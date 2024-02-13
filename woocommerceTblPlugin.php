<?php
/**
 * Plugin Name: woocommerce table plugin 
 * Description: this is the customize plugin for show products in tables .
 * Version: 1.0
 * Author : Mehrab Kordbacheh
 * Author URI : http://mehrabkordbacheh.com
 */

// Your plugin code goes here!

require_once (__DIR__ . '/add.php');
function tblAssetsCss() {
    wp_register_style('dataTblCss', 'https://cdn.datatables.net/v/dt/dt-1.13.10/datatables.min.css');
    wp_enqueue_style('dataTblCss');
    wp_register_style('myStyles' , plugins_url('woocommerceTblPlugin/assets/styles.css'));
    wp_enqueue_style('myStyles');
}   
add_action('admin_print_styles', 'tblAssetsCss');
function tblAssetsJs(){
    wp_register_script('dataTblJs', 'https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js');
    wp_enqueue_script('dataTblJs');
    wp_register_script('myScript' , plugins_url('woocommerceTblPlugin/assets/script.js'));
    wp_enqueue_script('myScript');
}
add_action('admin_print_scripts' , 'tblAssetsJs');