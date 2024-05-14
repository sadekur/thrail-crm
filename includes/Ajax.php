<?php

namespace Thrail\Crm;
use Thrail\Crm\Helper;

class Ajax {

	function __construct() {
		add_action('wp_ajax_thrail_form', [$this, 'handle_form_submission']); 
		add_action('wp_ajax_nopriv_thrail_form', [$this, 'handle_form_submission']); 
	}

	public function handle_form_submission() {
		check_ajax_referer('thrail-admin-nonce', 'nonce');

		$response = [
			 'status'	=> 0,
			 'message'	=>__( 'Unauthorized!', 'thrail-crm' )
		];

		if( !wp_verify_nonce( $_POST['nonce'], 'thrail-admin-nonce' ) ) {
			wp_send_json( $response );
		}

		if ( isset( $_POST['name'] ) && isset( $_POST['email'] ) ) {
			$name = sanitize_text_field( $_POST['name'] );
			$email = sanitize_email( $_POST['email'] );

			global $wpdb;
			$table_name = $wpdb->prefix . 'thrail_crm_leads';

			$email_exists = $wpdb->get_var($wpdb->prepare(
				"SELECT COUNT(*) FROM $table_name WHERE email = %s",
				$email
			));
			update_option('dsfew', $email_exists);
			if ( $email_exists ) {
				wp_send_json_error(['message' => 'This email is already registered.']);
				return;
			}

			$inserted = $wpdb->insert(
				$table_name,
				['name' => $name, 'email' => $email],
				['%s', '%s']
			);

			if ( $inserted ) {
				send_congratulatory_email( $name, $email );

				wp_send_json_success(['message' => __('Thank you for subscribing!', 'codesigner')]);
			} else {
				wp_send_json_error(['message' => __('Failed to register. Please try again.', 'codesigner')]);
			}
		}
	}

	// private function send_congratulatory_email( $name, $email ) {
	// 	$subject = "Congratulations on subscribing!";
	// 	$message = "Hi {$name},\n\nThank you for subscribing to our newsletter! We look forward to bringing you the latest updates and information.\n\nBest regards,\nThe Thrail CRM Team";
	// 	$headers = ['Content-Type: text/plain; charset=UTF-8'];

	// 	wp_mail( $email, $subject, $message, $headers );
	// }
}
