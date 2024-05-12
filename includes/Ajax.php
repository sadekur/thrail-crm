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
			if ( $email_exists ) {
				wp_send_json_error(['message' => 'This email is already registered.']);
				return;
			}

			$wpdb->insert(
				$table_name,
				[
					'name' => $name,
					'email' => $email
				]
			);

			wp_send_json_success(['message' => 'Thank you for subscribing!']);
		} else {
			wp_send_json_error(['message' => 'Required fields are missing.']);
		}
	}
}
