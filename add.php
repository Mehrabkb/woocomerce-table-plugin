<?php
    // this function add my menu to wordpress menu 
    function wtp_plugin_add_add_menu(){
        add_menu_page('اضافه کردن جدول', 'اضافه کردن جدول', 'manage_options','add-tbl', 'wtp_add_function');
    }
    add_action('admin_menu' , 'wtp_plugin_add_add_menu');
    function wtp_add_function(){
        global $wpdb ;
        ?>
            <div class="wrap">
            <form method="post" class="add-form">
                <label for="name">دسته بندی را انتخاب کنید</label>
                <select name="products" id="">
                    <?php
                        $categories = allCategories();
                        foreach ($categories as $key => $value) {
                            $all_ids = getAllProductsIdBySelectingCategoryId($value->term_id);
                            $all_ids_count = count($all_ids);
                            echo "<option value='{$value->term_id}'> {$value->name}  {$all_ids_count} </option>" ;
                        }
                    ?>
                </select>
                <input type="submit" class="button button-primary" name="submit_form" value="ثبت">
            </form>
            </div>

        <?php
        $finalTblShowingRecords = tableDataRender();
        tableShow($finalTblShowingRecords);
    if (isset($_POST['submit_form'])){
        $categoy_id = $_POST['products'];
        $result = $wpdb->get_results("SELECT * FROM `wp_wtcplugin` WHERE `category_id` = $categoy_id");
        if(!$result){
            $table_name = $wpdb->prefix . 'wtcplugin';
            $data = array(
                'category_id' => $categoy_id, // Assuming you're receiving this from a form
            );
            $format = array("%d");
            $wpdb->insert($table_name , $data , $format);
            $record_id = $wpdb->insert_id;
        }
        $finalTblShowingRecords = tableDataRender();
        tableShow($finalTblShowingRecords);
        ?>
    <?php
    }
}
    //this function get all products categories :)
    function allCategories(){
        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => 0
        ]);
        return $categories;
    }
    function getAllProductsIdBySelectingCategoryId($category_id){
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
        return $all_ids;
    }
    function tableDataRender(){
        global $wpdb;
        $allTableRecords = $wpdb->get_results("SELECT * FROM `wp_wtcplugin`");
        $finalTblShowingRecords = [];
        foreach ($allTableRecords as $atr){
            $category = get_term($atr->category_id);
            $category_name = $category->name;
            array_push($finalTblShowingRecords , [
                'id' => $atr->category_id,
                'category_name' => $category_name,
            ]);
        }
        return $finalTblShowingRecords;
    }
    function tableShow($finalTblShowingRecords){
        ?>
            <script>
                document.querySelector('table#finalTbl').remove();
            </script>
        <table id = 'finalTbl' class='display' style='width : 80%; margin : 0 auto;'>
            <thead>
                <tr>
                    <th>
                        ستون
                    </th>
                    <th>
                        دسته بندی
                    </th>
                    <th>
                         شورت کد
                    </th>
                </tr>
            </thead>
            <tbody>
            <?php
                $count = 0 ;
                for($i = 0 ; $i < count($finalTblShowingRecords) ; $i++){
                    ?>
                  <tr>
                    <td>  <?php echo ++$count; ?> </td>
                    <td> <?php echo $finalTblShowingRecords[$i]['category_name']; ?>  </td>
                    <td> <?php echo "[wtcTable cat_id=\"" .$finalTblShowingRecords[$i]['id'] . "\"]";  ?></td>
                  </tr>
                      <?php
                }
            ?>
        </tbody>
        </table>
<?php
    }