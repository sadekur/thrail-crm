<?php

namespace Thrail\Crm\Admin;

/**
 * The Menu handler class
 */
class Menu {

    public $addressbook;

    /**
     * Initialize the class
     */
    function __construct( ) {

        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    /**
     * Register admin menu
     *
     * @return void
     */
     public function admin_menu() {
        // Add top-level menu page
        add_menu_page(
            'Thrail CRM',
            'Thrail CRM',
            'manage_options',
            'thrail-crm',
            [$this, 'crm_page'],
            'dashicons-businessman'
        );

        // Add submenu page
        add_submenu_page(
            'thrail-crm',
            'Manage Leads',
            'Manage Leads',
            'manage_options',
            'thrail-crm-leads',
            [$this, 'leads_page']
        );
    }

    /**
     * Callback for the main CRM page content
     *
     * @return void
     */
    public function crm_page() {
        echo '<div class="wrap"><h1>Thrail CRM Dashboard</h1><p>Welcome to Thrail CRM.</p></div>';
    }

    /**
     * Callback for the leads management page content
     *
     * @return void
     */
    public function leads_page() {
        echo '<div class="wrap"><h1>Manage Leads</h1><p>Here you can manage your leads.</p></div>';
    }
}
