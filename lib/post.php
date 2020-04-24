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
            $metaData = $this->getMetaData($post_id);

            return array(
                '_id' => $blogId . '-post-' . $post->ID,
                'blogId' => $blogId,
                'type' => 'post',
                'taxonomies' => $taxonomies,
                'meta' => $metaData,
                'data' => array(
                    'post' => $post,
                    'permalink' => $permalink,
                    'modules' => $modules
                ),
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
            // Set the useDefault to true, otherwise the options overrides the useDefault
            $result['useDefault'] = true;
            
            return $result;
        }

        function getMetaData($post_id) {
            return array(
                'seoTitle' => get_post_meta($post_id, '_yoast_wpseo_title', true),
                'seoMetaDescription' => get_post_meta($post_id, '_yoast_wpseo_metadesc', true),
                'canonicalUrl' => get_post_meta($post_id, '_yoast_wpseo_canonical', true),
                'facebookTitle' => get_post_meta($post_id, '_yoast_wpseo_opengraph-title', true),
                'facebookDescription' => get_post_meta($post_id, '_yoast_wpseo_opengraph-description', true),
                'facebookImage' => get_post_meta($post_id, '_yoast_wpseo_opengraph-image', true),
                'twitterTitle' => get_post_meta($post_id, '_yoast_wpseo_twitter-title', true),
                'twitterDescription' => get_post_meta($post_id, '_yoast_wpseo_twitter-description', true),
                'twitterImage' => get_post_meta($post_id, '_yoast_wpseo_twitter-image', true),
                'metaRobotIndex' => get_post_meta($post_id, '_yoast_wpseo_meta-robots-noindex', true),
                'metaRobotFollow' => get_post_meta($post_id, '_yoast_wpseo_meta-robots-nofollow', true),
                'metaRobotAdvanced' => get_post_meta($post_id, '_yoast_wpseo_meta-robots-adv', true),
                'featuredImage' => get_the_post_thumbnail_url($post_id),
                'hreflang' => $this->getHrefLang($post_id),
            );
        }

        function getHrefLang($post_id) {
            $postMeta = get_post_meta($post_id);
            $result = [];


            foreach($postMeta as $metaKey => $metaValue) {
                if(strpos($metaKey, 'hreflang') !== false) {
                    array_push($result, array(
                        'lang' => $metaKey,
                        'value' => $metaValue[0]
                        )
                    );
                }
            }

            return $result;
        }
    }
endif;
