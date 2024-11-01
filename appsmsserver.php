<?php
/**
 * @package APPSMSServer
 */
/*
Plugin Name: Woocommerce APP SMS Server
Plugin URI: https://appsmsserver.com/
Description: SMS Server for your android device
Version: 1.0.1
Author: SimpApp
Author URI: https://simpapp.ro/
License: GPLv2 or later
Text Domain: appsms
*/
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define WC_PLUGIN_FILE.
if ( ! defined( 'APPSMS_PLUGIN_FILE' ) ) {
	define( 'APPSMS_PLUGIN_FILE', __FILE__ );
}

// Include the main WooCommerce class.
if ( ! class_exists( 'APPSMSServer' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-appsmsserver.php';
}
include_once dirname(__FILE__) . '/includes/config.php';
$appsms = new APPSMSServer($cfg); 
$appsms->init();