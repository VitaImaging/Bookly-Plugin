<?php
/* 
* Plugin Name: Booking and Rental Manager Addon: Min and Max Booking Day
* Version: 1.0.2
* Author: MagePeople Team
* Description: Extends the Minimum and Maximum Booking Day feature.
* Author URI: https://www.mage-people.com/
* Text Domain: rbfw-mmbd
* Domain Path: /languages/
*/


if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( ! defined( 'RBFW_MMBD_PLUGIN_URL' ) ) {
define( 'RBFW_MMBD_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
}
if ( ! defined( 'RBFW_MMBD_PLUGIN_DIR' ) ) {
define( 'RBFW_MMBD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if (!defined('MEP_STORE_URL')) { 
	define('MEP_STORE_URL', 'https://mage-people.com/');
}	

define('RBFW_PRO_MMBD_ID', 121432);
define('RBFW_PRO_MMBD_NAME', 'Booking and Rental Manager Addon: Min and Max Booking Day');

if (!class_exists('EDD_SL_Plugin_Updater')) {
	include(dirname(__FILE__) . '/license/EDD_SL_Plugin_Updater.php');
}

include(dirname(__FILE__) . '/license/main.php');

$license_key      = trim(get_option('rbfw_pro_mmbd_license_key'));
$edd_updater 		= new EDD_SL_Plugin_Updater(MEP_STORE_URL, __FILE__, array(
	'version'     		=> '1.0.2',
	'license'     		=> $license_key,
	'item_name'   		=> RBFW_PRO_MMBD_NAME,
	'item_id'     		=> RBFW_PRO_MMBD_ID,
	'author'      		=> 'MagePeople Team',
	'url'         		=> home_url(),
	'beta'        		=> false
));


require_once(RBFW_MMBD_PLUGIN_DIR . "inc/file_include.php");
