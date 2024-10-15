<?php

namespace vchat\inc;

defined('ABSPATH') || exit;

class AdminMenu{
    
    public function __construct(){
        add_action( 'admin_menu', [$this, 'register_admin_menu'] );
    }

    public function register_admin_menu(){
        add_menu_page(
            'VChat panel',              // Page title
            'VChat panel',              // Menu title
            'manage_options',           // Capability required to view the menu
            'vchat',                    // Menu slug
            [ $this,'render_view' ],    // Function to display the menu page content
            'dashicons-format-chat',    // Icon URL or Dashicon class
            6                           // Position in the menu order
        );
    }

    public function render_view(){ ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <p>Welcome to the VChat settings page.</p>
            <!-- Add your custom content here -->
        </div>
    <?php }
}