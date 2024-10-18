<?php

namespace Thrail\Crm\Admin;
use Thrail\Crm\Admin\Leads_List_Table;
use Thrail\Crm\Admin\Email_Logs_List_Table;
require_once __DIR__ . '/../../classes/Trait.php';

use Thrail\Crm\Helper;

class Menu {
    use Helper;

	private $leads_list_table;
	private $email_logs_list_table;

	function __construct() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_init', [ $this, 'handle_csv_export' ] );
	}

	public function admin_menu() {
		$hook = add_menu_page(
			'Thrail CRM',
			'Thrail CRM',
			'manage_options',
			'thrail-crm',
			[ $this, 'crm_page' ],
			'dashicons-businessman'
		);

		add_action( "load-$hook", [ $this, 'init_list_table' ] );

		$email_hook = add_submenu_page(
			'thrail-crm', 'Email Logs', 
			'Email Logs', 'manage_options', 
			'thrail-crm-email-logs', 
			[ $this, 'email_logs_page' ]
		);
		$send_email_hook = add_submenu_page(
			'thrail-crm', 'Send Emails', 
			'Send Emails', 'manage_options', 
			'thrail-crm-send-emails', 
			[ $this, 'send_emails_page' ]
		);

    	add_action( "load-$email_hook", [ $this, 'init_email_logs_table' ] );
	}

	public function init_list_table() {
		$this->leads_list_table = new Leads_List_Table();
		add_screen_option( 'per_page', [
			'default' 	=> 10,
			'option' 	=> 'leads_per_page'
		] );
	}

	public function crm_page() {
		echo '<div class="thrail-wrap"><h1 class="wp-heading-inline">Leads</h1>';
		$this->render_filters();
		$this->leads_list_table->prepare_items();
		$this->leads_list_table->display();
		echo '<div class="main">';
		echo '<div id="edit_lead" title="Edit Lead" style="display:none;">';
		echo '<form id="edit_lead_form">';
		echo '<label for="lead_name">Name:</label>';
		echo '<input type="text" id="lead_name" name="name"><br><br>';
		echo '<label for="lead_email">Email:</label>';
		echo '<input type="email" id="lead_email" name="email">';
		echo '<input type="hidden" id="lead_id" name="id">';
		echo '</form>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}


	public function init_email_logs_table() {
		$this->email_logs_list_table = new Email_Logs_List_Table();
		add_screen_option( 'per_page', [ 'default' => 10, 'option' => 'email_logs_per_page' ] );
	}

	public function email_logs_page() {
	    echo '<div class="thrail-wrap thrail-log-list"><h1>Email Logs</h1>';
	    $this->email_logs_list_table->prepare_items();
	    $this->email_logs_list_table->display();
	    echo '</div>';
	}

	public function send_emails_page() {
		$defaults = [
			'congratulatory_subject' => 'Congratulations on subscribing!',
			'congratulatory_message' => "Hi {name},\n\nThank you for subscribing to our newsletter!",
			'followup_subject' => 'Follow-up: We\'re glad to have you!',
			'followup_message' => "Hi {name},\n\nIt's been a minute since you subscribed! We just wanted to follow up and say thanks again."
		];
		$options = get_option('thrail_crm_email_settings', $defaults);
	
		echo '<div class="thrail-wrap thrail-form">
            <h1>Email Settings</h1>
            <form id="thrail-crm-email-settings-form" method="post" action="javascript:void(0);">
                <h2>Congratulatory Email</h2>
                <label for="congratulatory_subject">Subject:</label>
                <input type="text" id="congratulatory_subject" name="congratulatory_subject" value="' . esc_attr($options['congratulatory_subject']) . '">
                <br><br>
                <label for="congratulatory_message">Message:</label>
                <textarea id="congratulatory_message" rows="5" name="congratulatory_message">' . esc_textarea($options['congratulatory_message']) . '</textarea>
                
                <h2>Follow-up Email</h2>
                <label for="followup_subject">Subject:</label>
                <input type="text" id="followup_subject" name="followup_subject" value="' . esc_attr($options['followup_subject']) . '">
                <br><br>
                <label for="followup_message">Message:</label>
                <textarea id="followup_message" rows="5" name="followup_message">' . esc_textarea($options['followup_message']) . '</textarea>
                <br><br>
                <input type="submit" value="Save Settings" class="button-primary">
            </form>
          </div>';
	}
	public function handle_csv_export() {
	     if ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] === 'export_csv' && check_admin_referer( 'export_csv', 'csv_nonce' ) ) {
            $this->leads_list_table = new Leads_List_Table();
            $this->leads_list_table->prepare_items();
            $this->leads_list_table->export_to_csv();
            exit;
        }
	}
}