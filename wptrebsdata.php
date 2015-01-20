<?php

/*
Plugin Name: WP Trebs Data
Plugin URI: http://www.moeloubani.com/wordpress-treb-3pv-plugin
Description: A WordPress plugin to use to import property data from Treb Data URL
Version: 1.0
Author: Moe Loubani
Author URI: http://www.moeloubani.com
License: GPL2
*/

// Get requires files

require_once(dirname(__FILE__) . '/inc/GetData.php');
require_once(dirname(__FILE__) . '/inc/ProcessData.php');
require_once(dirname(__FILE__) . '/inc/CheckValid.php');
require_once(dirname(__FILE__) . '/inc/Setup.php');
require_once(dirname(__FILE__) . '/inc/ManageImages.php');

// Set up post type

function setUpPostType() {
	$install = new \Loubani\WPTrebs3v\Install();
}

add_action('plugins_loaded', 'setUpPostType');

// Actual update call

function getPropertyData() {

	$username = 'yourusername';
	$password = 'yourpassword';

	$files = new \Loubani\WPTrebs3v\GetData($username, $password, 'http://3pv.torontomls.net/Data3PV/Download3PVAction.asp');
	$process = new \Loubani\WPTrebs3v\ProcessData($files);
	$check = new \Loubani\WPTrebs3v\CheckValid($files);
}

add_action('getPropertyDataHook', 'getPropertyData');

// Schedule update daily

function activate_3pv() {
	add_action('init', 'getPropertyData');
	wp_schedule_event( time(), 'daily', 'getPropertyDataHook' );
}

register_activation_hook( __FILE__, 'activate_3pv' );
