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

	public function send_congratulatory_email( $name, $email ) {
	    $subject = "Congratulations on subscribing!";
	    $message = "Hi {$name},\n\nThank you for subscribing to our newsletter! We look forward to bringing you the latest updates and information.\n\nBest regards,\nThe Thrail CRM Team";
	    $headers = [ 'Content-Type: text/plain; charset=UTF-8' ];

	    wp_mail( $email, $subject, $message, $headers );
	}
	
}