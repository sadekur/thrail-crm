<?php
namespace Thrail\Crm;

class RestAPI {
	use Helper;
	public function __construct() {
	require_once __DIR__ . '/../classes/Trait.php';
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
		add_action( 'thrail_crm_send_follow_up_email', [ $this, 'send_follow_up_email' ] );
	}

	public function register_routes() {
		register_rest_route( 'thrail-crm/v1', '/submit', [
			'methods' => 'POST',
			'callback' => [ $this, 'handle_form_submission' ],
			'permission_callback' => '__return_true',
			'args' => [
				'name' => [
					'required' => true,
					'sanitize_callback' => 'sanitize_text_field'
				],
				'email' => [
					'required' => true,
					'sanitize_callback' => 'sanitize_email'
				]
			]
		] );
	}

		public function handle_new_subscription( $name, $email ) {
		// $this->send_congratulatory_email( $name, $email );
		$args = [ 'name' => $name, 'email' => $email ];
		wp_schedule_single_event( time() + MINUTE_IN_SECONDS, 'thrail_crm_send_follow_up_email', $args );
	}


	public function send_follow_up_email( $args ) {
		$subject = "Reminder: Explore More Features!";
		$message = "Hi {$args['name']},\n\nJust a reminder that you signed up recently! Don't forget to check out all our features and offerings.\n\nBest regards,\nThe Thrail CRM Team";
		$headers = ['Content-Type: text/plain; charset=UTF-8'];

		wp_mail( $args[ 'email' ], $subject, $message, $headers );
	}

	public function handle_form_submission( $request ) {
		$name 	= $request->get_param( 'name' );
		$email 	= $request->get_param( 'email' );

		global $wpdb;
		$table_name = $wpdb->prefix . 'thrail_crm_leads';

		$email_exists = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM $table_name WHERE email = %s",
			$email
		) );
		if ( $email_exists ) {
			return new \WP_Error( 'email_exists', 'This email is already registered.', [ 'status' => 400 ] );
		}

		$inserted = $wpdb->insert(
			$table_name,
			[ 'name' => $name, 'email' => $email ],
			[ '%s', '%s' ]
		);

		if ( $inserted ) {
			$this->send_congratulatory_email( $name, $email );
			// $this->handle_new_subscription( $name, $email );
			$this->send_follow_up_email( $args );
			return new \WP_REST_Response( [ 'message' => 'Thank you for subscribing!' ], 200 );
		} else {
			return new \WP_Error( 'db_error', 'Failed to register. Please try again.', [ 'status' => 500 ] );
		}
	}
}
