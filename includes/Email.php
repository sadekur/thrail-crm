<?php
namespace Thrail\Crm;

class Email {
    public function __construct() {
        add_action( 'thrail_send_followup_email', [ $this, 'send_followup_email' ], 10, 2 );
    }
    public function send_congratulatory_email( $name, $email ) {
        $defaults = [
            'congratulatory_subject' => 'Congratulations on subscribing!',
            'congratulatory_message' => 'Hi {name},\n\nThank you for subscribing to our newsletter!',
        ];
        $options = get_option('thrail_crm_email_settings', $defaults);

        $subject = $options[ 'congratulatory_subject' ];
        $message = str_replace( '{name}', $name, $options[ 'congratulatory_message' ] );
        $message = str_replace( '\n', "\n", $message );
        $headers = [ 'Content-Type: text/plain; charset=UTF-8' ];

        wp_mail( $email, $subject, $message, $headers );

        $time_sent = time();
        set_transient( 'congratulatory_email_sent_time_' . $email, $time_sent, HOUR_IN_SECONDS );
        if ( ! wp_next_scheduled( 'thrail_send_followup_email', [ $name, $email ] ) ) {
            wp_schedule_single_event( $time_sent + MINUTE_IN_SECONDS, 'thrail_send_followup_email', [ $name, $email ] );
        }
    }

    public function send_followup_email( $name, $email ) {

        $defaults = [
            'followup_subject' => 'Follow-up: We\'re glad to have you!',
            'followup_message' => 'Hi {name},\n\nIt\'s been a minute since you subscribed! We just wanted to follow up and say thanks again.'
        ];
        $options = get_option('thrail_crm_email_settings', $defaults);

        $subject = $options[ 'followup_subject' ];
        $message = str_replace( '{name}', $name, $options[ 'followup_message' ] );
        $message = str_replace( '\n', "\n", $message );
        $headers = [ 'Content-Type: text/plain; charset=UTF-8' ];

        wp_mail( $email, $subject, $message, $headers );
        wp_clear_scheduled_hook( 'thrail_send_followup_email', [ $name, $email ] );
        delete_transient( 'congratulatory_email_sent_time_' . $email );
    }
}