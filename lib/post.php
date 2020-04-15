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
            $taxonomies = wp_get_post_terms($post_id, get_taxonomies('','names'));

            return array(
                '_id' => $blogId . '-post-' . $post->ID,
                'blogId' => $blogId,
                'type' => 'post',
                'taxonomies' => $taxonomies,
                'data' => array(
                    'post' => $post,
                    'permalink' => $permalink,
                    'modules' => $modules
                )
            );
        }

        function checkWhenToSwapWithDefault($modules) {
            if(is_array($modules) === false || count($modules) === 0) return [];

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
