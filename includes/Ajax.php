<?php

namespace Thrail\Crm;

/**
 * Ajax handler class
 */
class Ajax {

	/**
	 * Class constructor
	 */
	function __construct() {
		add_action( 'wp_ajax_priv_thrail_form', [ $this, 'submit_enquiry'] );
		add_action( 'wp_ajax_nopriv_thrail_form', [ $this, 'submit_enquiry'] );
	}

	/**
	 * Handle Enquiry Submission
	 *
	 * @return void
	 */
	public function submit_enquiry() {

		$response = [
			 'status'   => 0,
			 'message'  =>__( 'Unauthorized!', 'codesigner' )
		];

		if( !wp_verify_nonce( $_POST['nonce'], 'thrail-crm') ) {
			wp_send_json( $response );
		}
	}
}
