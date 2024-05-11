<?php

namespace Thrail\Crm;

/**
 * Ajax handler class
 */
class Ajax {

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_priv_thrail_form', [ $this, 'submit_enquiry'] );
		add_action( 'wp_ajax_nopriv_thrail_form', [ $this, 'submit_enquiry'] );
	}

	/**
	 * Handle Enquiry Submission
	 *
	 * @return void
	 */
	public function handle_thrail_form() {
	   	$response['status'] 	= 0;
		$response['message'] 	= __( 'Something is wrong!', 'thrail-crm' );
		
		if( !wp_verify_nonce( $_POST['nonce'], 'thrail-crm' ) ) {
			$response['message'] = __( 'Unauthorized!', 'thrail-crm' );
		    wp_send_json( $response );
		}

	    $name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
		$email = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';

	    wp_send_json_success(['message' => 'Data processed successfully']);
	}
}
