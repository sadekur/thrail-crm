<?php

namespace Thrail\Crm\Admin;
use Thrail\Crm\Admin\Leads_List_Table;

// if (!class_exists('WP_List_Table')) {
//     require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
// }

// class Leads_List_Table extends \WP_List_Table {
//     function __construct() {
//         parent::__construct([
//             'singular' => 'lead',
//             'plural'   => 'leads',
//             'ajax'     => true
//         ]);
//     }

//     public function get_columns() {
//         return [
//             'cb'      => '<input type="checkbox" />',
//             'name'    => __('Name', 'thrail-crm'),
//             'email'   => __('Email', 'thrail-crm'),
//             'actions' => __('Actions', 'thrail-crm'),
//         ];
//     }

//     protected function column_default($item, $column_name) {
//         switch ($column_name) {
//             case 'name':
//             case 'email':
//                 return sprintf('<span class="%s-column">%s</span>', $column_name, $item[$column_name]);
//             case 'actions':
//                 return sprintf(
//                     '<a href="#" class="edit-lead" data-id="%s">Edit</a> | <a href="#" class="delete-lead" data-id="%s">Delete</a>',
//                     $item['id'], $item['id']
//                 );
//             default:
//                 return isset($item[$column_name]) ? $item[$column_name] : 'Not set';
//         }
//     }

//     public function prepare_items() {
//         global $wpdb;
//         $table_name = $wpdb->prefix . 'thrail_crm_leads';

//         $per_page = 10;
//         $current_page = $this->get_pagenum();
//         $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

//         $this->set_pagination_args([
//             'total_items' => $total_items,
//             'per_page'    => $per_page
//         ]);

//         $this->items = $wpdb->get_results($wpdb->prepare(
//             "SELECT * FROM $table_name LIMIT %d OFFSET %d;", $per_page, ($current_page - 1) * $per_page
//         ), ARRAY_A);
//     }
// }

class Menu {
    private $leads_list_table;

    function __construct() {
        add_action('admin_menu', [$this, 'admin_menu']);
    }

    public function admin_menu() {
        $hook = add_menu_page(
            'Thrail CRM',
            'Thrail CRM',
            'manage_options',
            'thrail-crm',
            [$this, 'crm_page'],
            'dashicons-businessman'
        );

        add_action("load-$hook", [$this, 'init_list_table']);
    }

    public function init_list_table() {
        $this->leads_list_table = new Leads_List_Table();
        add_screen_option('per_page', [
            'default' => 10,
            'option' => 'leads_per_page'
        ]);
    }

    public function crm_page() {
        echo '<div class="wrap"><h1 class="wp-heading-inline">Leads</h1>';
        $this->leads_list_table->prepare_items();
        $this->leads_list_table->display();
        echo '<div class="main">';
        echo '<div id="editLeadModal" title="Edit Lead" style="display:none;">';
	    echo '<form id="editLeadForm">';
	    echo '<label for="leadName">Name:</label>';
	    echo '<input type="text" id="leadName" name="name" class="widefat"><br><br>';
	    echo '<label for="leadEmail">Email:</label>';
	    echo '<input type="email" id="leadEmail" name="email" class="widefat">';
	    echo '<input type="hidden" id="leadId" name="id">';
	    echo '</form>';
	    echo '</div>';
	    echo '</div>';
	    echo '</div>';
    }
}