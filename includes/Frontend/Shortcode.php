<?php

namespace Thrail\Crm\Frontend;

class Shortcode {

	function __construct() {
		add_shortcode( 'thrail-crm', [ $this, 'optin_form' ] );
		add_action( 'wp_footer', [ $this, 'optin_footer' ] );
	}

	public function optin_form() {
		$form_html = '<div class="form-container">
		<form id="thrailOptinForm" action="" method="post">
			<label for="name">Name:</label>
			<input type="text" id="name" name="name" required placeholder="Enter your name">
			<label for="email">Email:</label>
			<input type="email" id="email" name="email" required placeholder="Enter your email">
			<input type="submit" value="Subscribe">
		</form>
		</div>';
		return $form_html;
	}


	public function optin_footer() {
		if (!is_admin()) {
			echo '<div class="loader-container" id="formLoader" style="display: none;">' .
			'<img src="' . esc_url( THRAIL_CRM_ASSETS . '/img/loader.gif' ) . '" alt="' . esc_attr( 'Loading...' ) . '">' .
			'</div>';
		}
	}	
}