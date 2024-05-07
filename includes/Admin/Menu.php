<?php

namespace WeDevs\Academy\Admin;

/**
 * The Menu handler class
 */
class Menu {

    public $addressbook;

    /**
     * Initialize the class
     */
    function __construct( $addressbook ) {
        $this->addressbook = $addressbook;

        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    /**
     * Register admin menu
     *
     * @return void
     */
    public function admin_menu() {
        $parent_slug = 'wedevs-academy';
        $capability = 'manage_options';

        $hook = add_menu_page( __( 'weDevs Academy', 'wedevs-academy' ), __( 'Academy', 'wedevs-academy' ), $capability, $parent_slug, [ $this->addressbook, 'plugin_page' ], 'dashicons-welcome-learn-more' );
        add_submenu_page( $parent_slug, __( 'Address Book', 'wedevs-academy' ), __( 'Address Book', 'wedevs-academy' ), $capability, $parent_slug, [ $this->addressbook, 'plugin_page' ] );
        add_submenu_page( $parent_slug, __( 'Settings', 'wedevs-academy' ), __( 'Settings', 'wedevs-academy' ), $capability, 'wedevs-academy-settings', [ $this, 'settings_page' ] );

        add_action( 'admin_head-' . $hook, [ $this, 'enqueue_assets' ] );
    }

    /**
     * Handles the settings page
     *
     * @return void
     */
    public function settings_page() {
        echo 'Settings Page';
        $post_data = wp_remote_get( 'https://dummyjson.com/posts' );

// Check if the request was successful
if ( ! is_wp_error( $post_data ) && wp_remote_retrieve_response_code( $post_data ) === 200 ) {
    // Retrieve the response body
    $post_data = wp_remote_retrieve_body( $post_data );

    // Decode the JSON data
    $post_data = json_decode( $post_data );

    // Check if JSON decoding was successful
    if ( $post_data !== null ) {
        // Iterate through each post and display its title
        foreach ( $post_data as $post ) {
            echo '<li>' . esc_html( $post->title ) . '</li>';
        }
    } else {
        // JSON decoding failed
        echo '<li>Error: Unable to decode JSON data</li>';
    }
} else {
    // Request failed
    echo '<li>Error: Unable to fetch posts from the remote URL</li>';
}
    }

    /**
     * Enqueue scripts and styles
     *
     * @return void
     */
    public function enqueue_assets() {
        wp_enqueue_style( 'thrail-admin-style' );
        wp_enqueue_script( 'thrail-admin-script' );
    }
}
