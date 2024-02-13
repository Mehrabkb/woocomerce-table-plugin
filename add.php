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
                <select name="" id="">
                    <?php
                        $categories = get_terms(['taxonomy' => 'product_cat']);
                        foreach ($categories as $key => $value) {
                           echo "<option> {$value->name} </option>" ;
                        }
                    ?>
                </select>
                <br>
                <input type="submit" class="button button-primary" name="submit_form" value="ثبت">
            </form>
            </div>
        <?php
    }