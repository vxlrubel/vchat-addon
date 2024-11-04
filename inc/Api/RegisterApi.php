<?php

namespace VChat\Inc\Api;

defined('ABSPATH') || exit;


class RegisterApi {    
    
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct(){
        add_action( 'rest_api_init', [ $this, 'user_registration' ] );
    }
    
    /**
     * Method user_registration
     *
     * @return void
     */
    public function user_registration(){
        $register_user = new RegisterUser();
        $register_user->register_routes();
    }
}