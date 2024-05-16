<?php
namespace Thrail\Crm\Admin;

if (!class_exists('WP_List_Table')) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Leads_List_Table extends \WP_List_Table {
	public function __construct() {
		parent::__construct([
			'singular' => 'lead',
			'plural'   => 'leads',
			'ajax'     => true
		]);
	}

	public function get_columns() {
		return [
			'cb'      => '<input type="checkbox" />',
			'name'    => __('Name', 'thrail-crm'),
			'email'   => __('Email', 'thrail-crm'),
			'actions' => __('Actions', 'thrail-crm'),
		];
	}

	protected function column_default($item, $column_name) {
		switch ($column_name) {
			case 'name':
			case 'email':
				return sprintf('<span class="%s-column">%s</span>', $column_name, $item[$column_name]);
			case 'actions':
				return sprintf(
					'<a href="#" class="edit-lead" data-id="%s">Edit</a> | <a href="#" class="delete-lead" data-id="%s">Delete</a>',
					$item['id'], $item['id']
				);
			default:
				return isset($item[$column_name]) ? $item[$column_name] : 'Not set';
		}
	}

	public function prepare_items() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'thrail_crm_leads';

		$per_page = 10;
		$current_page = $this->get_pagenum();
		$total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

		$this->set_pagination_args([
			'total_items' => $total_items,
			'per_page'    => $per_page
		]);

		$this->items = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM $table_name LIMIT %d OFFSET %d;", $per_page, ($current_page - 1) * $per_page
		), ARRAY_A);
	}
}
