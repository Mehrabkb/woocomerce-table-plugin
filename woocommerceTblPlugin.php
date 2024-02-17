<?php
/**
 * Plugin Name: woocommerce table plugin 
 * Description: this is the customize plugin for show products in tables .
 * Version: 1.0
 * Author : Mehrab Kordbacheh
 * Author URI : http://mehrabkordbacheh.com
 */

// Your plugin code goes here!
if( !defined('ABSPATH')){
    die('شما مجاز به دسترسی نمیباشید');
}

require_once (__DIR__ . '/add.php');
function tblAssetsCss() {
    wp_register_style('dataTblCss', 'https://cdn.datatables.net/v/dt/dt-1.13.10/r-2.5.0/datatables.min.css');
    wp_enqueue_style('dataTblCss');
    wp_register_style('dataTblResponsiveCss' , 'https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css');
    wp_enqueue_style('dataTblResponsiveCss');
    wp_register_style('inputNumSpinCss' , plugins_url('woocommerceTblPlugin/assets/inputNumSpin/css/input-numspin.min.css'));
    wp_enqueue_style('inputNumSpinCss');
    wp_register_style('myStyles' , plugins_url('woocommerceTblPlugin/assets/styles.css'));
    wp_enqueue_style('myStyles');
}   
add_action('admin_print_styles', 'tblAssetsCss');
add_action('wp_print_styles' , 'tblAssetsCss');
function tblAssetsJs(){
    wp_register_script('jquery' , 'https://code.jquery.com/jquery-3.7.1.js');
    wp_enqueue_script('jquery');
    wp_register_script('dataTblJs', 'https://cdn.datatables.net/v/dt/dt-1.13.10/r-2.5.0/datatables.min.js');
    wp_enqueue_script('dataTblJs');
    wp_register_script('dataTblResponsiveJs' , 'https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js');
    wp_enqueue_script('dataTblResponsiveJs');
    wp_register_script('inputNumSpinJs' , plugins_url('woocommerceTblPlugin/assets/inputNumSpin/js/input-numspin.min.js'));
    wp_enqueue_script('inputNumSpinJs');
    wp_register_script('myScript' , plugins_url('woocommerceTblPlugin/assets/script.js'));
    wp_enqueue_script('myScript');

}
add_action('admin_print_scripts' , 'tblAssetsJs');
add_action('wp_enqueue_scripts' , 'tblAssetsJs');
function jal_install() {
    global $wpdb;
    $wp_track_table = $wpdb->prefix . "wtcplugin";
    $charset = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $wp_track_table(
        wtc_id int(10) NOT NULL AUTO_INCREMENT,
        category_id int ,
        PRIMARY KEY (wtc_id)
    ) $charset ;";
    require_once(ABSPATH . "wp-admin/includes/upgrade.php");
    dbDelta($sql);
}

// Register the activation hook to call the table creation function
register_activation_hook(__FILE__, "jal_install");

function wtc_ShortCode($atts){
    $category_id  = (int) $atts['cat_id'];
    $args = array(
        'post_type' => 'product',
        'numberposts' => -1,
        'post_status' => 'publish',
        'fields' => 'ids',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $category_id,
                'operator' => 'IN',
            ),
        ),
    );
    $all_ids = get_posts($args);
    $tBody = "";
    for($i = 0 ; $i < count($all_ids) ; $i++){
        $product = wc_get_product($all_ids[$i]);
        $tBody .= "<tr data-url-ajax='". admin_url('admin-ajax.php') ."' data-product-id='". $all_ids[$i] ."'>
                        <td> ". $product->get_name() ." </td>
                        <td class='unit-price' data-pure-price='".$product->get_price()."'> ". number_format($product->get_price()) ." </td>
                        <td> <button type='button' class='button' onclick='changInputCount(this)' data-role='decrease'>-</button> <input style='width: 40px;' id='count' type='number' value='0' min='0' max='100' disabled> <button type='button' class='button' onclick='changInputCount(this)' data-role='increase' class='increase-button' >+</button> </td>
                        <td class='final-price'>".number_format(0)."</td>
                        <td>" . add_to_cart_button($all_ids[$i]) . "</td>
                    </tr>";
    }
    $outPutHtml = "<table id='shortcode-tbl' class=\"cell-border display nowrap \" style=\" width: 100%; \">
                        <thead>
                            <tr>
                                <th>
                                    نام محصول 
                                </th>
                                <th>
                                    قیمت 
                                </th>
                                <th>
                                    تعداد
                                </th>
                                <th>
                                    قیمت کل
                                </th>
                                <th>
                                    عملیات
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            $tBody
                        </tbody>
                    </table>";

    return $outPutHtml;

}

function add_to_cart_button($product_id) {
    // Output your button HTML here
    return '<button class="add-to-cart-btn" data-product-id="'. $product_id .'">افزودن به سبد خرید</button>';
}
add_action('woocommerce_after_single_product_summary', 'add_to_cart_button');
add_action('wp_ajax_custom_add_to_cart', 'custom_add_to_cart');
add_action('wp_ajax_nopriv_custom_add_to_cart', 'custom_add_to_cart');
function custom_add_to_cart() {
    global $woocommerce;
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    $result = WC()->cart->add_to_cart($product_id, $quantity);
    if($result){
        return $woocommerce;
    }
    die();
}
add_shortcode("wtcTable" , "wtc_ShortCode");
add_action('wp_footer', 'my_plugin_ajax_handler');
function my_plugin_ajax_handler() {
    // Handle AJAX request here?>
<script type="text/javascript">
    jQuery(document).ready(function($){
        console.log('hello');
        $('.add-to-cart-btn').on('click' , function(e){
            e.preventDefault();
            let that = $(this);
            let url = that.parent().parent().attr('data-url-ajax');
            let product_id = that.parent().parent().attr('data-product-id');
            let inputCount = parseInt(that.parent().parent().find('input#count').val()) ;
            var data = {
                'action': 'custom_add_to_cart',
                'product_id': product_id,
                'quantity' : inputCount
            };
            $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
                // Optionally, you can handle the response here
                console.log(response);
                alert('با موفقیت به سبد خرید اضافه شد');
            });
    });
    });
</script>
<?php
    // You can access POST data using $_POST array
    // Return the response using echo or wp_send_json()
    wp_send_json_success('Success!');
    wp_die(); // Always end with wp_die()
}