<?php

namespace Thrail\Crm\Frontend;

class Shortcode {

	function __construct() {
		add_shortcode('thrail-crm', [$this, 'optin_form']);
	}

	public function optin_form($atts) {
		wp_enqueue_script( 'thrail-script' );
		wp_enqueue_style( 'thrail-style' );
		$form_html = '<form id="thrailOptinForm" action="" method="post">
		<label for="name">Name:</label><br>
		<input type="text" id="name" name="name" placeholder="Enter your name"><br>
		<label for="email">Email:</label><br>
		<input type="email" id="email" name="email" placeholder="Enter your email"><br>
		<input type="submit" value="Subscribe">
		</form>';

		return $form_html;
	}
}
