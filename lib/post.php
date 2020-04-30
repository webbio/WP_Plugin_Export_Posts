<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('Post') ) :
    class Post {

        function __construct() {

        }

        function getPost($post_id) {
            print_r($_POST);

            die();


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
                'seoTitle' => $this->getMostRecentPostMeta($post_id, 'yoast_wpseo_title'),
                'seoMetaDescription' => $this->getMostRecentPostMeta($post_id, 'yoast_wpseo_metadesc'),
                'breadcrumbTitle' => $this->getMostRecentPostMeta($post_id, 'yoast_wpseo_bctitle'),
                'canonicalUrl' => $this->getMostRecentPostMeta($post_id, 'yoast_wpseo_canonical'),
                'facebookTitle' => $this->getMostRecentPostMeta($post_id, 'yoast_wpseo_opengraph-title'),
                'facebookDescription' => $this->getMostRecentPostMeta($post_id, 'yoast_wpseo_opengraph-description'),
                'facebookImage' => $this->getMostRecentPostMeta($post_id, 'yoast_wpseo_opengraph-image'),
                'twitterTitle' => $this->getMostRecentPostMeta($post_id, 'yoast_wpseo_twitter-title'),
                'twitterDescription' => $this->getMostRecentPostMeta($post_id, 'yoast_wpseo_twitter-description'),
                'twitterImage' => $this->getMostRecentPostMeta($post_id, 'yoast_wpseo_twitter-image'),
                'metaRobotIndex' => $this->getMostRecentPostMeta($post_id, 'yoast_wpseo_meta-robots-noindex'),
                'metaRobotFollow' => $this->getMostRecentPostMeta($post_id, 'yoast_wpseo_meta-robots-nofollow'),
                'metaRobotAdvanced' => $this->getMostRecentPostMeta($post_id, 'yoast_wpseo_meta-robots-adv'),
                'featuredImage' => get_the_post_thumbnail_url($post_id),
                'hreflang' => $this->getHrefLang($post_id),
            );
        }

        function getMostRecentPostMeta($post_id, $metaKey) {
            if (empty($_POST[$metaKey])) {
                return get_post_meta($post_id, '_' . $metaKey, true);
            }
            return $_POST[$metaKey];
        }

        function getHrefLang($post_id) {
            $href_web = $_POST['hreflang-href'];
            $href_lang = $_POST['hreflang-lang'];
            $result = [];

            foreach($href_web as $key => $href) {
                array_push($result, array(
                    'lang' => $href_lang[$key],
                    'value' => $href
                    )
                );
            }


            if(empty($href_web) && empty($href_lang)) {
                $postMeta = get_post_meta($post_id);

                foreach($postMeta as $metaKey => $metaValue) {
                    if(strpos($metaKey, 'hreflang') !== false) {
                        array_push($result, array(
                            'lang' => str_replace('hreflang-', '', $metaKey),
                            'value' => $metaValue[0]
                            )
                        );
                    }
                }
            }

            return $result;
        }
    }
endif;
