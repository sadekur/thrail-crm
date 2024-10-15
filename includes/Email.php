<?php
namespace Thrail\Crm;

class Email {
    public function __construct() {
        add_action( 'thrail_send_followup_email', [ $this, 'send_followup_email' ], 10, 2 );
    }
    public function send_congratulatory_email( $name, $email ) {
        $subject = "Congratulations on subscribing!";
        $message = "Hi {$name},\n\nThank you for subscribing to our newsletter!";
        $headers = [ 'Content-Type: text/plain; charset=UTF-8' ];
        wp_mail( $email, $subject, $message, $headers );
        $time_sent = time();
        set_transient( 'congratulatory_email_sent_time_' . $email, $time_sent, HOUR_IN_SECONDS );
        if ( ! wp_next_scheduled( 'thrail_send_followup_email', [ $name, $email ] ) ) {
            wp_schedule_single_event( $time_sent + HOUR_IN_SECONDS, 'thrail_send_followup_email', [ $name, $email ] );
        }
    }

    public function send_followup_email( $name, $email ) {
        $subject = "Follow-up: We're glad to have you!";
        $message = "Hi {$name},\n\nIt's been a MINUTE since you subscribed! We just wanted to follow up and say thanks again.";
        $headers = [ 'Content-Type: text/plain; charset=UTF-8' ];
        wp_mail( $email, $subject, $message, $headers );
        wp_clear_scheduled_hook( 'thrail_send_followup_email', [ $name, $email ] );
        delete_transient( 'congratulatory_email_sent_time_' . $email );
    }
}
