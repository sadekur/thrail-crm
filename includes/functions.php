<?php
// Exit if accessed directly
if ( !defined('ABSPATH' ) ) {
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

if ( ! function_exists( 'handle_new_subscription' ) ):
	function handle_new_subscription($name, $email) {
	    send_congratulatory_email( $name, $email );
	    $args = [ 'name' => $name, 'email' => $email ];
	    wp_schedule_single_event( time() + MINUTE_IN_SECONDS, 'thrail_crm_send_follow_up_email', $args );
	}
endif;

if ( ! function_exists( 'send_congratulatory_email' ) ):
	function send_congratulatory_email( $name, $email ) {
	    $subject = "Congratulations on subscribing!";
	    $message = "Hi {$name},\n\nThank you for subscribing to our newsletter! We look forward to bringing you the latest updates and information.\n\nBest regards,\nThe Thrail CRM Team";
	    $headers = [ 'Content-Type: text/plain; charset=UTF-8' ];

	    wp_mail( $email, $subject, $message, $headers );
	}
endif;

add_action( 'thrail_crm_send_follow_up_email', 'send_follow_up_email' );

function send_follow_up_email($args) {
    $subject = "Reminder: Explore More Features!";
    $message = "Hi {$args['name']},\n\nJust a reminder that you signed up recently! Don't forget to check out all our features and offerings.\n\nBest regards,\nThe Thrail CRM Team";
    $headers = [ 'Content-Type: text/plain; charset=UTF-8' ];

    wp_mail( $args[ 'email' ], $subject, $message, $headers);
}

