<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Function to set up the database table for Thrail CRM
 */
if( ! function_exists( 'thrail_crm_activate' ) ) :
	function thrail_crm_activate() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		global $wpdb;
		$table_name = $wpdb->prefix . 'thrail_crm_leads';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name tinytext NOT NULL,
			email text NOT NULL,
			time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		dbDelta($sql);
	}
endif;

if ( ! function_exists( 'send_congratulatory_email' ) ):
	function send_congratulatory_email( $name, $email ) {
		$subject = "Congratulations on subscribing!";
		$message = "Hi {$name},\n\nThank you for subscribing to our newsletter! We look forward to bringing you the latest updates and information.\n\nBest regards,\nThe Thrail CRM Team";
		$headers = ['Content-Type: text/plain; charset=UTF-8'];

		wp_mail( $email, $subject, $message, $headers );
	}
endif;

if ( ! function_exists( 'fetch_data' ) ):
	function fetch_data() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'thrail_crm_leads';
        $sql = "SELECT * FROM {$table_name} ORDER BY time DESC";
        $results = $wpdb->get_results($sql, ARRAY_A);

        if ($results === false) {
            echo "<p>Error retrieving data from database: " . $wpdb->last_error . "</p>";
            return [];
        }

        return $results;
    }
endif;

if ( ! function_exists( 'column_default' ) ):
    function column_default($item, $column_name) {
        return isset($item[$column_name]) ? esc_html($item[$column_name]) : 'No data';
    }
endif;

if ( ! function_exists( 'column_time' ) ):
    function column_time($item) {
        return sprintf('<strong>%s</strong>', esc_html($item['time']));
    }
endif;

if ( ! function_exists( 'column_cb' ) ):
    function column_cb($item) {
        return sprintf('<input type="checkbox" name="id[]" value="%d" />', esc_attr($item['id']));
    }
endif;