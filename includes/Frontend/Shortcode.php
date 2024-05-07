<?php

namespace Thrail\Crm\Frontend;

/**
 * Shortcode handler class
 */
class Shortcode {

    /**
     * Initializes the class
     */
    function __construct() {
        add_shortcode( 'thrail-crm', [ $this, 'render_shortcode' ] );
    }

    /**
     * Shortcode handler class
     *
     * @param  array $atts
     * @param  string $content
     *
     * @return string
     */
    public function render_shortcode( $atts, $content = '' ) {
        wp_enqueue_script( 'thrail-script' );
        wp_enqueue_style( 'thrail-style' );

        return '<div class="academy-shortcode">Hello from Shortcode</div>';
    }
}
