<?php

namespace Thrail\Crm;

Trait Helper {

	 public function render_filters() {
        ?>
        <form method="get">
            <input type="hidden" name="page" value="thrail-crm" />
            <input type="text" name="s" value="<?php echo isset($_GET['s']) ? esc_attr($_GET['s']) : ''; ?>" />
            <input type="submit" value="Filter" class="button" />
        </form>
        <form method="post">
            <input type="hidden" name="action" value="export_csv">
            <?php wp_nonce_field('export_csv', 'csv_nonce'); ?>
            <input type="submit" value="Export to CSV" class="button button-primary">
        </form>
        <?php
    }

    public function fetch_data() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'thrail_crm_leads';
        $sql = "SELECT * FROM {$table_name} ORDER BY time DESC";
        $results = $wpdb->get_results($sql, ARRAY_A);

        if ($results === false) {
            echo "<p>Error retrieving data from database: " . $wpdb->last_error . "</p>";
            return [];
        }

        return $results;
    }

    public function column_default($item, $column_name) {
        return isset($item[$column_name]) ? esc_html($item[$column_name]) : 'No data';
    }

    public function column_time($item) {
        return sprintf('<strong>%s</strong>', esc_html($item['time']));
    }

    public function column_cb($item) {
        return sprintf('<input type="checkbox" name="id[]" value="%d" />', esc_attr($item['id']));
    }
	
}