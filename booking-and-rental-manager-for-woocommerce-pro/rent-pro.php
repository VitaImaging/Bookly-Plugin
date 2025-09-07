<?php
/* 
* Plugin Name: Booking and Rental Manager Pro
* Version: 1.2.3
* Author: MagePeople Team
* Description: Additional features of Booking and Rental Manager plugin.
* Author URI: https://www.mage-people.com/
* Text Domain: rbfw-pro
* Domain Path: /languages/
*/


if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if (is_plugin_active( 'booking-and-rental-manager-for-woocommerce/rent-manager.php' )) {

	if ( ! defined( 'RBMW_PRO_PLUGIN_URL' ) ) {
		define( 'RBMW_PRO_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
	}
	if ( ! defined( 'RBMW_PRO_PLUGIN_DIR' ) ) {
		define( 'RBMW_PRO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	}
	if ( ! defined( 'RBMW_PLUGIN_DIR_PRO' ) ) {
		define( 'RBMW_PLUGIN_DIR_PRO', dirname( __FILE__ ) );
	}
	if ( ! defined( 'RBMW_PLUGIN_URL_PRO' ) ) {
		define( 'RBMW_PLUGIN_URL_PRO', plugins_url() . '/' . plugin_basename( dirname( __FILE__ ) ) );
	}
	if (!defined('MEP_STORE_URL')) { 
		define('MEP_STORE_URL', 'https://mage-people.com/');
	}	

	define('RBFW_PRO_ID', 113144);
	define('RBFW_PRO_NAME', 'Booking and Rental Manager for WooCommerce Pro');

	if (!class_exists('EDD_SL_Plugin_Updater')) {
		include(dirname(__FILE__) . '/license/EDD_SL_Plugin_Updater.php');
	}
  
	include(dirname(__FILE__) . '/license/main.php');


    $license_key      	= trim(get_option('rbfw_pro_license_key'));
    $edd_updater 		= new EDD_SL_Plugin_Updater(MEP_STORE_URL, __FILE__, array(
        'version'     		=> '1.2.3',
		'license'     		=> $license_key,
		'item_name'   		=> RBFW_PRO_NAME,
		'item_id'     		=> RBFW_PRO_ID,
		'author'      		=> 'MagePeople Team',
		'url'         		=> home_url(),
		'beta'        		=> false
	));

  	require_once(dirname(__FILE__) . "/inc/file_include.php");

}
else{
	function rbfw_pro_admin_notice_wc_not_active() {
		$class = 'notice notice-error';
		$message = __('Booking and Rental Manager Pro is requires Booking and Rental Manager free version to be installed and active.', 'rbfw-pro');
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
	}
	add_action('admin_notices', 'rbfw_pro_admin_notice_wc_not_active');
}

/**
 * Flush rewrite rules on plugin activation.
 */
function rbfw_pro_flush_rewrite_rules() {
    flush_rewrite_rules();
}

// flush rewrite rules on activation and deactivation
register_activation_hook( __FILE__, 'rbfw_pro_flush_rewrite_rules' );
register_deactivation_hook( __FILE__, 'rbfw_pro_flush_rewrite_rules' );