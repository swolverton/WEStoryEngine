<?php

/**
Plugin Name: Calendarize It! for WordPress
Plugin URI: http://plugins.righthere.com/calendarize-it/
Description: Calendarize It! for WordPress is a powerful calendar and event plugin. 
Version: 2.1.6 rev38527
Author: Alberto Lau (RightHere LLC)
Author URI: http://plugins.righthere.com
 **/

define('RHC_VERSION','2.1.6'); 
define('RHC_PATH', plugin_dir_path(__FILE__) ); 
define("RHC_URL", plugin_dir_url(__FILE__) ); 
define("RHC_SLUG", plugin_basename( __FILE__ ) );
define("RHC_ADMIN_ROLE", 'administrator');

//this can only be modified when installing for the first time,//created taxonomies will be lost if changed after.
define("RHC_CALENDAR",	'calendar');
define("RHC_VENUE",		'venue');
define("RHC_ORGANIZER",	'organizer');
define("RHC_VISUAL_CALENDAR", 'calendar');
//custom post type, this afects slugs
define("RHC_EVENTS", 'events');
define("RHC_CAPABILITY_TYPE", 'event');

define('RHC_DEFAULT_DATE_FORMAT','D. F j, g:ia');

define('RHC_DISPLAY','rhcdisplay');

define('SHORTCODE_CALENDARIZE','calendarize');
define('SHORTCODE_CALENDARIZEIT','calendarizeit');

load_plugin_textdomain('rhc', null, dirname( plugin_basename( __FILE__ ) ).'/languages' );

if(!function_exists('property_exists')):
function property_exists($o,$p){
	return is_object($o) && 'NULL'!==gettype($o->$p);
}
endif;

if(!class_exists('plugin_righthere_calendar')){
	require_once RHC_PATH.'includes/class.plugin_righthere_calendar.php';
}

$settings = array(
	'options_capability'	=> 'rhc_options',
	'license_capability'	=> 'rhc_license'
);
//$settings['debug_menu']=true;//provides a debug menu with debugging information

global $rhc_plugin; 
$rhc_plugin = new plugin_righthere_calendar($settings);

//-------------------------------------------------------- 
register_activation_hook(__FILE__,'rhc_install');
function rhc_install() {
	include RHC_PATH.'includes/install.php';
	if(function_exists('handle_rhc_install'))handle_rhc_install();	
}
//---
register_deactivation_hook( __FILE__, 'rhc_uninstall' );
function rhc_uninstall(){
	include RHC_PATH.'includes/install.php';
	if(function_exists('handle_rhc_uninstall'))handle_rhc_uninstall();
}
//--------------------------------------------------------
?>