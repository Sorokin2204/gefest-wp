<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class VIWCAIO_CART_ALL_IN_ONE_Frontend_Sidebar_Cart_Content {
	public static $settings, $sc_checkout, $cache;
	public $is_customize, $customize_data;
	protected static $instance = null;
	public function __construct() {
		self::$settings    = new VIWCAIO_CART_ALL_IN_ONE_DATA();
		self::$sc_checkout = self::$settings->enable( 'sc_checkout_' );
		add_action( 'wp_enqueue_scripts', array( $this, 'viwcaio_wp_enqueue_scripts' ) );
		if ( self::$sc_checkout ) {
			add_action( 'template_redirect', array( $this, 'init_payment_gateways' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'remove_filter_is_checkout' ), 11 );
			add_filter( 'woocommerce_update_order_review_fragments', array( 'VIWCAIO_CART_ALL_IN_ONE_Frontend_Form_Checkout', 'viwcaio_woocommerce_update_order_review_fragments' ), PHP_INT_MAX, 1 );
			add_filter( 'wc_square_credit_card_payment_form_js_args', array( 'VIWCAIO_CART_ALL_IN_ONE_Frontend_Form_Checkout', 'viwcaio_wc_square_credit_card_payment_form_js_args' ), PHP_INT_MAX, 2 );
			if ( is_plugin_active( 'flexible-checkout-fields/flexible-checkout-fields.php' )
                 || is_plugin_active( 'flexible-checkout-fields-pro/flexible-checkout-fields-pro.php' )
                 || is_plugin_active( 'woocommerce-payments/woocommerce-payments.php' ) ) {
				add_action( 'wp_footer', array( $this, 'add_filter_is_checkout' ),9 );
				add_action( 'wp_footer', array( $this, 'remove_filter_is_checkout' ), 11 );
			}
			if ( is_plugin_active( 'woo-stripe-payment/stripe-payments.php' )) {
				add_action( 'wp_print_footer_scripts', array( $this, 'add_filter_is_checkout' ), 5 );
				add_action( 'wp_print_footer_scripts', array( $this, 'remove_filter_is_checkout' ), 7 );
			}
			add_filter( 'viwcaio_get_filter_is_checkout', array( $this, 'viwcaio_get_filter_is_checkout' ), 10, 1 );
			add_filter( 'viwcuf_ob_get_action', array( 'VIWCAIO_CART_ALL_IN_ONE_Frontend_Form_Checkout', 'viwcuf_ob_get_action' ), 10, 1 );
			add_filter( 'viwcuf_us_get_action', array( 'VIWCAIO_CART_ALL_IN_ONE_Frontend_Form_Checkout', 'viwcuf_ob_get_action' ), 10, 1 );
		}
	}

	public function viwcaio_get_filter_is_checkout( $filters ) {
		if ( class_exists( 'VIWCUF_CHECKOUT_UPSELL_FUNNEL' ) ) {
			$funnel_settings = new  VIWCUF_CHECKOUT_UPSELL_FUNNEL_Data();
			if ( ! $funnel_settings->get_params( 'ob_vicaio_enable' ) ) {
				$filters['viwcuf_ob_enable'] = 'viwcaio_is_not_checkout';
			}
			if ( ! $funnel_settings->get_params( 'us_vicaio_enable' ) ) {
				$filters['viwcuf_us_enable'] = 'viwcaio_is_not_checkout';
			}
		}
		return $filters;
	}
	public function add_filter_is_checkout() {
		if ( is_admin() ) {
			return;
		}
		if ( ( is_checkout() || is_cart() ) && ! is_product() ) {
			return;
		}
		if ( ! $this->assign_page() ) {
			return;
		}
		$filters = apply_filters( 'viwcaio_get_filter_is_checkout', array( 'woocommerce_is_checkout' => 'viwcaio_is_checkout' ) );
		foreach ( $filters as $k => $v ) {
			add_filter( $k, array( 'VIWCAIO_CART_ALL_IN_ONE_Frontend_Form_Checkout', $v ), PHP_INT_MAX, 1 );
		}
	}
	public function remove_filter_is_checkout() {
		if ( ! $this->assign_page() ) {
			return;
		}
		$filters = apply_filters( 'viwcaio_get_filter_is_checkout', array( 'woocommerce_is_checkout' => 'viwcaio_is_checkout' ) );
		foreach ( $filters as $k => $v ) {
			remove_filter( $k, array( 'VIWCAIO_CART_ALL_IN_ONE_Frontend_Form_Checkout', $v ), PHP_INT_MAX, 1 );
		}
	}
	public function init_payment_gateways() {
		if ( ( is_checkout() || is_cart() ) && ! is_product() ) {
			return;
		}
		if ( ! $this->assign_page() ) {
			return;
		}
		remove_action( 'wp_enqueue_scripts', 'shoptimizer_minimal_checkout', 10 );
		// Ensure gateways and shipping methods are loaded early.
		WC()->payment_gateways();
		WC()->shipping();
	}
	public function viwcaio_wp_enqueue_scripts() {
		if ( ( is_checkout() || is_cart() ) && ! is_product() ) {
			return;
		}
		$this->is_customize = self::is_customize_preview();
		if ( ! $this->is_customize && ! $this->assign_page() ) {
			return;
		}
		if ( $this->is_customize ) {
			global $wp_customize;
			$this->customize_data = $wp_customize;
		}
		$sc_footer_message = self::$settings->get_params( 'sc_footer_message' );
		$has_product_plus  = strpos( $sc_footer_message, '{product_plus}' );
		wp_enqueue_style( 'vi-wcaio-loading', VIWCAIO_CART_ALL_IN_ONE_CSS . 'loading.min.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
		$suffix = WP_DEBUG ? '' : 'min.';
		wp_enqueue_style( 'vi-wcaio-sidebar-cart-content', VIWCAIO_CART_ALL_IN_ONE_CSS . 'sidebar-cart-content.' . $suffix . 'css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
		wp_enqueue_script( 'vi-wcaio-sidebar-cart', VIWCAIO_CART_ALL_IN_ONE_JS . 'sidebar-cart.' . $suffix . 'js', array( 'jquery' ), VIWCAIO_CART_ALL_IN_ONE_VERSION );
		wp_enqueue_style( 'vi-wcaio-cart-icons', VIWCAIO_CART_ALL_IN_ONE_CSS . 'cart-icons.min.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
		if ( ( $has_product_plus !== false ) || $this->is_customize ) {
			wp_enqueue_style( 'vi-wcaio-cart-icons-atc', VIWCAIO_CART_ALL_IN_ONE_CSS . 'cart-icons-atc.min.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
			wp_enqueue_style( 'vi-wcaio-nav-icons', VIWCAIO_CART_ALL_IN_ONE_CSS . 'nav-icons.min.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
			wp_enqueue_style( 'vi-wcaio-flexslider', VIWCAIO_CART_ALL_IN_ONE_CSS . 'sc-flexslider.min.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
			wp_enqueue_script( 'vi-wcaio-flexslider', VIWCAIO_CART_ALL_IN_ONE_JS . 'vi-flexslider.min.js', array( 'jquery' ), VIWCAIO_CART_ALL_IN_ONE_VERSION );
		}
		if ( self::$sc_checkout || $this->is_customize ) {
			wp_enqueue_style( 'vi-wcaio-back-icons', VIWCAIO_CART_ALL_IN_ONE_CSS . 'back-icons.min.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
			if ( wp_style_is( 'woocommerce-general', 'registered' ) ) {
				wp_enqueue_style( 'vi-wcaio-sc-checkout', VIWCAIO_CART_ALL_IN_ONE_CSS . 'sc-checkout.'.$suffix.'css', array( 'select2', 'woocommerce-general' ), VIWCAIO_CART_ALL_IN_ONE_VERSION );
			} else {
				wp_enqueue_style( 'vi-wcaio-sc-checkout', VIWCAIO_CART_ALL_IN_ONE_CSS . 'sc-checkout.'.$suffix.'css', array( 'select2' ), VIWCAIO_CART_ALL_IN_ONE_VERSION );
			}
			wp_enqueue_script( 'vi-wcaio-selectWoo', VIWCAIO_CART_ALL_IN_ONE_JS . 'vi-selectWoo.' . $suffix . 'js', array( 'jquery' ), VIWCAIO_CART_ALL_IN_ONE_VERSION );
			wp_enqueue_script( 'vi-wcaio-sc-checkout', VIWCAIO_CART_ALL_IN_ONE_JS . 'sc-checkout.' . $suffix . 'js', array( 'jquery', 'wc-checkout' ), VIWCAIO_CART_ALL_IN_ONE_VERSION, true );
			$checkout_args = array(
				'invalid_text'              => apply_filters( 'vi-wcaio-invalid_text', esc_html__( 'Please insert all required information to checkout.', 'woocommerce-cart-all-in-one' ) ),
				'unavailable_checkout_text' => apply_filters( 'vi-wcaio-unavailable_checkout_text', esc_html__( 'Checkout is not available whilst your cart is empty.', 'woocommerce-cart-all-in-one' ) )
			);
			if ( self::$sc_checkout ) {
				if ( wp_script_is( 'alg-payment-gateways-checkout', 'registered' ) ) {
					wp_enqueue_script( 'alg-payment-gateways-checkout' );
				}
				$filters = apply_filters( 'viwcaio_get_filter_is_checkout', array( 'woocommerce_is_checkout' => 'viwcaio_is_checkout' ) );
				foreach ( $filters as $k => $v ) {
					add_filter( $k, array( 'VIWCAIO_CART_ALL_IN_ONE_Frontend_Form_Checkout', $v ), PHP_INT_MAX, 1 );
				}
				add_filter( 'yith_ppec_show_button_everywhere', array( 'VIWCAIO_CART_ALL_IN_ONE_Frontend_Form_Checkout', 'viwcaio_is_checkout' ), PHP_INT_MAX, 1 );
				if ( class_exists( 'WC_Gateway_Twocheckout' ) ) {
					$checkout_args['WC_Gateway_Twocheckout'] = array(
						'twocheckoutIsCheckout' => ( is_checkout() && empty( $_GET['pay_for_order'] ) ) ? 'yes' : 'no'
					);
					if ( ! isset( WC()->cart ) || WC()->cart->is_empty() ) {
						$checkout_args['WC_Gateway_Twocheckout']['not_twocheckoutIsCheckout'] = 1;
						wp_enqueue_script( '2payjs', 'https://2pay-js.2checkout.com/v1/2pay.js' );
						wp_enqueue_script( 'twocheckout_script', '/wp-content/plugins/twocheckout/assets/js/twocheckout.js' );
						wp_enqueue_style( 'twocheckout_style', '/wp-content/plugins/twocheckout/assets/css/twocheckout.css' );
					}
				}
				if ( class_exists( 'WC_Gateway_Twocheckout_Inline' ) ) {
					$checkout_args['WC_Gateway_Twocheckout_Inline'] = 1;
				}
				$checkout_args['country_locale'] = wp_json_encode( WC()->countries->get_country_locale() );
//				if(function_exists('devvn_ghtk')) {
////					( new DevVN_Woo_GHTK_Class() )->devvn_enqueue_UseAjaxInWp();
//					devvn_ghtk()->devvn_enqueue_UseAjaxInWp();
//				}
			}
			wp_localize_script( 'vi-wcaio-sc-checkout', 'viwcaio_sc_checkout_params', $checkout_args );
		}
		if ( ! $this->is_customize ) {
			$args = array(
				'wc_ajax_url'                      => WC_AJAX::get_endpoint( "%%endpoint%%" ),
				'language'                         => self::get_language(),
				'sc_content_class_open'            => self::$settings->get_params( 'sc_content_class_open' ) ?: '',
				'update_cart_when_changing_pd_qty' => self::$settings->get_params( 'sc_pd_update_cart' ) ?: '',
			);
			wp_localize_script( 'vi-wcaio-sidebar-cart', 'viwcaio_sc_params', $args );
			$css = $this->get_inline_css();
			wp_add_inline_style( 'vi-wcaio-sidebar-cart-content', $css );
			if ( $has_product_plus !== false ) {
				wp_enqueue_script( 'vi-wcaio-ajax-atc', VIWCAIO_CART_ALL_IN_ONE_JS . 'ajax-add-to-cart.' . $suffix . 'js', array( 'jquery' ), VIWCAIO_CART_ALL_IN_ONE_VERSION );
				$args1 = array(
					'ajax_atc'                            => self::$settings->get_params( 'ajax_atc' ),
					'wc_ajax_url'                         => WC_AJAX::get_endpoint( "%%endpoint%%" ),
					'woocommerce_enable_ajax_add_to_cart' => 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ? 1 : '',
					'added_to_cart'                       => did_action( 'woocommerce_add_to_cart' ) ?: '',
					'ajax_atc_pd_exclude'                 => self::$settings->get_params( 'ajax_atc_pd_exclude' ) ?: array(),
					'i18n_make_a_selection_text'          => apply_filters( 'vi-wcaio-i18n_make_a_selection_text', esc_html__( 'Please select some product options before adding this product to your cart.', 'woocommerce-cart-all-in-one' ) ),
					'i18n_unavailable_text'               => apply_filters( 'vi-wcaio-i18n_unavailable_text', esc_html__( 'Sorry, this product is unavailable. Please choose a different combination.', 'woocommerce-cart-all-in-one' ) ),
					'cart_url'                            => apply_filters( 'woocommerce_add_to_cart_redirect', wc_get_cart_url(), null ),
					'cart_redirect_after_add'             => get_option( 'woocommerce_cart_redirect_after_add' ),
				);
				wp_localize_script( 'vi-wcaio-ajax-atc', 'viwcaio_ajax_atc_params', $args1 );
			}
		}
		add_action( 'wp_footer', array( $this, 'frontend_html' ),9 );
	}
	public function frontend_html() {
		wc_get_template( 'sidebar-cart.php', array( 'sidebar_cart' => $this ),
			'woocommerce-cart-all-in-one' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'sidebar-cart' . DIRECTORY_SEPARATOR,
			VIWCAIO_CART_ALL_IN_ONE_TEMPLATES . 'sidebar-cart' . DIRECTORY_SEPARATOR );
	}
	public function get_inline_css() {
		$css      = '';
		$frontend = 'VIWCAIO_CART_ALL_IN_ONE_Frontend_Frontend';
		if ( self::$settings->enable( 'sc_checkout_' ) ) {
			$css .= $this->get_checkout_inline_css( $frontend );
		}
		if ( $sc_horizontal = self::$settings->get_params( 'sc_horizontal' ) ) {
			$sc_horizontal_mobile = $sc_horizontal > 20 ? 20 - $sc_horizontal : 0;
			$css                  .= '.vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_left,
            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_left{
                left: ' . $sc_horizontal . 'px ;
            }
            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_right,
            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_right{
                right: ' . $sc_horizontal . 'px ;
            }
            @media screen and (max-width: 768px) {
                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_left .vi-wcaio-sidebar-cart-content-wrap,
                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_left .vi-wcaio-sidebar-cart-content-wrap{
                    left: ' . $sc_horizontal_mobile . 'px ;
                }
                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_right .vi-wcaio-sidebar-cart-content-wrap,
                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_right .vi-wcaio-sidebar-cart-content-wrap{
                    right: ' . $sc_horizontal_mobile . 'px ;
                }
            }
            ';
		}
		if ( $sc_vertical = self::$settings->get_params( 'sc_vertical' ) ) {
			$sc_vertical_mobile = $sc_vertical > 20 ? 20 - $sc_vertical : 0;
			$css                .= '.vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_left,
            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_right{
                top: ' . $sc_vertical . 'px ;
            }
            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_right,
            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_left{
                bottom: ' . $sc_vertical . 'px ;
            }
            @media screen and (max-width: 768px) {
                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_left .vi-wcaio-sidebar-cart-content-wrap,
                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_right .vi-wcaio-sidebar-cart-content-wrap{
                    top: ' . $sc_vertical_mobile . 'px ;
                }
                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_right .vi-wcaio-sidebar-cart-content-wrap,
                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_left .vi-wcaio-sidebar-cart-content-wrap{
                    bottom: ' . $sc_vertical_mobile . 'px ;
                }
            }';
		}
		if ( $sc_loading_color = self::$settings->get_params( 'sc_loading_color' ) ) {
			$css .= '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-dual_ring:after {
                border-color: ' . $sc_loading_color . '  transparent ' . $sc_loading_color . '  transparent;
            }
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-ring div{
                border-color: ' . $sc_loading_color . '  transparent transparent transparent;
            }
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-ripple  div{
                border: 4px solid ' . $sc_loading_color . ' ;
            }
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-default div,
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-animation_face_1 div,
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-animation_face_2 div,
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-roller div:after,
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-loader_balls_1 div,
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-loader_balls_2 div,
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-loader_balls_3 div,
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-spinner div:after{
                background: ' . $sc_loading_color . ' ;
            }';
		}
		if ( self::$settings->get_params( 'sc_pd_img_box_shadow' ) ) {
			$css .= '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-img-wrap img{
                box-shadow: 0 4px 10px rgba(0,0,0,0.07);
            }';
		}
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-content-wrap' ),
			array( 'sc_radius' ),
			array( 'border-radius' ),
			array( 'px' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap' ),
			array( 'sc_header_bg_color', 'sc_header_border_style', 'sc_header_border_color' ),
			array( 'background', 'border-style', 'border-color' ),
			array( '', '', '' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-title-wrap' ),
			array( 'sc_header_title_color' ),
			array( 'color' ),
			array( '' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-coupon-wrap .vi-wcaio-coupon-code' ),
			array( 'sc_header_coupon_input_radius' ),
			array( 'border-radius' ),
			array( 'px' )
		);
		$css .= $frontend::add_inline_style(
			array(
				'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-coupon-wrap button.vi-wcaio-bt-coupon-code',
				'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-coupon-wrap .vi-wcaio-bt-coupon-code.button'
			),
			array( 'sc_header_coupon_button_bg_color', 'sc_header_coupon_button_color', 'sc_header_coupon_button_border_radius' ),
			array( 'background', 'color', 'border-radius' ),
			array( '', '', 'px' )
		);
		$css .= $frontend::add_inline_style(
			array(
				'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-coupon-wrap button.vi-wcaio-bt-coupon-code:hover',
				'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-coupon-wrap .vi-wcaio-bt-coupon-code.button:hover'
			),
			array( 'sc_header_coupon_button_bg_color_hover', 'sc_header_coupon_button_color_hover' ),
			array( 'background', 'color' ),
			array( '', '' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap' ),
			array( 'sc_footer_bg_color', 'sc_footer_border_type', 'sc_footer_border_color' ),
			array( 'background', 'border-style', 'border-color' ),
			array( '', '', '' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-footer-cart_total > div:nth-child(1)' ),
			array( 'sc_footer_cart_total_color' ),
			array( 'color' ),
			array( '' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-footer-cart_total > div:nth-child(2)' ),
			array( 'sc_footer_cart_total_color1' ),
			array( 'color' ),
			array( '' )
		);
		$css .= $frontend::add_inline_style(
			array(
				'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap button.vi-wcaio-sidebar-cart-bt-nav',
				'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt-nav.button'
			),
			array( 'sc_footer_button_bg_color', 'sc_footer_button_color', 'sc_footer_button_border_radius' ),
			array( 'background', 'color', 'border-radius' ),
			array( '', '', 'px' )
		);
		$css .= $frontend::add_inline_style(
			array(
				'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap button.vi-wcaio-sidebar-cart-bt-nav:hover',
				'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt-nav.button:hover'
			),
			array( 'sc_footer_button_hover_bg_color', 'sc_footer_button_hover_color' ),
			array( 'background', 'color' ),
			array( '', '' )
		);
		$css .= $frontend::add_inline_style(
			array(
				'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap button.vi-wcaio-sidebar-cart-bt-update',
				'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt-update.button'
			),
			array( 'sc_footer_bt_update_bg_color', 'sc_footer_bt_update_color', 'sc_footer_bt_update_border_radius' ),
			array( 'background', 'color', 'border-radius' ),
			array( '', '', 'px' )
		);
		$css .= $frontend::add_inline_style(
			array(
				'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap button.vi-wcaio-sidebar-cart-bt-update:hover',
				'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt-update.button:hover'
			),
			array( 'sc_footer_bt_update_hover_bg_color', 'sc_footer_bt_update_hover_color' ),
			array( 'background', 'color' ),
			array( '', '' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-footer-pd-plus-title' ),
			array( 'sc_footer_pd_plus_color' ),
			array( 'color' ),
			array( '' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products-wrap' ),
			array( 'sc_pd_bg_color' ),
			array( 'background' ),
			array( '' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-img-wrap img' ),
			array( 'sc_pd_img_border_radius' ),
			array( 'border-radius' ),
			array( 'px' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-info-wrap .vi-wcaio-sidebar-cart-pd-name-wrap .vi-wcaio-sidebar-cart-pd-name, .vi-wcaio-sidebar-cart-footer-pd-name *' ),
			array( 'sc_pd_name_color' ),
			array( 'color' ),
			array( '' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-info-wrap .vi-wcaio-sidebar-cart-pd-name-wrap .vi-wcaio-sidebar-cart-pd-name:hover, .vi-wcaio-sidebar-cart-footer-pd-name *:hover' ),
			array( 'sc_pd_name_hover_color' ),
			array( 'color' ),
			array( '' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-info-wrap .vi-wcaio-sidebar-cart-pd-price *, .vi-wcaio-sidebar-cart-footer-pd-price *' ),
			array( 'sc_pd_price_color' ),
			array( 'color' ),
			array( '' )
		);
		if ( $sc_pd_qty_border_color = self::$settings->get_params( 'sc_pd_qty_border_color' ) ) {
			$css .= '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-desc .vi-wcaio-sidebar-cart-pd-quantity{
                 border: 1px solid ' . $sc_pd_qty_border_color . ' ;
            }';
			$css .= '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-desc .vi_wcaio_minus{
                 border-right: 1px solid ' . $sc_pd_qty_border_color . ' ;
            }';
			$css .= '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-desc .vi_wcaio_plus{
                 border-left: 1px solid ' . $sc_pd_qty_border_color . ' ;
            }';
			$css .= '.vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-rtl .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-desc .vi_wcaio_minus{
			     border-right: unset;
                 border-left: 1px solid ' . $sc_pd_qty_border_color . ' ;
            }';
			$css .= '.vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-rtl .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-desc .vi_wcaio_plus{
			     border-left: unset;
                 border-right: 1px solid ' . $sc_pd_qty_border_color . ' ;
            }';
		}
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-desc .vi-wcaio-sidebar-cart-pd-quantity' ),
			array( 'sc_pd_qty_border_radius' ),
			array( 'border-radius' ),
			array( 'px' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-remove-wrap i' ),
			array( 'sc_pd_delete_icon_color' ),
			array( 'color' ),
			array( '' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-remove-wrap i:before' ),
			array( 'sc_pd_delete_icon_font_size' ),
			array( 'font-size' ),
			array( 'px' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-remove-wrap i:hover' ),
			array( 'sc_pd_delete_icon_hover_color' ),
			array( 'color' ),
			array( '' )
		);
		$css .= $frontend::add_inline_style(
			array(
				'.vi-wcaio-sidebar-cart button.vi-wcaio-pd_plus-product-bt-atc',
				'.vi-wcaio-sidebar-cart .button.vi-wcaio-pd_plus-product-bt-atc',
			),
			array( 'sc_footer_pd_plus_bt_atc_bg_color', 'sc_footer_pd_plus_bt_atc_color' ),
			array( 'background', 'color' ),
			array( '', '' )
		);
		$css .= $frontend::add_inline_style(
			array(
				'.vi-wcaio-sidebar-cart button.vi-wcaio-pd_plus-product-bt-atc:hover',
				'.vi-wcaio-sidebar-cart .button.vi-wcaio-pd_plus-product-bt-atc:hover',
			),
			array( 'sc_footer_pd_plus_bt_atc_hover_bg_color', 'sc_footer_pd_plus_bt_atc_hover_color' ),
			array( 'background', 'color' ),
			array( '', '' )
		);
		$css = str_replace( array( "\r", "\n", '\r', '\n' ), ' ', $css );
		return $css;
	}
	public function get_checkout_inline_css( $frontend ) {
		$css = '';
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-content-wrap1.vi-wcaio-sidebar-cart-checkout-wrap' ),
			array( 'sc_checkout_bg_color' ),
			array( 'background' ),
			array( '' )
		);
		$css .= $frontend::add_inline_style(
			array(
				'.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-cancel',
				'.vi-wcaio-sidebar-cart .button.vi-wcaio-sidebar-cart-bt-checkout-cancel'
			),
			array( 'sc_checkout_bt_btc_bg_color', 'sc_checkout_bt_btc_color', 'sc_checkout_bt_btc_border_radius' ),
			array( 'background', 'color', 'border-radius' ),
			array( '', '', 'px' )
		);
		$css .= $frontend::add_inline_style(
			array(
				'.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-cancel:hover',
				'.vi-wcaio-sidebar-cart .button.vi-wcaio-sidebar-cart-bt-checkout-cancel:hover'
			),
			array( 'sc_checkout_bt_btc_hover_bg_color', 'sc_checkout_bt_btc_hover_color' ),
			array( 'background', 'color' ),
			array( '', '' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-next' ),
			array( 'sc_checkout_bt_next_bg_color', 'sc_checkout_bt_next_color', 'sc_checkout_bt_next_border_radius' ),
			array( 'background', 'color', 'border-radius' ),
			array( '', '', 'px' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart .button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-next:hover' ),
			array( 'sc_checkout_bt_next_hover_bg_color', 'sc_checkout_bt_next_hover_color' ),
			array( 'background', 'color' ),
			array( '', '' )
		);
		$css .= $frontend::add_inline_style(
			array(
				'.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-back',
				'.vi-wcaio-sidebar-cart .button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-back'
			),
			array( 'sc_checkout_bt_pre_bg_color', 'sc_checkout_bt_pre_color', 'sc_checkout_bt_pre_border_radius' ),
			array( 'background', 'color', 'border-radius' ),
			array( '', '', 'px' )
		);
		$css .= $frontend::add_inline_style(
			array(
				'.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-back:hover',
				'.vi-wcaio-sidebar-cart .button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-back:hover',
			),
			array( 'sc_checkout_bt_pre_hover_bg_color', 'sc_checkout_bt_pre_hover_color' ),
			array( 'background', 'color' ),
			array( '', '' )
		);
		$css .= $frontend::add_inline_style(
			array(
				'.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-place_order',
				'.vi-wcaio-sidebar-cart .button.vi-wcaio-sidebar-cart-bt-checkout-place_order',
			),
			array( 'sc_checkout_bt_place_order_bg_color', 'sc_checkout_bt_place_order_color', 'sc_checkout_bt_place_order_border_radius' ),
			array( 'background', 'color', 'border-radius' ),
			array( '', '', 'px' )
		);
		$css .= $frontend::add_inline_style(
			array(
				'.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-place_order:hover',
				'.vi-wcaio-sidebar-cart .button.vi-wcaio-sidebar-cart-bt-checkout-place_order:hover',
			),
			array( 'sc_checkout_bt_place_order_hover_bg_color', 'sc_checkout_bt_place_order_hover_color' ),
			array( 'background', 'color' ),
			array( '', '' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart-wrap .vi-wcaio-checkout-nav-step-wrap .vi-wcaio-checkout-nav-step' ),
			array( 'sc_checkout_nav_bar_color' ),
			array( 'background' ),
			array( '' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart-wrap .vi-wcaio-checkout-nav-step-wrap .vi-wcaio-checkout-nav-step:hover' ),
			array( 'sc_checkout_nav_bar_hover_color' ),
			array( 'background' ),
			array( '' )
		);
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart-wrap .vi-wcaio-checkout-nav-step-wrap .vi-wcaio-checkout-nav-step.vi-wcaio-checkout-step-current' ),
			array( 'sc_checkout_nav_bar_selected_color' ),
			array( 'background' ),
			array( '' )
		);
		return $css;
	}
	public function assign_page() {
		if ( isset( self::$cache['assign_page'] ) ) {
			return self::$cache['assign_page'];
		}
		if ( ! self::$settings->enable( 'sc_' ) ) {
			return self::$cache['assign_page'] = false;
		}
		$assign_page = self::$settings->get_params( 'sc_assign_page' );
		if ( $assign_page ) {
			if ( stristr( $assign_page, "return" ) === false ) {
				$assign_page = "return (" . $assign_page . ");";
			}
			if ( ! eval( $assign_page ) ) {
				return self::$cache['assign_page'] = false;
			}
		}
		return self::$cache['assign_page'] = true;
	}
	public static function is_customize_preview() {
		if ( isset( self::$cache['is_customize_preview'] ) ) {
			return self::$cache['is_customize_preview'];
		}
		return self::$cache['is_customize_preview'] = is_customize_preview();
	}
	public static function get_language() {
		if ( isset( self::$cache['language'] ) ) {
			return self::$cache['language'];
		}
		if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
			$default_lang     = apply_filters( 'wpml_default_language', null );
			$current_language = apply_filters( 'wpml_current_language', null );
			if ( $current_language && $current_language !== $default_lang ) {
				$language = $current_language;
			}
		} else if ( class_exists( 'Polylang' ) && function_exists( 'pll_default_language' ) ) {
			$default_lang     = pll_default_language( 'slug' );
			$current_language = pll_current_language( 'slug' );
			if ( $current_language && $current_language !== $default_lang ) {
				$language = $current_language;
			}
		}
		self::$cache['language'] = $language ?? '';
		return self::$cache['language'];
	}
	public function get_params( $name = '', $language = '' ) {
		if ( $this->customize_data && $name && $setting = $this->customize_data->get_setting( 'woo_cart_all_in_one_params[' . $name . ']' ) ) {
			return $this->customize_data->post_value( $setting, self::$settings->get_params( $name ) );
		} else {
			return self::$settings->get_params( $name, $language );
		}
	}
	public static function get_instance( $new = false ) {
		if ( $new || null === self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	public static function get_sidebar_content_pd_html( $wc_cart, $sc_pd_price_style = null ) {
		wc_get_template( 'sc-product-list-html.php',
			array(
				'sidebar_cart'      => self::get_instance(),
				'wc_cart'           => $wc_cart,
				'sc_pd_price_style' => $sc_pd_price_style,
			),
			'woocommerce-cart-all-in-one' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'sidebar-cart' . DIRECTORY_SEPARATOR,
			VIWCAIO_CART_ALL_IN_ONE_TEMPLATES . 'sidebar-cart' . DIRECTORY_SEPARATOR );
	}
	public static function get_sc_pd_quantity_html( $args = array(), $echo = false ) {
		if ( $echo ) {
			wc_get_template( 'vicaio-product-quantity-html.php', array( 'args' => $args ),
				'woocommerce-cart-all-in-one' . DIRECTORY_SEPARATOR,
				VIWCAIO_CART_ALL_IN_ONE_TEMPLATES . DIRECTORY_SEPARATOR );
		} else {
			ob_start();
			wc_get_template( 'vicaio-product-quantity-html.php', array( 'args' => $args ),
				'woocommerce-cart-all-in-one' . DIRECTORY_SEPARATOR,
				VIWCAIO_CART_ALL_IN_ONE_TEMPLATES . DIRECTORY_SEPARATOR );
			$html = ob_get_clean();
			return $html;
		}
	}
	public static function get_sc_pd_price_html( $wc_cart, $cart_item, $cart_item_key, $product, $style = 'price' ) {
		if ( ! $wc_cart || ! $product ) {
			return '';
		}
		switch ( $style ) {
			case 'qty':
				$html = $product->is_sold_individually() ? 1 : ( $cart_item['quantity'] ?? 1 );
				$html .= ' &#215; ' . apply_filters( 'woocommerce_cart_item_price', $wc_cart->get_product_price( $product ), $cart_item, $cart_item_key );
				break;
			case 'subtotal':
				$html = apply_filters( 'woocommerce_cart_item_subtotal', $wc_cart->get_product_subtotal( $product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
				break;
			default:
				$html = apply_filters( 'woocommerce_cart_item_price', $wc_cart->get_product_price( $product ), $cart_item, $cart_item_key );
		}
		return $html;
	}
	public static function get_sc_footer_coupon_html( $coupons ) {
		if ( empty( $coupons ) ) {
			return apply_filters( 'vi_wcaio_footer_coupon_html', '' );
		}
		ob_start();
		foreach ( $coupons as $code => $coupon ) {
			?>
            <tr class="vi-wcaio-coupon vi-wcaio-coupon-<?php echo esc_attr( $code ) ?>">
                <td><?php wc_cart_totals_coupon_label( $coupon ); ?></td>
                <td><?php wc_cart_totals_coupon_html( $coupon ); ?> </td>
            </tr>
			<?php
		}
		$html = ob_get_clean();
		$html = '<table cellspacing="0" >' . $html . '</table>';
		return apply_filters( 'vi_wcaio_footer_coupon_html', $html );
	}
	public static function get_sc_footer_message_html( $text, $language = false ) {
		if ( ! $text ) {
			return '';
		}
		$shortcodes = array();
		preg_match_all( '/' . get_shortcode_regex() . '/', $text, $matches, PREG_SET_ORDER );
		if ( ! empty( $matches ) ) {
			foreach ( $matches as $shortcode ) {
				$shortcodes[] = $shortcode[0];
			}
		}
		if ( count( $shortcodes ) ) {
			foreach ( $shortcodes as $shortcode ) {
				$text = str_replace( $shortcode, do_shortcode( $shortcode ), $text );
			}
		}
		$text = str_replace( '{product_plus}', self::get_product_plus( $language ), $text );
		echo wp_kses( $text, VIWCAIO_CART_ALL_IN_ONE_DATA::extend_post_allowed_html() );
	}
	public static function get_product_plus( $language = false, $type = false ) {
		$settings           = self::$settings;
		$language           = $language ? self::get_language() : '';
		$sc_footer_pd_plus  = $type !== false ? $type : $settings->get_params( 'sc_footer_pd_plus' );
		$product_plus_limit = $settings->get_params( 'sc_footer_pd_plus_limit' );
		$out_of_stock       = $settings->get_params( 'sc_footer_pd_plus_out_of_stock' );
		$product_plus       = self::get_sidebar_pd_plus( $settings, $sc_footer_pd_plus, $product_plus_limit, $out_of_stock );
		if ( $language && is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
			$product_plus = self::get_sidebar_pd_plus_wpml( $product_plus, $language );
		}
		if ( empty( $product_plus ) || ! is_array( $product_plus ) ) {
			return '<div class="vi-wcaio-sidebar-cart-footer-pd-wrap-wrap vi-wcaio-disabled"></div>';
		}
		ob_start();
		?>
        <div class="vi-wcaio-sidebar-cart-footer-pd-wrap-wrap vi-wcaio-sidebar-cart-footer-pd-<?php echo esc_attr( $sc_footer_pd_plus ); ?>">
            <div class="vi-wcaio-sidebar-cart-footer-pd-plus-title">
				<?php echo wp_kses_post( $settings->get_params( 'sc_footer_pd_plus_title', $language ) ); ?>
            </div>
            <div class="vi-wcaio-sidebar-cart-footer-pd-wrap">
				<?php
				foreach ( $product_plus as $product_id ) {
					wc_get_template( 'sc-product-plus-html.php',
						array( 'product_id' => $product_id, 'language' => $language, 'settings' => $settings ),
						'woocommerce-cart-all-in-one' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'sidebar-cart' . DIRECTORY_SEPARATOR,
						VIWCAIO_CART_ALL_IN_ONE_TEMPLATES . 'sidebar-cart' . DIRECTORY_SEPARATOR );
				}
				?>
            </div>
        </div>
		<?php
		$html = ob_get_clean();
		return $html;
	}
	public static function get_sidebar_pd_plus( $settings, $type = '', $limit = 5, $out_of_stock = false ) {
		if ( ! $type || ! $limit ) {
			return false;
		}
		$limit                     = $limit > 15 ? 15 : $limit;
		$product_visibility_hidden = apply_filters( 'vi_wcaio_sc_pd_plus_visibility_hidden', 1 );
		switch ( $type ) {
			case 'best_selling':
				$args = array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'meta_key'       => 'total_sales',
					'orderby'        => 'meta_value_num',
					'order'          => 'DESC',
					'posts_per_page' => $limit
				);
				if ( $product_visibility_hidden ) {
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'product_visibility',
							'terms'    => array( 'exclude-from-catalog', 'exclude-from-search' ),
							'field'    => 'name',
							'operator' => 'NOT IN'
						)
					);
				}
				if ( ! $out_of_stock ) {
					$args['meta_query'] = array(
						'relation' => 'AND',
						array(
							'key'     => '_stock_status',
							'value'   => 'instock',
							'compare' => 'EQUAL'
						)
					);
				}
				$product_ids = array();
				$the_query   = new WP_Query( $args );
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$product_ids[] = get_the_ID();
					}
				}
				wp_reset_postdata();
				break;
			case 'viewed_product':
				$viewed_products = is_active_widget( false, false, 'woocommerce_recently_viewed_products', true ) ? ( $_COOKIE['woocommerce_recently_viewed'] ?? '' ) : '';
				$viewed_products = $viewed_products ?: ( $_COOKIE['viwcaio_recently_viewed'] ?? '' );
				$product_ids_t   = $viewed_products ? explode( '|', wp_unslash( $viewed_products ) ) : array();
				if ( $product_visibility_hidden ) {
					$product_ids_t1 = $product_ids_t;
					$product_ids_t  = array();
					foreach ( $product_ids_t1 as $id ) {
						$product = wc_get_product( $id );
						if ( $product->get_catalog_visibility() === 'hidden' ) {
							continue;
						}
						$product_ids_t[] = $id;
					}
				}
				if ( empty( $product_ids_t ) ) {
					break;
				}
				if ( ! $out_of_stock ) {
					$product_ids = array();
					foreach ( $product_ids_t as $id ) {
						if ( ! $limit ) {
							break;
						}
						$product = wc_get_product( $id );
						if ( ! $product->is_in_stock() ) {
							continue;
						}
						$product_ids[] = $id;
					}
				} else {
					$product_ids = $product_ids_t;
					if ( $limit < count( $product_ids_t ) ) {
						$product_ids = array_slice( $product_ids_t, 0, $limit );
					}
				}
				break;
			case 'product_rating':
				$args = array(
					'post_type'      => 'product',
					'meta_key'       => '_wc_average_rating',
					'orderby'        => 'meta_value_num',
					'order'          => 'DESC',
					'posts_per_page' => $limit
				);
				if ( $product_visibility_hidden ) {
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'product_visibility',
							'terms'    => array( 'exclude-from-catalog', 'exclude-from-search' ),
							'field'    => 'name',
							'operator' => 'NOT IN'
						)
					);
				}
				if ( ! $out_of_stock ) {
					$args['meta_query'] = array(
						'relation' => 'AND',
						array(
							'key'     => '_stock_status',
							'value'   => 'instock',
							'compare' => 'EQUAL'
						)
					);
				}
				$product_ids = array();
				$the_query   = new WP_Query( $args );
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$product_ids[] = get_the_ID();
					}
				}
				wp_reset_postdata();
				break;
			case 'product_featured':
				$args = array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'orderby'        => 'date',
					'order'          => 'DESC',
					'post__in'       => wc_get_featured_product_ids(),
					'posts_per_page' => $limit
				);
				if ( $product_visibility_hidden ) {
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'product_visibility',
							'terms'    => array( 'exclude-from-catalog', 'exclude-from-search' ),
							'field'    => 'name',
							'operator' => 'NOT IN'
						)
					);
				}
				if ( ! $out_of_stock ) {
					$args['meta_query'] = array(
						'relation' => 'AND',
						array(
							'key'     => '_stock_status',
							'value'   => 'instock',
							'compare' => 'EQUAL'
						)
					);
				}
				$product_ids = array();
				$the_query   = new WP_Query( $args );
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$product_ids[] = get_the_ID();
					}
				}
				wp_reset_postdata();
				break;
			case 'select_cat':
				if ( isset( $_REQUEST['vicaio_selected_cats'] ) ) {
					$selected_cats = sanitize_text_field( $_REQUEST['vicaio_selected_cats'] );
				} else {
					$selected_cats = $settings->get_params( 'sc_footer_pd_plus_cats' ) ?? '';
				}
				if ( ! $selected_cats ) {
					break;
				}
				$selected_cats                  = explode( ',', $selected_cats );
				$args                           = array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'orderby'        => 'date',
					'order'          => 'DESC',
					'posts_per_page' => $limit
				);
				$args['tax_query'] ['relation'] = 'AND';
				$args['tax_query'] []           = array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $selected_cats,
					'operator' => 'IN'
				);
				if ( $product_visibility_hidden ) {
					$args['tax_query'] ['relation'] = 'AND';
					$args['tax_query'] []           = array(
						'taxonomy' => 'product_visibility',
						'terms'    => array( 'exclude-from-catalog', 'exclude-from-search' ),
						'field'    => 'name',
						'operator' => 'NOT IN'
					);
				}
				if ( ! $out_of_stock ) {
					$args['meta_query'] = array(
						'relation' => 'AND',
						array(
							'key'     => '_stock_status',
							'value'   => 'instock',
							'compare' => 'EQUAL'
						)
					);
				}
				$product_ids = array();
				$the_query   = new WP_Query( $args );
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$product_ids[] = get_the_ID();
					}
				}
				wp_reset_postdata();
				break;
			case 'select_pd':
				if ( isset( $_REQUEST['vicaio_selected_pd'] ) ) {
					$selected_pd = sanitize_text_field( $_REQUEST['vicaio_selected_pd'] );
				} else {
					$selected_pd = $settings->get_params( 'sc_footer_pd_plus_products' ) ?? '';
				}
				if ( ! $selected_pd ) {
					break;
				}
				$selected_pd = explode( ',', $selected_pd );
				$args        = array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'orderby'        => 'date',
					'order'          => 'DESC',
					'post__in'       => $selected_pd,
					'posts_per_page' => $limit
				);
				if ( $product_visibility_hidden ) {
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'product_visibility',
							'terms'    => array( 'exclude-from-catalog', 'exclude-from-search' ),
							'field'    => 'name',
							'operator' => 'NOT IN'
						)
					);
				}
				if ( ! $out_of_stock ) {
					$args['meta_query'] = array(
						'relation' => 'AND',
						array(
							'key'     => '_stock_status',
							'value'   => 'instock',
							'compare' => 'EQUAL'
						)
					);
				}
				$product_ids = array();
				$the_query   = new WP_Query( $args );
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$product_ids[] = get_the_ID();
					}
				}
				wp_reset_postdata();
				break;
			case 'product_upsell':
				if ( empty( WC()->cart ) ) {
					break;
				}
				$upsell_pd_ids = array();
				$cart_pd_ids   = array();
				foreach ( WC()->cart->get_cart() as $key => $item ) {
					$product_id = $item['product_id'] ?? '';
					if ( ! $product_id || in_array( $product_id, $cart_pd_ids ) ) {
						continue;
					}
					$cart_pd_ids[] = $product_id;
					$upsell_ids    = get_post_meta( $product_id, '_upsell_ids', true );
					if ( is_array( $upsell_ids ) && count( $upsell_ids ) ) {
						$upsell_pd_ids = array_unique( array_merge( $upsell_ids, $upsell_pd_ids ) );
					}
				}
				if ( empty( $upsell_pd_ids ) ) {
					break;
				}
				$args = array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'orderby'        => 'date',
					'order'          => 'DESC',
					'post__in'       => array_unique( $upsell_pd_ids ),
					'posts_per_page' => $limit
				);
				if ( $product_visibility_hidden ) {
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'product_visibility',
							'terms'    => array( 'exclude-from-catalog', 'exclude-from-search' ),
							'field'    => 'name',
							'operator' => 'NOT IN'
						)
					);
				}
				if ( ! $out_of_stock ) {
					$args['meta_query'] = array(
						'relation' => 'AND',
						array(
							'key'     => '_stock_status',
							'value'   => 'instock',
							'compare' => 'EQUAL'
						)
					);
				}
				$product_ids = array();
				$the_query   = new WP_Query( $args );
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$product_ids[] = get_the_ID();
					}
				}
				wp_reset_postdata();
				break;
			case 'cross_sell':
				if ( empty( WC()->cart ) ) {
					break;
				}
				$crosssell_pd_ids = array();
				$cart_pd_ids      = array();
				foreach ( WC()->cart->get_cart() as $key => $item ) {
					$product_id = $item['product_id'] ?? '';
					if ( ! $product_id || in_array( $product_id, $cart_pd_ids ) ) {
						continue;
					}
					$cart_pd_ids[] = $product_id;
					$crosssell_ids = get_post_meta( $product_id, '_crosssell_ids', true );
					if ( is_array( $crosssell_ids ) && count( $crosssell_ids ) ) {
						$crosssell_pd_ids = array_merge( $crosssell_ids, $crosssell_pd_ids );
					}
				}
				if ( empty( $crosssell_pd_ids ) ) {
					break;
				}
				$args = array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'orderby'        => 'date',
					'order'          => 'DESC',
					'post__in'       => array_unique( $crosssell_pd_ids ),
					'posts_per_page' => $limit
				);
				if ( $product_visibility_hidden ) {
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'product_visibility',
							'terms'    => array( 'exclude-from-catalog', 'exclude-from-search' ),
							'field'    => 'name',
							'operator' => 'NOT IN'
						)
					);
				}
				if ( ! $out_of_stock ) {
					$args['meta_query'] = array(
						'relation' => 'AND',
						array(
							'key'     => '_stock_status',
							'value'   => 'instock',
							'compare' => 'EQUAL'
						)
					);
				}
				$product_ids = array();
				$the_query   = new WP_Query( $args );
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$product_ids[] = get_the_ID();
					}
				}
				wp_reset_postdata();
				break;
		}
		return $product_ids ?? false;
	}
	public static function get_sidebar_pd_plus_wpml( $product_plus, $language = '' ) {
		if ( ! $language || ! is_array( $product_plus ) || empty( $product_plus ) ) {
			return false;
		}
		$result = array();
		foreach ( $product_plus as $product_id ) {
			$result[] = apply_filters( 'wpml_object_id', $product_id, 'product', false, $language );
		}
		return array_unique( $result );
	}
	public function get_sidebar_loading( $type ) {
		if ( ! $type ) {
			return;
		}
		$class   = array(
			'vi-wcaio-sidebar-cart-loading vi-wcaio-sidebar-cart-loading-' . $type
		);
		$class[] = $this->is_customize ? 'vi-wcaio-disabled' : '';
		$class   = trim( implode( ' ', $class ) );
		switch ( $type ) {
			case 'spinner':
			case 'default':
				?>
                <div class="<?php echo esc_attr( $class ); ?>">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
				<?php
				break;
			case 'dual_ring':
				?>
                <div class="<?php echo esc_attr( $class ); ?>"></div>
				<?php
				break;
			case 'animation_face_1':
				?>
            <div class="<?php echo esc_attr( $class ); ?>">
                    <div></div>
                    <div></div>
                    <div></div></div><?php
				break;
			case 'animation_face_2':
			case 'ring':
				?>
            <div class="<?php echo esc_attr( $class ); ?>">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div></div><?php
				break;
			case 'roller':
				?>
                <div class="<?php echo esc_attr( $class ); ?>">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
				<?php
				break;
			case 'loader_balls_1':
				?>
                <div class="<?php echo esc_attr( $class ); ?>">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
				<?php
				break;
			case 'loader_balls_2':
			case 'loader_balls_3':
				?>
                <div class="<?php echo esc_attr( $class ); ?>">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
				<?php
				break;
			case 'ripple':
				?>
                <div class="<?php echo esc_attr( $class ); ?>">
                    <div></div>
                    <div></div>
                </div>
				<?php
				break;
		}
	}
	public static function product_get_bt_atc_html( $settings, $language ) {
		if ( ! $settings ) {
			return '';
		}
		$html            = $settings->get_params( 'sc_footer_pd_plus_bt_atc_title', $language );
		$cart_icon_class = $settings->get_class_icon( $settings->get_params( 'sc_footer_pd_plus_bt_atc_cart_icon' ), 'cart_icons_atc' ) ?: '';
		$html            = str_replace( '{cart_icon}', '<i class="vi-wcaio-pd_plus-bt-atc-cart_icons ' . $cart_icon_class . '"></i>', $html );
		return $html;
	}
}