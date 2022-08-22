<?php
/**
 * Plugin Name: WooCommerce Cart All in One Premium
 * Plugin URI:  https://villatheme.com/extensions/woocommerce-cart-all-in-one/
 * Description: Cart All In One For WooCommerce helps your customers view cart effortlessly.
 * Version: 1.0.6
 * Author: VillaTheme
 * Author URI: https://villatheme.com
 * Text Domain: woocommerce-cart-all-in-one
 * Domain Path: /languages
 * Copyright 2021-2022 VillaTheme.com. All rights reserved.
 * Requires PHP: 7.0
 * Requires at least: 5.0
 * Tested up to: 5.9
 * WC requires at least: 5.0
 * WC tested up to: 6.3
 **/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'VIWCAIO_CART_ALL_IN_ONE_VERSION', '1.0.6' );
$viwcaio_errors = array();
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( ! version_compare( phpversion(), '7.0', '>=' ) ) {
	$viwcaio_errors[] = esc_html__( 'Please update PHP version at least 7.0 to use WooCommerce Cart All in One.', 'woocommerce-cart-all-in-one' );
}
if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	$viwcaio_errors[] = esc_html__( 'Please install and activate WooCommerce to use WooCommerce Cart All in One.', 'woocommerce-cart-all-in-one' );
}
if ( empty( $viwcaio_errors ) ) {
	$init_file = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woocommerce-cart-all-in-one" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "define.php";
	require_once $init_file;
}

/**
 * Class VIWCAIO_CART_ALL_IN_ONE
 */
class VIWCAIO_CART_ALL_IN_ONE {
	protected $errors;

	public function __construct( $errors = array() ) {
		$this->errors = $errors;
		if ( ! empty( $errors ) ) {
			add_action( 'admin_notices', array( $this, 'global_note' ) );
			return;
		}
	}

	/**
	 * Notify if found error
	 */
	function global_note() {
		if ( count( $this->errors ) ) {
			foreach ( $this->errors as $error ) {
				echo sprintf( '<div id="message" class="error"><p>%s</p></div>', esc_html( $error ) );
			}
		}
	}
}

new VIWCAIO_CART_ALL_IN_ONE( $viwcaio_errors );