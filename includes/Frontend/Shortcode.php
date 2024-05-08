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
        add_shortcode( 'thrail-crm', [ $this, 'optin_form' ] );
    }

    /**
     * Shortcode handler class
     *
     * @param  array $atts
     * @param  string $content
     *
     * @return string
     */
    public function optin_form( $atts ) {
        // wp_enqueue_script( 'thrail-script' );
        // wp_enqueue_style( 'thrail-style' );

    $atts = shortcode_atts(
        array(
            'foo' => 'no foo',
            'bar' => 'default bar',
        ), $atts, 'bartag' );

    return 'bartag: ' . esc_html( $atts['foo'] ) . ' ' . esc_html( $atts['bar'] );
}
}
