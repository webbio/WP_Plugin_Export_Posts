<?php

include('render-settings-page.php');
include('lib/couchdb-sender.php');
include('lib/post.php');
include('lib/menu.php');
/*
Plugin Name: Export to JSON on Save
Description: When a post is saved we generate a json export for this post
Version: 1.0.0
Author: Webbio
Version: 1.0
Author URI: #
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('ExportToJsonOnSave') ) :

class ExportToJsonOnSave {
	
	// vars
	var $settings;
	var $CouchDBSender;
	var $Post;
	var $Menu;
	
	/*
	*  __construct
	*
	*  This function will setup the class functionality
	*
	*  @type	function
	*  @date	07/01/2020
	*  @since	1.0.0
	*
	*  @param	void
	*  @return	void
	*/
	
	function __construct() {
		// settings
		// - these will be passed into the field class.
		$this->settings = array(
			'version'	=> '1.0.0',
			'url'		=> plugin_dir_url( __FILE__ ),
			'path'		=> plugin_dir_path( __FILE__ )
		);

		add_action('plugins_loaded', array($this, 'class_init'));
		add_action('acf/save_post', array($this, 'onPostSave'), 100, 3);
		add_filter('plugin_action_links_export-to-json-on-save/export-to-json-on-save.php', array($this, 'nc_settings_link'));
	}


	function class_init() {
		if(class_exists('CouchDBSender')) {
			$this->CouchDBSender = new CouchDBSender();
		}
		if(class_exists('Post')) {
			$this->Post = new Post();
		}
		if(class_exists('Menu')) {
			$this->Menu = new Menu();
		}
	}

	function nc_settings_link( $links ) {
		// Build and escape the URL.
		$url = esc_url( add_query_arg(
			'page',
			'export-to-json-settings',
			get_admin_url() . 'admin.php'
		) );
		// Create the link.
		$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
		// Adds the link to the end of the array.
		array_push(
			$links,
			$settings_link
		);
		return $links;
	}

	function onPostSave($post_id) {
		$post = $this->Post->getPost($post_id);
		$this->CouchDBSender->sendItem($post);
	}
}

// initialize
new ExportToJsonOnSave();

// class_exists check
endif;