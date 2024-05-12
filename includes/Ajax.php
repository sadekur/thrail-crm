<?php

namespace Thrail\Crm;

class Ajax {

	function __construct() {
		add_action('wp_ajax_thrail_form', [$this, 'handle_form_submission']); 
		add_action('wp_ajax_nopriv_thrail_form', [$this, 'handle_form_submission']); 
	}

	public function handle_form_submission() {
		check_ajax_referer('thrail-admin-nonce', 'nonce');

		if ( isset( $_POST['name'] ) && isset( $_POST['email'] ) ) {
			$name = sanitize_text_field( $_POST['name'] );
			$email = sanitize_email( $_POST['email'] );

			global $wpdb;
			$table_name = $wpdb->prefix . 'thrail_crm_leads';

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
