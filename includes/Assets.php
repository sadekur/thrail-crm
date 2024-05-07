<?php

namespace Thrail\Crm;

/**
 * Assets handlers class
 */
class Assets {

    /**
     * Class constructor
     */
    function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );
    }

    /**
     * All available scripts
     *
     * @return array
     */
    public function get_scripts() {
        return [
            'thrail-script' => [
                'src'     => THRAIL_CRM_ASSETS . '/js/frontend.js',
                'version' => filemtime( THRAIL_CRM_PATH . '/assets/js/frontend.js' ),
                'deps'    => [ 'jquery' ]
            ],
            'thrail-enquiry-script' => [
                'src'     => THRAIL_CRM_ASSETS . '/js/enquiry.js',
                'version' => filemtime( THRAIL_CRM_PATH . '/assets/js/enquiry.js' ),
                'deps'    => [ 'jquery' ]
            ],
            'thrail-admin-script' => [
                'src'     => THRAIL_CRM_ASSETS . '/js/admin.js',
                'version' => filemtime( THRAIL_CRM_PATH . '/assets/js/admin.js' ),
                'deps'    => [ 'jquery', 'wp-util' ]
            ],
        ];
    }

    /**
     * All available styles
     *
     * @return array
     */
    public function get_styles() {
        return [
            'thrail-style' => [
                'src'     => THRAIL_CRM_ASSETS . '/css/frontend.css',
                'version' => filemtime( THRAIL_CRM_PATH . '/assets/css/frontend.css' )
            ],
            'thrail-admin-style' => [
                'src'     => THRAIL_CRM_ASSETS . '/css/admin.css',
                'version' => filemtime( THRAIL_CRM_PATH . '/assets/css/admin.css' )
            ],
            'thrail-enquiry-style' => [
                'src'     => THRAIL_CRM_ASSETS . '/css/enquiry.css',
                'version' => filemtime( THRAIL_CRM_PATH . '/assets/css/enquiry.css' )
            ],
        ];
    }

    /**
     * Register scripts and styles
     *
     * @return void
     */
    public function register_assets() {
        $scripts = $this->get_scripts();
        $styles  = $this->get_styles();

        foreach ( $scripts as $handle => $script ) {
            $deps = isset( $script['deps'] ) ? $script['deps'] : false;

            wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
        }

        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;

            wp_register_style( $handle, $style['src'], $deps, $style['version'] );
        }

        // wp_localize_script( 'thrail-enquiry-script', 'THRAIL', [
        //     'ajaxurl' => admin_url( 'admin-ajax.php' ),
        //     'error'   => __( 'Something went wrong', 'thrail-crm' ),
        // ] );

        wp_localize_script( 'thrail-admin-script', 'THRAIL', [
            'nonce' => wp_create_nonce( 'thrail-admin-nonce' ),
            'confirm' => __( 'Are you sure?', 'thrail-crm' ),
            'error' => __( 'Something went wrong', 'thrail-crm' ),
        ] );
    }
}
