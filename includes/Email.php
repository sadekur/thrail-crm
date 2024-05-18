<?php

namespace Thrail\Crm;
require_once __DIR__ . '/../classes/Trait.php';
class Email {
	use Helper;
	function __construct() {
		add_action( 'thrail_crm_send_follow_up_email', [ $this, 'send_follow_up_email' ] );
	}

	public function handle_new_subscription( $name, $email ) {
		$this->send_congratulatory_email( $name, $email );
		$args = [ 'name' => $name, 'email' => $email ];
		wp_schedule_single_event( time() + MINUTE_IN_SECONDS, 'thrail_crm_send_follow_up_email', $args );
	}


	public function send_follow_up_email( $args ) {
		$subject = "Reminder: Explore More Features!";
		$message = "Hi {$args['name']},\n\nJust a reminder that you signed up recently! Don't forget to check out all our features and offerings.\n\nBest regards,\nThe Thrail CRM Team";
		$headers = ['Content-Type: text/plain; charset=UTF-8'];

		wp_mail( $args[ 'email' ], $subject, $message, $headers );
	}

}