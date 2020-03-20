<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// check if class already exists
if( !class_exists('CouchDBSender') ) :

class CouchDBSender {
    var $staticVars;
	var $couchDbUrl;
	var $couchDbAuthKey;

    function __construct() {
        $this->staticVars = get_option( 'export_to_json_settings_option_name' );
        $this->couchDbUrl = $this->staticVars['couchdb_url'];
		$this->couchDbAuthKey = $this->staticVars['authorization_key'];
    }

    function sendPost($body) {
		$etag = $this->retrieveRevision($body);
		if($etag) {
			$body['_rev'] = $etag;
		}

		$response = wp_remote_post( $this->couchDbUrl, array(
			'headers' => array(
				'Content-Type' => 'application/json',
				'Authorization' => 'Basic ' . $this->couchDbAuthKey
			),
			'body' => json_encode($body))
		);

		if ( is_wp_error( $response ) ) {
			wp_die($response->get_error_message());
		}
    }
    
    private function retrieveRevision($body) {
		$id = $body['_id'];
		$response = wp_remote_post( $this->couchDbUrl, array(
			'headers' => array(
				'Content-Type' => 'application/json',
				'Authorization' => 'Basic ' . $this->couchDbAuthKey
			),
			'body' => json_encode($body))
		);
		if ( is_wp_error( $response ) ) {
			wp_die($response->get_error_message());
		} else {
			$etag = $response['headers']['etag'];
			if($etag) {
				return json_decode($etag);
			} else {
				return null;
			}
		}
	}
}

endif;