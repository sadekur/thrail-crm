<?php
namespace Thrail\Crm;
class RestAPI {
    private $email;

    public function __construct() {
        $this->email = new Email();
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
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
		register_rest_route( 'thrail-crm/v1', '/update-email-settings/', [
			'methods' => 'POST',
			'callback' => [ $this, 'handle_email_settings_update' ],
			'permission_callback' => '__return_true',
		]);
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
            // Call the Email class to send the congratulatory email
            $this->email->send_congratulatory_email( $name, $email );
            return new \WP_REST_Response( [ 'message' => 'Thank you for subscribing!' ], 200 );
        } else {
            return new \WP_Error( 'db_error', 'Failed to register. Please t	ry again.', [ 'status' => 500 ] );
        }
    }

	public function handle_email_settings_update( $request ) {
		// check_ajax_referer( 'nonce', 'nonce' );
		// $response = [
		// 	 'status'	=> 0,
		// 	 'message'	=>__( 'Unauthorized!', 'thrail-crm' )
		// ];
		// if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'nonce' ) ) {
        //     wp_send_json_error( [ 'message' => __( 'Invalid nonce', 'thrail-crm' ) ] );
        //     return;
        // }
		$options = [
			'congratulatory_subject' 	=> sanitize_text_field( $request->get_param( 'congratulatory_subject' ) ),
			'congratulatory_message' 	=> sanitize_textarea_field( $request->get_param( 'congratulatory_message' )) ,
			'followup_subject' 			=> sanitize_text_field( $request->get_param( 'followup_subject' ) ),
			'followup_message' 			=> sanitize_textarea_field( $request->get_param( 'followup_message' ) )
		];
		update_option( 'thrail_crm_email_settings', $options );
		return new \WP_REST_Response( 'Settings updated successfully', 200 );
	}
}