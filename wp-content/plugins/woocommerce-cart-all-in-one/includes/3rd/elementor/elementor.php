<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( ! is_plugin_active( 'elementor/elementor.php' ) ) {
	return;
}
add_action( 'elementor/widgets/widgets_registered', function () {
	if ( is_file( VIWCAIO_CART_ALL_IN_ONE_3RD . 'elementor/menu-cart.php' ) ) {
		require_once( 'menu-cart.php' );
		$widget = new VIWCAIO_Elementor_Menu_Cart();
		if ( version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' )){
			Elementor\Plugin::instance()->widgets_manager->register( $widget );
		}else {
			Elementor\Plugin::instance()->widgets_manager->register_widget_type( $widget );
		}
	}
} );
add_action( 'elementor/editor/before_enqueue_scripts', function () {
	wp_enqueue_style( 'vi-wcaio-cart-icons', VIWCAIO_CART_ALL_IN_ONE_CSS . 'cart-icons.min.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
	wp_enqueue_style( 'viwcaio-elementor-settings', VIWCAIO_CART_ALL_IN_ONE_CSS . 'elementor-settings.' . ( WP_DEBUG ? '' : 'min.' ) . 'css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
} );
add_action( 'elementor/preview/enqueue_styles', function () {
	wp_enqueue_style( 'vi-wcaio-cart-icons', VIWCAIO_CART_ALL_IN_ONE_CSS . 'cart-icons.min.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
	wp_enqueue_style( 'vi-wcaio-menu-cart', VIWCAIO_CART_ALL_IN_ONE_CSS . 'menu-cart.min.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
} );
add_action( 'elementor/preview/enqueue_scripts', function () {
	if ( WP_DEBUG ) {
		wp_enqueue_script( 'vi-wcaio-menu-cart', VIWCAIO_CART_ALL_IN_ONE_JS . 'menu-cart.js', array( 'jquery' ), VIWCAIO_CART_ALL_IN_ONE_VERSION );
	} else {
		wp_enqueue_script( 'vi-wcaio-menu-cart', VIWCAIO_CART_ALL_IN_ONE_JS . 'menu-cart.min.js', array( 'jquery' ), VIWCAIO_CART_ALL_IN_ONE_VERSION );
	}
} );
add_action( 'elementor/frontend/before_register_scripts', function () {
	wp_register_style( 'vi-wcaio-cart-icons', VIWCAIO_CART_ALL_IN_ONE_CSS . 'cart-icons.min.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
	wp_register_style( 'vi-wcaio-menu-cart', VIWCAIO_CART_ALL_IN_ONE_CSS . 'menu-cart.min.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
	wp_register_script( 'vi-wcaio-menu-cart', VIWCAIO_CART_ALL_IN_ONE_JS . 'menu-cart.' . ( WP_DEBUG ? '' : 'min.' ) . 'js', array( 'jquery' ), VIWCAIO_CART_ALL_IN_ONE_VERSION );
} );