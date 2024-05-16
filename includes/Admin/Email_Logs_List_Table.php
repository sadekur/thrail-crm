<?php

namespace Thrail\Crm\Admin;

if (!class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Email_Logs_List_Table extends \WP_List_Table {
	public function __construct() {
		parent::__construct([
			'singular' => 'email_log',
			'plural'   => 'email_logs',
			'ajax'     => true
		]);
	}

	public function get_columns() {
		return [
			'cb'        => '<input type="checkbox" />',
			'name'      => __( 'Name', 'thrail-crm' ),
			'email'     => __( 'Email', 'thrail-crm' ),
			'time'      => __( 'Time', 'thrail-crm' ),
		];
	}

	public function prepare_items() {
		$columns = $this->get_columns();
		$hidden = [];
		$sortable = [];
		$this->_column_headers = [ $columns, $hidden, $sortable ];

		global $wpdb;
		$table_name = $wpdb->prefix . 'thrail_crm_leads';
		$per_page = 10;
		$current_page = $this->get_pagenum();
		$query = "SELECT * FROM $table_name ORDER BY time DESC";
		$total_items = $wpdb->query( $query );

		if ( $total_items === false ) {
			echo "<p>Error retrieving data from database: " . $wpdb->last_error . "</p>";
		}

		$data = $wpdb->get_results( $wpdb->prepare( "$query LIMIT %d, %d", ( $current_page - 1 ) * $per_page, $per_page ), ARRAY_A );

		$this->set_pagination_args([
			'total_items' => $total_items,
			'per_page'    => $per_page
		]);

		$this->items = $data;
	}

	protected function column_default( $item, $column_name ) {
		return isset( $item[ $column_name ] ) ? $item[ $column_name ] : 'No data';
	}
	
	protected function column_time( $item ) {
		return sprintf( '<strong>%s</strong>', $item['time'] );
	}

	protected function column_cb($item) {
		return sprintf('<input type="checkbox" name="id[]" value="%d" />', $item['id']);
	}
}

