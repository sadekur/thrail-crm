<?php

namespace Thrail\Crm;
use Thrail\Crm\Helper;

/**
 * Frontend handler class
 */
class Frontend {

    /**
     * Initialize the class
     */
    function __construct() {
        // var_dump(THRAIL_CRM_ASSETS);
        new Frontend\Shortcode();
            global $wpdb;
            // Helper::pri($wpdb);
           print_r($wpdb);
    }
}
