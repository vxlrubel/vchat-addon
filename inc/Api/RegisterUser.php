<?php

namespace VChat\Inc\Api;

defined('ABSPATH') || exit;

use WP_REST_Server;
use WP_REST_Controller;

class RegisterUser extends WP_REST_Controller {

    public function __construct(){
        $this->namespace = 'vchat/v1';
        $this->rest_base = '/register';
    }

    public function register_routes(){
        $args = [
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => [ $this, 'register_new_user' ],
                'permission_callback' => [ $this, 'check_permission' ]
            ]
        ];
        register_rest_route( $this->namespace, $this->rest_base, $args );
    }

    public function check_permission(){
        return true;
    }

    public function register_new_user($request) {
        $params = $request->get_params();
    
        $get_details = [
            'name'     => sanitize_text_field($params['name']),
            'email'    => sanitize_email($params['email']),
            'password' => $params['password'],
            'username' => sanitize_user($params['username']),
        ];
    
        // Check if username already exists.
        if ( username_exists( $get_details['username']) ) {
            $args = [
                'status'  => 400,
                'fields'  => 'username',
                'message' => 'Username already exists'
            ];
            return $args;
        }
    
        // Check if email already exists.
        if( email_exists( $get_details['email'] ) ){
            $args = [
                'status'  => 400,
                'fields'  => 'email',
                'message' => 'Email already exists'
            ];
            return $args;
        }

        // Prepare user data for registration.
        $userdata = [
            'user_login' => $get_details['username'],
            'user_pass'  => $get_details['password'],
            'user_email' => $get_details['email'],
            'first_name' => $get_details['name'],
            'role'       => 'basic_user',
        ];
    
        // Insert the new user into the database.
        $user_id = wp_insert_user($userdata);
    
        // Check for errors.
        if (is_wp_error($user_id)) {
            return new WP_Error('registration_failed', $user_id->get_error_message());
        }
    
        // Return a success message or the new user details.
        return [
            'status'  => 200,
            'fields'  => 'success',
            'message' => 'Registered successfully'
        ];
    }

    
}