<?php

namespace Thrail\Crm;
use Thrail\Crm\Helper;

require_once __DIR__ . '/../classes/Trait.php';

class Ajax {
	use Helper;

	function __construct() {
		// add_action('wp_ajax_thrail_form', [$this, 'handle_form_submission']); 
		// add_action('wp_ajax_nopriv_thrail_form', [$this, 'handle_form_submission']);
		add_action( 'wp_ajax_delete_lead', [ $this, 'delete_lead' ] );
		add_action( 'wp_ajax_update_lead', [ $this, 'update_lead' ] );
	}

	public function handle_form_submission() {
		check_ajax_referer( 'nonce', 'nonce' );

		$response = [
			 'status'	=> 0,
			 'message'	=>__( 'Unauthorized!', 'thrail-crm' )
		];

		if( !wp_verify_nonce( $_POST['nonce'], 'nonce' ) ) {
			wp_die( 'Nonce verification failed!' );
		}

		if ( isset( $_POST[ 'name' ] ) && isset( $_POST[ 'email' ] ) ) {
			$name 			= sanitize_text_field( $_POST['name'] );
			$email 			= sanitize_email( $_POST['email'] );

			global $wpdb;
			$table_name 	= $wpdb->prefix . 'thrail_crm_leads';
			$email_exists 	= $wpdb->get_var( $wpdb->prepare(
				"SELECT COUNT(*) FROM $table_name WHERE email = %s",
				$email
			) );
			if ( $email_exists ) {
				wp_send_json_error( [ 'message' => 'This email is already registered.' ] );
				return;
			}

			$inserted = $wpdb->insert(
				$table_name,
				[ 'name' => $name, 'email' => $email ],
				[ '%s', '%s' ]
			);
			update_option( 'thrail_crm_inserted', $inserted );

			if ( $inserted ) {
				$this->send_congratulatory_email( $name, $email );

				wp_send_json_success( ['message' => __( 'Thank you for subscribing!', 'thrail-crm' ) ] );
			} else {
				wp_send_json_error( [ 'message' => __( 'Failed to register. Please try again.', 'thrail-crm' ) ] );
			}
		}
	}

	public function delete_lead() {

        check_ajax_referer( 'nonce', 'nonce' );

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'nonce' ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid nonce', 'thrail-crm' ) ] );
            return;
        }
		if ( !current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => 'Unauthorized access' ] );
			return;
		}

		$lead_id = isset( $_POST[ 'id' ] ) ? intval( $_POST[ 'id' ] ) : 0;
		if (!$lead_id) {
			wp_send_json_error( [ 'message' => 'Lead ID is missing' ] );
			return;
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'thrail_crm_leads';
		if ( $wpdb->delete( $table_name, [ 'id' => $lead_id ], [ '%d' ] ) ) {
			wp_send_json_success( [ 'message' => 'Lead successfully deleted' ] );
		} else {
			wp_send_json_error( [ 'message' => 'Failed to delete lead' ] );
		}
	}

	public function update_lead() {

		check_ajax_referer( 'nonce', 'nonce' );
        
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'nonce' ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid nonce', 'thrail-crm' ) ] );
            return;
        }

		$lead_id 	= isset( $_POST[ 'id' ] ) ? intval( $_POST[ 'id' ] ) : 0;
		$name 		= isset( $_POST[ 'name' ] ) ? sanitize_text_field( $_POST[ 'name' ] ) : '';
		$email 		= isset( $_POST[ 'email' ] ) ? sanitize_email( $_POST[ 'email' ] ) : '';

		if ( !$lead_id || !$name || !$email ) {
			wp_send_json_error( [ 'message' => 'Missing or incorrect data' ] );
			return;
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'thrail_crm_leads';

		$email_exists = $wpdb->get_var( $wpdb->prepare(
			"SELECT id FROM $table_name WHERE email = %s AND id != %d",
			$email, $lead_id
		) );

		if ( $email_exists ) {
			wp_send_json_error( [ 'message' => 'Email already exists with another lead' ] );
			return;
		}

		if ( $wpdb->update( $table_name, [ 'name' => $name, 'email' => $email] , [ 'id' => $lead_id ], [ '%s', '%s' ], ['%d' ] ) ) {
			wp_send_json_success( [ 'message' => 'Lead successfully updated' ] );
		} else {
			wp_send_json_error( [ 'message' => 'Failed to update lead' ] );
		}
	}
}
