<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('Redirects') ) :
    class Redirects {

        function __construct() {

        }

        function createRedirect($post_id, $blogId) {
            if($post_id == 'options'){ 
                return array(
                    '_id' => 'wordpress:' . $blogId . '-redirects',
                    'blogId' => $blogId,
                    'type' => 'redirect',
                    'data'=> get_field('redirects', 'option')
                );
            } else {
                  return false;
            }
           
        }
    }
endif;
