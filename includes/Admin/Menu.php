<?php

namespace Thrail\Crm\Admin;
use Thrail\Crm\Admin\Leads_List_Table;
use Thrail\Crm\Admin\Email_Logs_List_Table;
require_once __DIR__ . '/../../classes/Trait.php';

use Thrail\Crm\Helper;

class Menu {
    use Helper;

	private $leads_list_table;

	function __construct() {
		add_action('admin_menu', [$this, 'admin_menu']);
		add_action('admin_init', [$this, 'handle_csv_export']);
	}

	public function admin_menu() {
		$hook = add_menu_page(
			'Thrail CRM',
			'Thrail CRM',
			'manage_options',
			'thrail-crm',
			[$this, 'crm_page'],
			'dashicons-businessman'
		);

		add_action("load-$hook", [$this, 'init_list_table']);

		$email_hook = add_submenu_page(
			'thrail-crm', 'Email Logs', 
			'Email Logs', 'manage_options', 
			'thrail-crm-email-logs', 
			[$this, 'email_logs_page']
		);

    	add_action("load-$email_hook", [$this, 'init_email_logs_table']);
	}

	public function init_list_table() {
		$this->leads_list_table = new Leads_List_Table();
		add_screen_option('per_page', [
			'default' => 10,
			'option' => 'leads_per_page'
		]);
	}

	public function crm_page() {
		echo '<div class="wrap"><h1 class="wp-heading-inline">Leads</h1>';
		$this->render_filters();
		$this->leads_list_table->prepare_items();
		$this->leads_list_table->display();
		echo '<div class="main">';
		echo '<div id="editLeadModal" title="Edit Lead" style="display:none;">';
		echo '<form id="editLeadForm">';
		echo '<label for="leadName">Name:</label>';
		echo '<input type="text" id="leadName" name="name" class="widefat"><br><br>';
		echo '<label for="leadEmail">Email:</label>';
		echo '<input type="email" id="leadEmail" name="email" class="widefat">';
		echo '<input type="hidden" id="leadId" name="id">';
		echo '</form>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}


	public function init_email_logs_table() {
		$this->email_logs_list_table = new Email_Logs_List_Table();
		add_screen_option('per_page', ['default' => 10, 'option' => 'email_logs_per_page']);
	}

	public function email_logs_page() {
	    echo '<div class="wrap"><h1>Email Logs</h1>';
	    $this->email_logs_list_table->prepare_items();
	    $this->email_logs_list_table->display();
	    echo '</div>';
	}
	public function handle_csv_export() {
	     if (isset($_POST['action']) && $_POST['action'] === 'export_csv' && check_admin_referer('export_csv', 'csv_nonce')) {
            $this->leads_list_table = new Leads_List_Table();
            $this->leads_list_table->prepare_items();
            $this->leads_list_table->export_to_csv();
            exit;
        }
	}
}