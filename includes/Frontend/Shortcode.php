<?php

namespace Thrail\Crm\Frontend;

class Shortcode {

    function __construct() {
        add_shortcode('thrail-crm', [$this, 'optin_form']);
    }

    public function optin_form($atts) {
        wp_enqueue_script( 'thrail-script' );
        wp_enqueue_style( 'thrail-style' );
        $atts = shortcode_atts(
            array(
                'name' => 'Shadekur',
                'email' => 'shadekur.rahman60@gmail.com',
            ), $atts, 'thrail-crm'
        );

        ob_start();
        ?>
        <form id="thrailOptinForm" method="post">
            <input type="text" name="name" placeholder="Enter Name" value="" />
            <input type="email" name="email" placeholder="Enter Email" value="" />
            <input type="submit" value="Subscribe" />
        </form>
        <?php
        return ob_get_clean();
    }
}
