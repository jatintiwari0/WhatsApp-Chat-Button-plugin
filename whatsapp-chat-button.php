<?php
/**
 * Plugin Name: WhatsApp Chat Button
 * Description: Adds a WhatsApp chat button to your WordPress site.
 * Version: 1.1
 * Author: Getallscripts
 * Author URI: https://getallscripts.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Enqueue styles and scripts
function wcb_enqueue_scripts() {
    wp_enqueue_style( 'wcb-styles', plugin_dir_url( __FILE__ ) . 'css/whatsapp-chat-button.css' );
    wp_enqueue_script( 'wcb-script', plugin_dir_url( __FILE__ ) . 'js/whatsapp-chat-button.js', array( 'jquery' ), false, true );
}
add_action( 'wp_enqueue_scripts', 'wcb_enqueue_scripts' );

// Add WhatsApp chat button
function wcb_add_whatsapp_button() {
    $phone_number = get_option( 'wcb_phone_number', '1234567890' );
    $message = get_option( 'wcb_default_message', 'Hello! How can we help you?' );

    echo '<a href="https://wa.me/' . esc_attr( $phone_number ) . '?text=' . urlencode( $message ) . '" class="whatsapp-chat-button" target="_blank">
        <img src="' . plugin_dir_url( __FILE__ ) . 'images/whatsapp-icon.png" alt="Chat with us on WhatsApp">
    </a>';
}
add_action( 'wp_footer', 'wcb_add_whatsapp_button' );

// Add settings menu
function wcb_add_settings_menu() {
    add_options_page( 
        'WhatsApp Chat Button Settings', 
        'WhatsApp Chat Button', 
        'manage_options', 
        'wcb-settings', 
        'wcb_settings_page' 
    );
}
add_action( 'admin_menu', 'wcb_add_settings_menu' );

// Settings page content
function wcb_settings_page() {
    ?>
    <div class="wrap">
        <h1>WhatsApp Chat Button Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'wcb_settings_group' );
            do_settings_sections( 'wcb-settings' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
function wcb_register_settings() {
    register_setting( 'wcb_settings_group', 'wcb_phone_number' );
    register_setting( 'wcb_settings_group', 'wcb_default_message' );

    add_settings_section( 
        'wcb_settings_section', 
        'Settings', 
        'wcb_settings_section_callback', 
        'wcb-settings' 
    );

    add_settings_field( 
        'wcb_phone_number', 
        'Phone Number', 
        'wcb_phone_number_callback', 
        'wcb-settings', 
        'wcb_settings_section' 
    );

    add_settings_field( 
        'wcb_default_message', 
        'Default Message', 
        'wcb_default_message_callback', 
        'wcb-settings', 
        'wcb_settings_section' 
    );
}
add_action( 'admin_init', 'wcb_register_settings' );

function wcb_settings_section_callback() {
    echo 'Enter your WhatsApp number and default message below:';
}

function wcb_phone_number_callback() {
    $phone_number = get_option( 'wcb_phone_number', '' );
    echo '<input type="text" name="wcb_phone_number" value="' . esc_attr( $phone_number ) . '" />';
}

function wcb_default_message_callback() {
    $message = get_option( 'wcb_default_message', '' );
    echo '<textarea name="wcb_default_message" rows="5" cols="50">' . esc_textarea( $message ) . '</textarea>';
}
?>