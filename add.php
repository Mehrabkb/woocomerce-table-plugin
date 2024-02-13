<?php
    function wtp_plugin_add_add_menu(){
        add_menu_page('اضافه کردن جدول', 'اضافه کردن جدول', 'manage_options','add-tbl', 'wtp_add_function');
    }
    add_action('admin_menu' , 'wtp_plugin_add_add_menu');
    function wtp_add_function(){
        ?>
            <div class="wrap">
            <form method="post">
                <label for="name">دسته بندی را انتخاب کنید</label>
                <select name="products" id="">
                    <?php
                        $categories = get_terms([
                            'taxonomy' => 'product_cat',
                            'hide_empty' => true,
                            'parent' => 0
                        ]);
                        foreach ($categories as $key => $value) {
                            $args = array(
                                'post_type' => 'product',
                                'numberposts' => -1,
                                'post_status' => 'publish',
                                'fields' => 'ids',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'product_cat',
                                        'field' => 'term_id',
                                        'terms' => $value->term_id,
                                        'operator' => 'IN',
                                    ),
                                ),
                            );
                            $all_ids = get_posts($args);
                            $all_ids_count = count($all_ids);
                            echo "<option value='{$value->term_id}'> {$value->name}  {$all_ids_count} </option>" ;
                        }
                    ?>
                </select>
                <br>
                <input type="submit" class="button button-primary" name="submit_form" value="ثبت">
            </form>
            </div>
        <?php
    if (isset($_POST['submit_form'])){
        $categoy_id = $_POST['products'];
        $args = array(
            'post_type' => 'product',
            'numberposts' => -1,
            'post_status' => 'publish',
            'fields' => 'ids',
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $categoy_id,
                    'operator' => 'IN',
                ),
            ),
        );
        $all_ids = get_posts($args);    
        for($i = 0 ; $i < count($all_ids) ; $i++){
            $product = wc_get_product($all_ids[$i]);
            echo "<p> {$product->get_name()} </p>";
            echo "<p> {$product->get_price()} </p> ";
            print('<pre>' . print_r($product->get_attributes()) . '</pre>');
            // print("<pre>".print_r($product->get_price()) . "</pre>");
        }

    }    
}