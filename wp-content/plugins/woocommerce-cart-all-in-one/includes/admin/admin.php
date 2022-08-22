<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VIWCAIO_CART_ALL_IN_ONE_Admin_Admin {
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_filter(
			'plugin_action_links_woocommerce-cart-all-in-one/woocommerce-cart-all-in-one.php', array(
				$this,
				'settings_link'
			)
		);
	}

	public function settings_link( $links ) {
		$settings_link = sprintf( '<a href="%s?page=woocommerce-cart-all-in-one" title="%s">%s</a>', esc_attr( admin_url( 'admin.php' ) ),
			esc_attr__( 'Settings', 'woocommerce-cart-all-in-one' ),
			esc_html__( 'Settings', 'woocommerce-cart-all-in-one' )
		);
		array_unshift( $links, $settings_link );
		return $links;
	}

	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce-cart-all-in-one' );
		load_textdomain( 'woocommerce-cart-all-in-one', VIWCAIO_CART_ALL_IN_ONE_LANGUAGES . "woocommerce-cart-all-in-one-$locale.mo" );
		load_plugin_textdomain( 'woocommerce-cart-all-in-one', false, VIWCAIO_CART_ALL_IN_ONE_LANGUAGES );
	}

	public function init() {
		load_plugin_textdomain( 'woocommerce-cart-all-in-one' );
		$this->load_plugin_textdomain();
		if ( class_exists( 'VillaTheme_Support_Pro' ) ) {
			new VillaTheme_Support_Pro(
				array(
					'support'   => 'https://villatheme.com/supports/forum/plugins/woocommerce-cart-all-in-one/',
					'docs'      => 'http://docs.villatheme.com/?item=woocommerce-cart-all-in-one',
					'review'    => 'https://codecanyon.net/downloads',
					'css'       => VIWCAIO_CART_ALL_IN_ONE_CSS,
					'image'     => VIWCAIO_CART_ALL_IN_ONE_IMAGES,
					'slug'      => 'woocommerce-cart-all-in-one',
					'menu_slug' => 'woocommerce-cart-all-in-one',
					'version'   => VIWCAIO_CART_ALL_IN_ONE_VERSION
				)
			);
		}
	}
}