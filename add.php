<?php
    function wtp_plugin_add_add_menu(){
        add_menu_page('اضافه کردن جدول', 'اضافه کردن جدول', 'manage_options','add-tbl', 'wtp_add_function');
    }
    add_action('admin_menu' , 'wtp_plugin_add_add_menu');