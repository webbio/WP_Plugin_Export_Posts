<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// check if class already exists
if( !class_exists('General') ) :
    class General {
        var $staticVars;

        function __construct() {
            $this->staticVars = get_option( 'export_to_json_settings_option_name' );
        }

        function wpse_pre_option_home() {
            if( isset($this->staticVars['frontend_url']) AND !empty($this->staticVars['frontend_url']) ){
                $str = $this->staticVars['frontend_url'];
                $str = preg_replace('#^https?://#', '', $str); // Clean-up: Remove protocol from URL to make sure protocol is always https in the WP admin
                $str = rtrim($str, '/'); // Clean-up: Remove trailing slash at the end of the URL to make sure base URL is always without trailing slash in de WP admin
                return 'https://'.$str;
            } else {
                return WP_HOME; // Return the wp_home url from the DB when no URL is specified by the admin
            }
            
        }
    }
endif;