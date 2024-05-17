<?php
namespace Thrail\Crm\Admin;

if ( !class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Leads_List_Table extends \WP_List_Table {
	public function __construct() {
		parent::__construct( [
			'singular' => 'lead',
			'plural'   => 'leads',
			'ajax'     => true
		] );
	}

	public function get_columns() {
		return [
			'cb'      => '<input type="checkbox" />',
			'name'    => __( 'Name', 'thrail-crm' ),
			'email'   => __( 'Email', 'thrail-crm' ),
			'actions' => __( 'Actions', 'thrail-crm' ),
		];
	}
	protected function get_sortable_columns() {
		return [
			'name'  => [ 'name', true ],
			'email' => [ 'email', true ],
			'actions' => [ 'actions', true ],
		];
	}

	protected function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'name':
			case 'email':
				return sprintf( '<span class="%s-column">%s</span>', $column_name, $item[ $column_name ] );
			case 'actions':
				return sprintf(
					'<a href="#" class="edit-lead" data-id="%s">Edit</a> | <a href="#" class="delete-lead" data-id="%s">Delete</a>',
					$item[ 'id' ], $item[ 'id' ]
				);
			default:
				return isset( $item[ $column_name ] ) ? $item[ $column_name ] : 'Not set';
		}
	}

	public function prepare_items() {
		$columns 				= $this->get_columns();
		$hidden 				= [];
		$sortable 				= $this->get_sortable_columns();
		$this->_column_headers 	= [ $columns, $hidden, $sortable ];

		$search_term 			= isset( $_REQUEST[ 's' ] ) ? trim( $_REQUEST[ 's' ] ) : '';
		$data 					= $this->fetch_data( $search_term );
		$per_page 				= 10;
		$current_page 			= $this->get_pagenum();
		$total_items 			= count( $data );

		$this->set_pagination_args([
			'total_items' => $total_items,
			'per_page'    => $per_page
		]);

		$this->items = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

		if ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] === 'export_csv' ) {
			$this->export_to_csv( $data );
		}
	}
	protected function fetch_data( $search_term = '' ) {
		global $wpdb;
		$sql = "SELECT * FROM {$wpdb->prefix}thrail_crm_leads";
		if ( !empty( $search_term ) ) {
			$sql .= $wpdb->prepare( " WHERE name LIKE %s OR email LIKE %s", '%' . $wpdb->esc_like( $search_term ) . '%', '%' . $wpdb->esc_like( $search_term ) . '%');
		}
		return $wpdb->get_results( $sql, ARRAY_A );
	}
	public function export_to_csv( $data ) {
		ob_end_clean();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="leads.csv"' );
		$output = fopen('php://output', 'w');
		fputcsv( $output, array( 'ID', 'Name', 'Email', 'Date' ) );
		foreach ( $data as $row ) {
			fputcsv( $output, $row );
		}
		fclose( $output );
		exit;
	}
}
