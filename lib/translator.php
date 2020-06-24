<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('Translator') ) :
    class Translator {

        function __construct() {

        }

        function createTranslation($post_id, $blogId) {
            if($post_id == 'options'){ 
                  return array(
                    '_id' => 'wordpress:' . $blogId . '-translation',
                    'blogId' => $blogId,
                    'type' => 'translation',
                    'data'=> get_field('stringTranslations', 'option')
                );
            } else {
                  return false;
            }
           
        }
    }
endif;
