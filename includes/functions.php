<?php

/**
 * Insert a new address
 *
 * @param  array  $args
 *
 * @return int|WP_Error
 */

register_activation_hook(__FILE__, 'thrail_crm_activate');
function thrail_crm_activate() {
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    global $wpdb;
    $table_name = $wpdb->prefix . 'thrail_crm_leads';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        email text NOT NULL,
        time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    dbDelta($sql);
}
