<?php

namespace Thrail\Crm;

class Ajax {

    function __construct() {
        // Ensure both logged in and not logged in users can submit the form
        add_action('wp_ajax_thrail_form', [$this, 'submit_enquiry']);
        add_action('wp_ajax_nopriv_thrail_form', [$this, 'submit_enquiry']);
    }

    public function submit_enquiry() {
        check_ajax_referer('thrail-crm', 'nonce'); // Verify the nonce. This will die if the nonce is incorrect.

        // Assuming name and email fields are being submitted
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);

        // Optional: Add extra validation here
        if (empty($name) || empty($email)) {
            wp_send_json_error(['message' => __('Missing required fields', 'thrail-crm')]);
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'thrail_crm_entries';

        $data = [
            'name' => $name,
            'email' => $email,
            'date_submitted' => current_time('mysql', 1)
        ];
        $format = ['%s', '%s', '%s']; // Formats of the data being inserted

        // Insert the data into the database
        $inserted = $wpdb->insert($table_name, $data, $format);

        if ($inserted) {
            wp_send_json_success(['message' => __('Enquiry submitted successfully!', 'thrail-crm')]);
        } else {
            wp_send_json_error(['message' => __('Failed to submit enquiry', 'thrail-crm')]);
        }
    }
}
register_activation_hook(__FILE__, 'create_thrail_crm_table');

function create_thrail_crm_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'thrail_crm_entries';

    $sql = "CREATE TABLE `$table_name` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL,
        `date_submitted` datetime NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
