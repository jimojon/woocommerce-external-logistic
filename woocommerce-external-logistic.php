<?php
/**
 * Plugin Name: WooCommerce External Logistic
 * Description: Externalisation de l'affranchissement et de la logistique de votre boutique.
 * Author: Jonas
 * Author URI: https://positronic.fr
 * Version: 0.2.0
 * Text Domain: woocommerce_external_logistic
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! function_exists('is_woocommerce_active') ) {
    function is_woocommerce_active(){
        return in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));
    }
}

if ( ! is_woocommerce_active() ) {
	return;
}

/**
 * The WC_External_Logisitic global object
 *
 * @name $woocommerce_external_logistic
 * @global WC_External_Logisitic $GLOBALS['woocommerce_external_logistic']
 */
$GLOBALS['woocommerce_external_logistic'] = new WC_External_Logisitic();

define( 'WOOCOMMERCE_EXTERNAL_LOGISTIC_VERSION', '0.1.1' );

/**
 * Main Plugin Class
 *
 * @since 0.1
 */
class WC_External_Logisitic {

	/**
	 * Setup main plugin class
	 *
	 * @since  0.1.0
	 * @return \WC_External_Logisitic
	 */
	public function __construct() {

		// load classes that require WC to be loaded
		add_action( 'woocommerce_init', array( $this, 'init' ) );
	}

	/**
	 * Load actions and filters that require WC to be loaded
	 *
	 * @since 0.1.0
	 */
	public function init() {

        load_plugin_textdomain( 'woocommerce-external-logistic', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

        require( 'includes/class-WEL-order-meta.php' );
        require( 'includes/class-WEL-order-status.php' );
	}
}
