<?php
/**
 * @package vchat-addon
 * @version 1.0.0
 */
/*
Plugin Name: Vue API
Plugin URI: http://wordpress.org/plugins/vchat-addon/
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: Rubel Mahmud ( Sujan )
Version: 1.0.0
Author URI: https://vxlrubel.github.io/me/
*/


if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}


use VChat\Inc\AdminMenu;
use VChat\Inc\Api\RegisterApi;


final class VCHAT_ADDON {

    // set instance
    private static $instance;



    public function __construct(){
        add_action( 'init', [ $this, 'add_user_role'] );

        if( is_admin() ){
            new AdminMenu;
        }
        new RegisterApi;
    }

    public function add_user_role(){
        add_role( 'basic_user', 'Basic User', [
            'read' => true,
            'edit_posts' => true,
            'delete_posts' => true,
            'delete_published_posts' => true,
            'edit_published_posts' => true,
            'publish_posts' => true,
            'upload_files' => true,
        ] );
    }
    
    
    /**
     * create single tone evil
     *
     * @return void
     */
    public static function get_instance(){
        if ( is_null( self::$instance ) ){
            self::$instance = new self();
        }
        return self::$instance;
    }
}

function vchat_addon(){
    return VCHAT_ADDON::get_instance();
}

vchat_addon();
