<?php

namespace VChat\Inc\Api;

defined('ABSPATH') || exit;

use WP_REST_Server;
use WP_REST_Controller;

class RegisterUser extends WP_REST_Controller {
    
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct(){
        $this->namespace = 'vchat/v1';
        $this->rest_base = '/register';
    }
    
    /**
     * Method register_routes
     *
     * @return void
     */
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
    
    /**
     * Method check_permission
     *
     * @return void
     */
    public function check_permission(){
        return true;
    }
        
    /**
     * Method check_empty_field
     *
     * @param string $name [This param use for define key of array]
     * @param string $message [This field define value of array.]
     *
     * @return void
     */
    protected function check_empty_field( string $name = 'invalid', string $message = 'Asign invalid field.' ){
        $args = [
            $name => $message
        ];
        return $args;
    }
        
    /**
     * Method register_new_user
     *
     * @param $request $request [This param receive user information for create new user.]
     *
     * @return void
     */
    public function register_new_user($request) {
        $params = $request->get_params();
    
        $get_details = [
            'name'     => sanitize_text_field($params['name']),
            'email'    => sanitize_email($params['email']),
            'password' => $params['password'],
            'username' => sanitize_user($params['username']),
        ];
        
        if( !$params['name'] && !$params['email'] && !$params['password'] && !$params['username'] ){
            return 'Please provide the valid information.';
        }

        if( !$params['name'] ){
            return $this->check_empty_field('name', 'Please insert name.');
        }

        if( !$params['email'] ){
            return $this->check_empty_field('email', 'Please insert email.');
        }

        if( !$params['password'] ){
            return $this->check_empty_field('password', 'Please insert password.');
        }
        
        if( !$params['username'] ){
            return $this->check_empty_field('username', 'Please insert username.');
        }
    
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