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
if ( ! function_exists( 'thrail_crm_activate' ) ) :
function thrail_crm_deactivate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'thrail_crm_leads';
    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
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
