<?php

namespace Thrail\Crm;

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
        // new Frontend\Enquiry();
    }
}
