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
        $tBody .= "<tr>
                        <td> ". $product->get_name() ." </td>
                        <td> ". number_format($product->get_price()) ." </td>
                        <td> <button type='button' onclick='changInputCount(this)' data-role='decrease'>-</button> <input id='count' type='number' value='0' min='0' max='100' disabled> <button type='button' onclick='changInputCount(this)' data-role='increase' class='increase-button' >+</button> </td>
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
                            </tr>
                        </thead>
                        <tbody>
                            $tBody
                        </tbody>
                    </table>";

    return $outPutHtml;

}

add_shortcode("wtcTable" , "wtc_ShortCode");