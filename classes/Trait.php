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
	
}