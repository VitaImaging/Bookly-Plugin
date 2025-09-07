<?php
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

// Enqueue Scripts for stripe
add_action( 'wp_enqueue_scripts', 'rbfw_pro_stripe_scripts' );
function rbfw_pro_stripe_scripts() {

	//wp_enqueue_style('rbfw-stripe', RBMW_PRO_PLUGIN_URL . 'inc/gateway/stripe.css', array() );
	//wp_enqueue_script('stripe', 'https://js.stripe.com/v3/', array( 'jquery' ), time(), false );
}