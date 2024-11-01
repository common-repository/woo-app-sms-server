<?php 
/*
*	No direct access
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class APPSMSWebHooks {
	
	//Client url
	
	protected $url;

	//Client WooCommerce API Consumer's

	protected $consumer_key;

	protected $consumer_secret;

	/*
	*	Class Constructor
	*/

	public function __construct($key,$secret) {

		//Setup consumer's

		$this->consumer_key = $key;
		$this->consumer_secret = $secret;

		//Client url setup

		$this->url = site_url();
	}


	/*
	*	Getting active webhooks
	*/

	public function get_webhooks() {
		if(!$this->consumer_key || !$this->consumer_secret)
			return false;
		$url = $this->url . '/wc-api/v3/webhooks';
		$url = str_replace('https://', 'https://'.$this->consumer_key.':'.$this->consumer_secret.'@', $url);
		$get = wp_remote_get($url);
		$body = wp_remote_retrieve_body($get);
		if($body) $body = json_decode($body,true);
		return $body['webhooks'];
	}
}