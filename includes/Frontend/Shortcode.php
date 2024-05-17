<?php

namespace Thrail\Crm\Frontend;

class Shortcode {

	function __construct() {
		add_shortcode( 'thrail-crm', [ $this, 'optin_form' ] );
		add_action( 'wp_footer', [ $this, 'optin_footer' ] );
	}

	public function optin_form() {
		// wp_enqueue_script( 'thrail-script' );
		// wp_enqueue_style( 'thrail-style' );
		$form_html = '<form id="thrailOptinForm" action="" method="post">
		<label for="name">Name:</label><br>
		<input type="text" id="name" name="name" placeholder="Enter your name"><br>
		<label for="email">Email:</label><br>
		<input type="email" id="email" name="email" placeholder="Enter your email"><br>
		<input type="submit" class= value="Subscribe">
		</form>';
		return $form_html;
	}

	public function optin_footer() {
		if (!is_admin()) {
			echo '<div class="loader-container" id="formLoader" style="display: none;">' .
				 '<img src="' . THRAIL_CRM_ASSETS . 'img/loader.gif" alt="Loading...">' .
				 '</div>';
		}
	}
}