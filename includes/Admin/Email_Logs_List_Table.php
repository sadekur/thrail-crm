<?php

namespace Thrail\Crm\Admin;
require_once __DIR__ . '/../../classes/Trait.php';

use Thrail\Crm\Helper;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Email_Logs_List_Table extends \WP_List_Table {
	use Helper;
    public function __construct() {
        parent::__construct([
            'singular' => 'email_log',
            'plural'   => 'email_logs',
            'ajax'     => true
        ]);
    }

    public function get_columns() {
        return [
            'cb'    => '<input type="checkbox" />',
            'name'  => __('Name', 'thrail-crm'),
            'email' => __('Email', 'thrail-crm'),
            'time'  => __('Time', 'thrail-crm'),
        ];
    }

    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = [];
        $sortable = []; // Define sortable columns
        $this->_column_headers = [$columns, $hidden, $sortable];

        $this->items = $this->fetch_data();
        $total_items = count($this->items);

        $per_page = 10;
        $current_page = $this->get_pagenum();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page
        ]);

        $this->items = array_slice($this->items, (($current_page - 1) * $per_page), $per_page);
    }

    
}