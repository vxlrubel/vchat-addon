<?php

namespace VChat\Inc\Api;

defined('ABSPATH') || exit;


class RegisterApi {
    public function __construct(){
        add_action( 'rest_api_init', [ $this, 'user_registration' ] );
    }

    public function user_registration(){
        $register_user = new RegisterUser();
        $register_user->register_routes();
    }
}