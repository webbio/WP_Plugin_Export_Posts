<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('Post') ) :
    class Post {

        function __construct() {

        }

        function getPost($post_id) {
            $blogId = get_current_blog_id();
            $post = get_post($post_id);
            $postMeta = get_fields($post_id);
            $permalink = get_permalink($post_id);
            $modules = $this->checkWhenToSwapWithDefault($postMeta['modules']);

            return array(
                '_id' => $blogId . '-' . $post->ID,
                'blogId' => $blogId,
                'post' => $post,
                'permalink' => $permalink,
                'modules' => $modules
            );
        }

        function checkWhenToSwapWithDefault($modules) {
            foreach($modules as $key => $module) {
                if($module['useDefault'] == true) {
                    $modules[$key] = $this->swapDataWithDefaultValues($module);
                }
            }
    
            return $modules;
        }

        function swapDataWithDefaultValues($module) {
            $defaults = get_fields('options')['modules'];
            $result = null;
    
            foreach($defaults as $default) {
                if($default['acf_fc_layout'] == $module['acf_fc_layout']) {
                    $result = $default;
                }
            }
    
            return $result;
        }
    }
endif;
