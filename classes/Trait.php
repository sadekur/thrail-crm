<?php

namespace Thrail\Crm;

Trait Helper {

	 public function render_filters() {
		echo '<div class="thrail-filter-forms">';
        // Search Form
        echo '<form method="get">
                <input type="hidden" name="page" value="thrail-crm" />
                <input type="text" name="s" placeholder="Search..." value="' . esc_attr(sanitize_text_field($_GET['s'] ?? '')) . '" />
                <input type="submit" value="Filter" class="button" />
              </form>';

        // Export CSV Form
        echo '<form method="post">
                <input type="hidden" name="action" value="export_csv">
                ' . wp_nonce_field('export_csv', 'csv_nonce', true, false) . '
                <input type="submit" value="Export to CSV" class="button button-primary">
              </form>';
        echo '</div>';
	}	
}