<?php
namespace Thrail\Crm;

class RestAPI {
    // use Helper;

    public function __construct() {
        require_once __DIR__ . '/../classes/Trait.php';
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
        add_action( 'thrail_send_followup_email', [ $this, 'send_followup_email' ], 10, 2 ); // Hook to send follow-up
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

    public function handle_form_submission( $request ) {
        $name  = $request->get_param( 'name' );
        $email = $request->get_param( 'email' );

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
            $this->send_congratulatory_email( $name, $email );  // Send the initial email
            return new \WP_REST_Response( [ 'message' => 'Thank you for subscribing!' ], 200 );
        } else {
            return new \WP_Error( 'db_error', 'Failed to register. Please try again.', [ 'status' => 500 ] );
        }
    }
    public function send_congratulatory_email( $name, $email ) {
        $subject = "Congratulations on subscribing!";
        $message = "Hi {$name},\n\nThank you for subscribing to our newsletter! We look forward to bringing you the latest updates and information.\n\nBest regards,\nThe Thrail CRM Team";
        $headers = [ 'Content-Type: text/plain; charset=UTF-8' ];

        wp_mail( $email, $subject, $message, $headers );

        if ( ! wp_next_scheduled( 'thrail_send_followup_email', [ $name, $email ] ) ) {
            wp_schedule_single_event( time() + HOUR_IN_SECONDS, 'thrail_send_followup_email', [ $name, $email ] );
        }
    }

    // This function handles the follow-up email after 1 hour
    public function send_followup_email( $name, $email ) {
        $subject = "Follow-up: We're glad to have you!";
        $message = "Hi {$name},\n\nIt's been an hour since you subscribed! We just wanted to follow up and say thanks again. We're excited to have you with us.\n\nBest regards,\nThe Thrail CRM Team";
        $headers = [ 'Content-Type: text/plain; charset=UTF-8' ];

        wp_mail( $email, $subject, $message, $headers );
    }
}
