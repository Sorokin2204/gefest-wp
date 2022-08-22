<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'VIWCAIO_CART_ALL_IN_ONE_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woocommerce-cart-all-in-one" . DIRECTORY_SEPARATOR );
define( 'VIWCAIO_CART_ALL_IN_ONE_INC', VIWCAIO_CART_ALL_IN_ONE_DIR . "includes" . DIRECTORY_SEPARATOR );
define( 'VIWCAIO_CART_ALL_IN_ONE_ADMIN', VIWCAIO_CART_ALL_IN_ONE_INC . "admin" . DIRECTORY_SEPARATOR );
define( 'VIWCAIO_CART_ALL_IN_ONE_FRONTEND', VIWCAIO_CART_ALL_IN_ONE_INC . "frontend" . DIRECTORY_SEPARATOR );
define( 'VIWCAIO_CART_ALL_IN_ONE_TEMPLATES', VIWCAIO_CART_ALL_IN_ONE_INC . "templates" . DIRECTORY_SEPARATOR );
define( 'VIWCAIO_CART_ALL_IN_ONE_LANGUAGES', VIWCAIO_CART_ALL_IN_ONE_DIR . "languages" . DIRECTORY_SEPARATOR );
define( 'VIWCAIO_CART_ALL_IN_ONE_3RD', VIWCAIO_CART_ALL_IN_ONE_INC . "3rd" . DIRECTORY_SEPARATOR );
$plugin_url = plugins_url( 'woocommerce-cart-all-in-one' );
$plugin_url = str_replace( '/includes', '', $plugin_url );
define( 'VIWCAIO_CART_ALL_IN_ONE_CSS', $plugin_url . '/assets/css/' );
define( 'VIWCAIO_CART_ALL_IN_ONE_JS', $plugin_url . '/assets/js/' );
define( 'VIWCAIO_CART_ALL_IN_ONE_IMAGES', $plugin_url . "/assets/images/" );
if ( is_file( VIWCAIO_CART_ALL_IN_ONE_INC . "functions.php" ) ) {
	require_once VIWCAIO_CART_ALL_IN_ONE_INC . "functions.php";
}
if ( is_file( VIWCAIO_CART_ALL_IN_ONE_INC . "check_update.php" ) ) {
	require_once VIWCAIO_CART_ALL_IN_ONE_INC . "check_update.php";
}
if ( is_file( VIWCAIO_CART_ALL_IN_ONE_INC . "update.php" ) ) {
	require_once VIWCAIO_CART_ALL_IN_ONE_INC . "update.php";
}
if ( is_file( VIWCAIO_CART_ALL_IN_ONE_INC . "support.php" ) ) {
	require_once VIWCAIO_CART_ALL_IN_ONE_INC . "support.php";
}
if ( is_file( VIWCAIO_CART_ALL_IN_ONE_INC . "data.php" ) ) {
	require_once VIWCAIO_CART_ALL_IN_ONE_INC . "data.php";
}
if ( is_file( VIWCAIO_CART_ALL_IN_ONE_INC . "customize-control.php" ) ) {
	require_once VIWCAIO_CART_ALL_IN_ONE_INC . "customize-control.php";
}
if ( is_file( VIWCAIO_CART_ALL_IN_ONE_3RD . "elementor/elementor.php" ) ) {
	require_once VIWCAIO_CART_ALL_IN_ONE_3RD . "elementor/elementor.php";
}
villatheme_include_folder( VIWCAIO_CART_ALL_IN_ONE_ADMIN, 'VIWCAIO_CART_ALL_IN_ONE_Admin_' );
villatheme_include_folder( VIWCAIO_CART_ALL_IN_ONE_FRONTEND, 'VIWCAIO_CART_ALL_IN_ONE_Frontend_' );
