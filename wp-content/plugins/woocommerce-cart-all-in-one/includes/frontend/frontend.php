<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class VIWCAIO_CART_ALL_IN_ONE_Frontend_Frontend {
	protected $settings;

	public function __construct() {
		$this->settings = new VIWCAIO_CART_ALL_IN_ONE_DATA();
		if ( ! $this->settings->enable( 'sb_' ) && ! $this->settings->enable( 'sc_' ) && ! $this->settings->enable( 'mc_' ) && ! $this->settings->enable( 'vp_' ) ) {
			if ( ! $this->settings->get_params( 'ajax_atc_pd_variable' ) ) {
				return;
			}
		}
		add_action( 'wp_enqueue_scripts', array( $this, 'viwcaio_wp_enqueue_scripts' ) );
		add_filter( 'woocommerce_add_to_cart_fragments', array( __CLASS__, 'viwcaio_woocommerce_add_to_cart_fragments' ), PHP_INT_MAX, 1 );
		add_action( 'template_redirect', array( $this, 'viwcaio_recently_viewed' ) );
		add_action( 'wp_ajax_vi_wcaio_get_class_icon', array( $this, 'viwcaio_get_class_icon' ) );
		add_action( 'wp_ajax_vi_wcaio_get_menu_cart_text', array( $this, 'viwcaio_get_menu_cart_text' ) );
		add_action( 'wp_ajax_vi_wcaio_get_product_url', array( $this, 'viwcaio_get_product_url' ) );
		add_action( 'wp_ajax_vi_wcaio_change_sc_pd_price_style', array( $this, 'viwcaio_change_sc_pd_price_style' ) );
		add_action( 'wp_ajax_vi_wcaio_get_sc_footer_message_html', array( $this, 'viwcaio_get_sc_footer_message_html' ) );
		add_action( 'wp_ajax_vi_wcaio_get_sc_footer_pd_plus_html', array( $this, 'viwcaio_get_sc_footer_pd_plus_html' ) );
		add_action( 'wp_ajax_viwcaio_get_checkout_form_preview', array( __CLASS__, 'viwcaio_get_checkout_form' ) );
		add_action( 'wp_ajax_viwcaio_get_cart_fragments', array( __CLASS__, 'viwcaio_get_cart_fragments' ) );
		self::add_ajax_events();
		add_filter( 'viwcaio_quantity_input_args', array( __CLASS__, 'viwcaio_quantity_input_args' ), 10, 2 );
		add_action( 'vi_wcaio_sc_pd_plus_simple_atc', array( $this, 'get_pd_plus_simple_atc' ), 10, 3 );
		add_action( 'vi_wcaio_sc_pd_plus_external_atc', array( $this, 'get_pd_plus_external_atc' ), 10, 3 );
		add_action( 'vi_wcaio_sc_pd_plus_grouped_atc', array( $this, 'get_pd_plus_grouped_atc' ), 10, 3 );
		add_action( 'vi_wcaio_sc_pd_plus_variable_atc', array( $this, 'get_pd_plus_grouped_atc' ), 10, 3 );
		add_action( 'vi_wcaio_get_sidebar_cart_content', array( $this, 'get_sidebar_cart_content' ) );
	}
	public function get_sidebar_cart_content() {
		wc_get_template( 'sc-content.php', array( 'sidebar_cart' => VIWCAIO_CART_ALL_IN_ONE_Frontend_Sidebar_Cart_Content::get_instance() ),
			'woocommerce-cart-all-in-one' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'sidebar-cart' . DIRECTORY_SEPARATOR,
			VIWCAIO_CART_ALL_IN_ONE_TEMPLATES . 'sidebar-cart' . DIRECTORY_SEPARATOR );
	}
	public function get_pd_plus_grouped_atc( $product, $settings, $language ) {
		$product_type = $product->get_type();
		$class        = array( 'vi-wcaio-product-bt-atc vi-wcaio-product-bt-not-atc vi-wcaio-pd_plus-product-bt-atc button' );
		$class[]      = 'vi-wcaio-product-bt-' . $product_type;
		if ( $product_type === 'variable' && $this->settings->get_params( 'ajax_atc_pd_variable' ) ) {
			$class[]    = 'vi-wcaio-loop-variable-bt-atc';
			$data_pd_id = 'data-product_id="' . $product->get_id() . '"';
			if ( ! wp_style_is( 'vi-wcaio-variable-atc', 'enqueued' ) ) {
				wp_enqueue_style( 'vi-wcaio-variable-atc' );
				wp_enqueue_style( 'vi-wcaio-nav-icons' );
			}
			if ( ! wp_script_is( 'vi-wcaio-variable-atc', 'enqueued' ) ) {
				wp_enqueue_script( 'vi-wcaio-variable-atc' );
				wp_enqueue_script( 'vi-wcaio-ajax-atc' );
				wp_enqueue_script( 'vi-wcaio-frontend-swatches' );
			}
		}
		$bt_class = trim( apply_filters( 'vi_wcaio_sc_pd_plus_atc_class', implode( ' ', $class ), $product ) );
		do_action( 'vi_wcaio_sc_pd_plus_before_atc', $product );
		echo sprintf( '<a href="%s" class="%s" %s target="_blank">%s</a>',
			esc_attr( esc_url( $product->get_permalink() ) ), $bt_class, $data_pd_id ?? '',
			VIWCAIO_CART_ALL_IN_ONE_Frontend_Sidebar_Cart_Content::product_get_bt_atc_html( $settings, $language )
		);
		do_action( 'vi_wcaio_sc_pd_plus_after_atc', $product );
	}
	public function get_pd_plus_external_atc( $product, $settings, $language ) {
		$bt_class = 'vi-wcaio-product-bt-atc vi-wcaio-product-bt-not-atc vi-wcaio-product-bt-external vi-wcaio-pd_plus-product-bt-atc vi-wcaio-pd_plus-product-bt-external button';
		$bt_class = trim( apply_filters( 'vi_wcaio_sc_pd_plus_atc_class', $bt_class, $product ) );
		?>
        <form class="vi-wcaio-pd_plus-cart-form" action="<?php echo esc_attr( esc_url( $product->add_to_cart_url() ) ); ?>" method="get">
			<?php do_action( 'vi_wcaio_sc_pd_plus_before_atc', $product ); ?>
            <button type="submit" class="<?php echo esc_attr( $bt_class ); ?>">
				<?php echo wp_kses_post( VIWCAIO_CART_ALL_IN_ONE_Frontend_Sidebar_Cart_Content::product_get_bt_atc_html( $settings, $language ) ); ?>
            </button>
			<?php do_action( 'vi_wcaio_sc_pd_plus_after_atc', $product ); ?>
        </form>
		<?php
	}
	public function get_pd_plus_simple_atc( $product, $settings, $language ) {
		$product_id = $product->get_id();
		$data_pd_id = 'data-product_id="' . $product_id . '"';
		$bt_class   = trim( apply_filters( 'vi_wcaio_sc_pd_plus_atc_class', 'vi-wcaio-product-bt-atc vi-wcaio-pd_plus-product-bt-atc button', $product ) );
		do_action( 'vi_wcaio_sc_pd_plus_before_atc', $product );
		echo sprintf( '<a href="%s" class="%s" %s target="_blank">%s</a>',
			esc_attr( esc_url( $product->get_permalink() ) ), $bt_class, $data_pd_id ?? '', VIWCAIO_CART_ALL_IN_ONE_Frontend_Sidebar_Cart_Content::product_get_bt_atc_html( $settings, $language )
		);
		do_action( 'vi_wcaio_sc_pd_plus_after_atc', $product );
		?>
        <input type="hidden" name="add-to-cart" class="vi-wcaio-add-to-cart" value="<?php echo esc_attr( $product_id ); ?>"/>
        <input type="hidden" name="product_id" class="vi-wcaio-product_id" value="<?php echo esc_attr( $product_id ); ?>"/>
		<?php
	}

	public function viwcaio_wp_enqueue_scripts() {
		if ( WP_DEBUG ) {
			wp_enqueue_style( 'vi-wcaio-frontend', VIWCAIO_CART_ALL_IN_ONE_CSS . 'frontend.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
		} else {
			wp_enqueue_style( 'vi-wcaio-frontend', VIWCAIO_CART_ALL_IN_ONE_CSS . 'frontend.min.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
		}
		wp_add_inline_style( 'vi-wcaio-frontend', wp_unslash( $this->settings->get_params( 'custom_css' ) ) );
	}

	public static function add_ajax_events() {
		$ajax_events = array(
			'viwcaio_change_quantity'    => true,
			'viwcaio_remove_item'        => true,
			'viwcaio_apply_coupon'       => true,
			'viwcaio_remove_coupon'      => true,
			'viwcaio_add_to_cart'        => true,
			'viwcaio_show_variation'     => true,
			'viwcaio_get_checkout_form'  => true,
			'viwcaio_get_cart_fragments' => true,
		);
		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_woocommerce_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_woocommerce_' . $ajax_event, array( __CLASS__, $ajax_event ) );
				// WC AJAX can be used for frontend ajax requests
				add_action( 'wc_ajax_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			}
		}
	}

	public static function viwcaio_get_checkout_form() {
		$viwcaio_get_checkout = isset( $_POST['viwcaio_get_checkout'] ) ? sanitize_text_field( $_POST['viwcaio_get_checkout'] ) : '';
		$result               = array(
			'status' => '',
			'html'   => '',
		);
		if ( $viwcaio_get_checkout ) {
			$param                = isset( $_POST['viwcaio_checkout_form'] ) ? wc_clean( $_POST['viwcaio_checkout_form'] ) : array();
			$param['language']    = isset( $_POST['viwcaio_language'] ) ? sanitize_text_field( $_POST['viwcaio_language'] ) : '';
			$param['is_paysoncheckout']    = isset( $_POST['is_paysoncheckout'] ) ? sanitize_text_field( $_POST['is_paysoncheckout'] ) : '';
			$result['status'] = 'success';
			ob_start();
			wc_get_template( 'sc-form-checkout.php',
				array( 'checkout' => WC()->checkout(), 'param' => $param ),
				'woocommerce-cart-all-in-one' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'sidebar-cart' . DIRECTORY_SEPARATOR,
				VIWCAIO_CART_ALL_IN_ONE_TEMPLATES . 'sidebar-cart' . DIRECTORY_SEPARATOR );
			$html                                = ob_get_clean();
			$result['html']                      = $html;
			$result['update_order_review_nonce'] = wp_create_nonce( 'update-order-review' );
		}
		wp_send_json( $result );
		die();
	}

	public static function viwcaio_add_to_cart() {
		$notices = WC()->session->get( 'wc_notices', array() );
		if ( ! empty( $notices['error'] ) ) {
			wp_send_json( array( 'error' => true ) );
		}
		$settings = new VIWCAIO_CART_ALL_IN_ONE_DATA();
		if ( ! empty( $notices ) && ! $settings->get_params( 'ajax_atc_notice' ) ) {
			unset( $notices['success'] );
			WC()->session->set( 'wc_notices', $notices );
		}
		WC_AJAX::get_refreshed_fragments();
		die();
	}

	public static function viwcaio_show_variation() {
		$product_id = isset( $_POST['product_id'] ) ? sanitize_text_field( $_POST['product_id'] ) : '';
		$result     = array(
			'status' => '',
			'url'    => '',
			'html'   => '',
		);
		if ( $product_id && $product_t = wc_get_product( $product_id ) ) {
			if ( $product_t->is_type( 'variable' ) ) {
				global $product;
				$product = $product_t;
				if ( $product_t->is_in_stock() ) {
					ob_start();
					wc_get_template( 'variation-popup.php', array(),
						'woocommerce-cart-all-in-one' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR,
						VIWCAIO_CART_ALL_IN_ONE_TEMPLATES );
					$html             = ob_get_clean();
					$result['status'] = 'success';
					$result['html']   = $html;
					wp_send_json( $result );
				} else {
					$result['status'] = 'error';
					$result['url']    = esc_attr( esc_url( $product->get_permalink() ) );
					wp_send_json( $result );
				}
			}
		}
		wp_send_json( $result );
		die();
	}

	public static function product_get_quantity_html( $args = array() ) {
		if ( empty( $args ) ) {
			return '';
		}
		extract( $args );
		ob_start();
		if ( $max_value && $min_value === $max_value ) {
			?>
            <input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" name="<?php echo esc_attr( $input_name ); ?>"
                   value="<?php echo esc_attr( $min_value ); ?>">
			<?php
		} else {
			do_action( 'woocommerce_before_quantity_input_field' );
			?>
            <div class="vi-wcaio-va-change-qty vi-wcaio-va-qty-subtract">
                <span class="viwcaio_nav_icons-pre"></span>
            </div>
            <div>
                <input type="number"
                       title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce-cart-all-in-one' ); ?>"
                       placeholder="<?php echo esc_attr( $placeholder ); ?>"
                       id="<?php echo esc_attr( $input_id ); ?>"
                       class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
                       name="<?php echo esc_attr( $input_name ); ?>"
                       inputmode="<?php echo esc_attr( $inputmode ); ?>"
                       min="<?php echo esc_attr( $min_value ); ?>"
                       max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
                       step="<?php echo esc_attr( $step ); ?>"
                       value="<?php echo esc_attr( $input_value ); ?>">
            </div>
            <div class="vi-wcaio-va-change-qty vi-wcaio-va-qty-add">
                <span class="viwcaio_nav_icons-next"></span>
            </div>
			<?php
			do_action( 'woocommerce_after_quantity_input_field' );
		}
		$html = ob_get_clean();
		return $html;
	}

	public static function viwcaio_apply_coupon() {
		$coupon_code = isset( $_POST['vi_wcaio_coupon_code'] ) ? sanitize_text_field( $_POST['vi_wcaio_coupon_code'] ) : '';
		if ( $coupon_code ) {
			WC()->cart->add_discount( wc_format_coupon_code( $coupon_code ) );
		} else {
			wc_add_notice( WC_Coupon::get_generic_coupon_error( WC_Coupon::E_WC_COUPON_PLEASE_ENTER ), 'error' );
		}
		wp_send_json( array( wc_print_notices( true ) ) );
		die();
	}

	public static function viwcaio_remove_coupon() {
		$coupon = isset( $_POST['vi_wcaio_coupon_code'] ) ? wc_format_coupon_code( wp_unslash( $_POST['vi_wcaio_coupon_code'] ) ) : '';
		if ( empty( $coupon ) ) {
			wc_add_notice( esc_html__( 'Sorry there was a problem removing this coupon.', 'woocommerce-cart-all-in-one' ), 'error' );
		} else {
			WC()->cart->remove_coupon( $coupon );
			wc_add_notice( esc_html__( 'Coupon has been removed.', 'woocommerce-cart-all-in-one' ) );
		}
		wp_send_json( array( wc_print_notices( true ) ) );
		die();
	}

	public static function viwcaio_change_quantity() {
		$viwcaio_cart = isset( $_POST['viwcaio_cart'] ) ? wc_clean( $_POST['viwcaio_cart'] ) : '';
		if ( empty( $viwcaio_cart ) ) {
			wp_send_json( array( 'error' => true ) );
		}
		$cart = WC()->cart->get_cart();
		foreach ( $viwcaio_cart as $cart_item_key => $qty ) {
			$qty = $qty['qty'] ?? 0;
			$qty = $qty < 0 ? 0 : $qty;
			if ( '' === $qty || $qty == ( $cart[ $cart_item_key ]['quantity'] ?? '' ) ) {
				continue;
			}
			// Sanitize.
			$qty = apply_filters( 'woocommerce_stock_amount_cart_item', wc_stock_amount( preg_replace( '/[^0-9\.]/', '', $qty ) ), $cart_item_key );
			WC()->cart->set_quantity( strval( $cart_item_key ), $qty, true );
		}
		WC()->cart->calculate_totals();
		WC_AJAX:: get_refreshed_fragments();
		die();
	}

	public static function viwcaio_remove_item() {
		$cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( $_POST['cart_item_key'] ) : '';
		if ( $cart_item_key && false !== WC()->cart->remove_cart_item( $cart_item_key ) ) {
			WC_AJAX::get_refreshed_fragments();
		} else {
			wp_send_json_error();
		}
		die();
	}

	public static function viwcaio_get_cart_fragments() {
		if ( ! isset( $_REQUEST['viwcaio_get_cart_fragments'] ) ) {
			wp_die();
		}
		$fragments = self::viwcaio_woocommerce_add_to_cart_fragments( array() );
		wp_send_json( array( 'fragments' => $fragments ) );
		die();
	}

	public static function viwcaio_woocommerce_add_to_cart_fragments( $fragments ) {
		$wc_cart              = WC()->cart;
		$cart_total           = $wc_cart->get_total();
		$cart_subtotal        = $wc_cart->get_cart_subtotal();
		$cart_content_count   = $wc_cart->get_cart_contents_count();
		$settings             = new VIWCAIO_CART_ALL_IN_ONE_DATA();
		$mc_display_style     = $settings->get_params( 'mc_display_style' );
		$mc_cart_total        = $settings->get_params( 'mc_cart_total' );
		$sc_footer_cart_total = $settings->get_params( 'sc_footer_cart_total' );
		$sc_icon_count_type   = $settings->get_params( 'sc_icon_count_type' );
		$sc_icon_style        = $settings->get_params( 'sc_icon_style' );
		ob_start();
		?>
        <span class="vi-wcaio-menu-cart-text-wrap">
	        <?php
	        VIWCAIO_CART_ALL_IN_ONE_Frontend_Menu_Cart::get_menu_cart_text( $mc_display_style, $mc_cart_total === 'total' ? $cart_total : $cart_subtotal, $cart_content_count );
	        ?>
		</span>
		<?php
		$menu_text = ob_get_clean();
		ob_start();
		?>
        <ul class="vi-wcaio-sidebar-cart-products">
			<?php
			VIWCAIO_CART_ALL_IN_ONE_Frontend_Sidebar_Cart_Content::get_sidebar_content_pd_html( $wc_cart );
			?>
        </ul>
		<?php
		$sidebar_content_pd_html = ob_get_clean();
		if ( in_array( $sc_icon_style, [ '1', '2', '3', '5' ] ) ) {
			if ( $sc_icon_style === '3' && $sc_icon_count_type === 'cart_subtotal' ) {
				$sc_icon_count_type = 'pd_count';
			}
			ob_start();
			?>
            <div class="vi-wcaio-sidebar-cart-count">
				<?php
				switch ( $sc_icon_count_type ) {
					case 'item_count':
						echo wp_kses_post( count( $wc_cart->get_cart() ) );
						break;
					case 'cart_subtotal':
						echo wp_kses_post( $cart_subtotal );
						break;
					default:
						echo wp_kses_post( $cart_content_count );
				}
				?>
            </div>
			<?php
			$sidebar_count_pd_html = ob_get_clean();
		}
		ob_start();
		?>
        <div class="vi-wcaio-sidebar-cart-footer-cart_total1">
			<?php echo $sc_footer_cart_total === 'total' ? wp_kses_post( $cart_total ) : wp_kses_post( $cart_subtotal ); ?>
        </div>
		<?php
		$sidebar_cart_total_html = ob_get_clean();
		if ( $settings->get_params( 'sc_header_coupon_enable' ) ) {
			$applied_coupons      = wc_coupons_enabled() ? $wc_cart->get_applied_coupons() : array();
			$applied_coupons_html = VIWCAIO_CART_ALL_IN_ONE_Frontend_Sidebar_Cart_Content::get_sc_footer_coupon_html( $applied_coupons );
			ob_start();
			?>
            <div class="vi-wcaio-sidebar-cart-footer-coupons<?php echo esc_attr( $applied_coupons_html ? '' : ' vi-wcaio-disabled' ); ?>">
                <div class="vi-wcaio-sidebar-cart-footer-coupons1">
					<?php echo wp_kses( $applied_coupons_html, VIWCAIO_CART_ALL_IN_ONE_DATA::extend_post_allowed_html() ); ?>
                </div>
            </div>
			<?php
			$sidebar_coupons_html = ob_get_clean();
		}
		if ( in_array( $sc_footer_pd_plus = $settings->get_params( 'sc_footer_pd_plus' ), [ 'product_upsell', 'cross_sell' ] ) ) {
			$fragments['.vi-wcaio-sidebar-cart-footer-pd-wrap-wrap'] = VIWCAIO_CART_ALL_IN_ONE_Frontend_Sidebar_Cart_Content::get_product_plus( true, $sc_footer_pd_plus );
		}
		$fragments['.vi-wcaio-menu-cart-text-wrap'] = $menu_text;
		if ( isset( $sidebar_count_pd_html ) ) {
			$fragments['.vi-wcaio-sidebar-cart-count'] = $sidebar_count_pd_html;
		}
		if ( isset( $sidebar_coupons_html ) ) {
			$fragments['.vi-wcaio-sidebar-cart-footer-coupons'] = $sidebar_coupons_html;
		}
		$fragments['.vi-wcaio-sidebar-cart-footer-cart_total1'] = $sidebar_cart_total_html;
		$fragments['.vi-wcaio-sidebar-cart-products']           = $sidebar_content_pd_html;
		return $fragments;
	}

	public static function viwcaio_quantity_input_args( $args = array(), $product = null ) {
		if ( is_null( $product ) ) {
			$product = $GLOBALS['product'];
		}
		$defaults = array(
			'input_id'     => uniqid( 'quantity_' ),
			'input_name'   => 'quantity',
			'input_value'  => '1',
			'classes'      => apply_filters( 'woocommerce_quantity_input_classes', array(
				'input-text',
				'qty',
				'text'
			), $product ),
			'max_value'    => apply_filters( 'woocommerce_quantity_input_max', - 1, $product ),
			'min_value'    => apply_filters( 'woocommerce_quantity_input_min', 0, $product ),
			'step'         => apply_filters( 'woocommerce_quantity_input_step', 1, $product ),
			'pattern'      => apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
			'inputmode'    => apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
			'product_name' => $product ? $product->get_title() : '',
			'placeholder'  => apply_filters( 'woocommerce_quantity_input_placeholder', '', $product ),
		);
		$args     = apply_filters( 'woocommerce_quantity_input_args', wp_parse_args( $args, $defaults ), $product );
		// Apply sanity to min/max args - min cannot be lower than 0.
		$args['min_value'] = max( $args['min_value'], 0 );
		$args['max_value'] = 0 < $args['max_value'] ? $args['max_value'] : '';
		// Max cannot be lower than min if defined.
		if ( '' !== $args['max_value'] && $args['max_value'] < $args['min_value'] ) {
			$args['max_value'] = $args['min_value'];
		}
		return $args;
	}

	public function viwcaio_change_sc_pd_price_style() {
		$result = array(
			'status'  => '',
			'message' => '',
		);
		$style  = isset( $_POST['style'] ) ? sanitize_text_field( wp_unslash( $_POST['style'] ) ) : '';
		if ( $style ) {
			ob_start();
			VIWCAIO_CART_ALL_IN_ONE_Frontend_Sidebar_Cart_Content::get_sidebar_content_pd_html( WC()->cart, $style );
			$html              = ob_get_clean();
			$result['status']  = $html ? 'success' : '';
			$result['message'] = $html;
		}
		wp_send_json( $result );
	}

	public function viwcaio_get_sc_footer_message_html() {
		$result         = array(
			'status'  => '',
			'message' => '',
		);
		$custom_message = isset( $_POST['custom_message'] ) ? wp_kses_post( wp_unslash( $_POST['custom_message'] ) ) : '';
		if ( $custom_message ) {
			ob_start();
			VIWCAIO_CART_ALL_IN_ONE_Frontend_Sidebar_Cart_Content::get_sc_footer_message_html( $custom_message );
			$html              = ob_get_clean();
			$result['status']  = $html ? 'success' : '';
			$result['message'] = $html;
		}
		wp_send_json( $result );
	}

	public function viwcaio_get_sc_footer_pd_plus_html() {
		$result = array(
			'status'  => '',
			'message' => '',
		);
		$type   = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';
		if ( $type ) {
			$html              = VIWCAIO_CART_ALL_IN_ONE_Frontend_Sidebar_Cart_Content::get_product_plus( '', $type );
			$result['status']  = $html ? 'success' : '';
			$result['message'] = $html;
		}
		wp_send_json( $result );
	}

	public function viwcaio_get_product_url() {
		$result     = array(
			'status'  => '',
			'message' => '',
		);
		$product_id = isset( $_POST['product_id'] ) ? sanitize_text_field( $_POST['product_id'] ) : '';
		if ( ! $product_id ) {
			$args      = array(
				'post_type'      => 'product',
				'post_status'    => 'any',
				'posts_per_page' => 1,
			);
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$product_id = get_the_ID();
					$product    = wc_get_product( $product_id );
					if ( $product ) {
						$url = $product->get_permalink();
						break;
					}
				}
			}
			wp_reset_postdata();
		} elseif ( $product = wc_get_product( $product_id ) ) {
			$url = $product->get_permalink();
		}
		if ( ! empty( $url ) ) {
			$result['status']  = 'success';
			$result['message'] = $url;
		}
		wp_send_json( $result );
	}

	public function viwcaio_get_menu_cart_text() {
		$result          = array(
			'status'  => '',
			'message' => '',
		);
		$display_type    = isset( $_POST['display_type'] ) ? sanitize_text_field( $_POST['display_type'] ) : '';
		$cart_total_type = isset( $_POST['cart_total_type'] ) ? sanitize_text_field( $_POST['cart_total_type'] ) : '';
		if ( $display_type && $cart_total_type ) {
			if ( isset( WC()->cart ) ) {
				$wc_cart            = WC()->cart;
				$cart_content_count = $wc_cart->get_cart_contents_count();
				$cart_total         = $cart_total_type === 'total' ? $wc_cart->get_total() : $wc_cart->get_cart_subtotal();
			} else {
				$cart_total = $cart_content_count = 0;
			}
			ob_start();
			VIWCAIO_CART_ALL_IN_ONE_Frontend_Menu_Cart::get_menu_cart_text( $display_type, $cart_total, $cart_content_count );
			$html = ob_get_clean();
			if ( $html ) {
				$result['status']  = 'success';
				$result['message'] = $html;
			}
		}
		wp_send_json( $result );
	}

	public function viwcaio_get_class_icon() {
		$result   = array(
			'status'  => '',
			'message' => '',
		);
		$settings = new VIWCAIO_CART_ALL_IN_ONE_DATA();
		$icon_id  = isset( $_POST['icon_id'] ) ? sanitize_text_field( $_POST['icon_id'] ) : '';
		$type     = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';
		if ( is_numeric( $icon_id ) && $type && $class = $settings->get_class_icon( $icon_id, $type ) ) {
			$result['status']  = 'success';
			$result['message'] = $class;
		}
		wp_send_json( $result );
	}

	public function viwcaio_recently_viewed() {
		$check = false;
		if ( $this->settings->enable( 'vp_' ) ) {
			$check = true;
		}
		if ( $this->settings->enable( 'sc_' ) && $this->settings->get_params( 'sc_footer_pd_plus' ) === 'viewed_product' ) {
			$check = true;
		}
		if ( ! $check ) {
			return;
		}
		if ( ! is_active_widget( false, false, 'woocommerce_recently_viewed_products', true ) && is_single() && is_product() ) {
			$product_id        = get_the_ID();
			$recent_viewed_ids = ! empty( $_COOKIE['viwcaio_recently_viewed'] ) ? explode( '|', wp_unslash( $_COOKIE['viwcaio_recently_viewed'] ) ) : array();
			$key               = array_search( $product_id, $recent_viewed_ids );
			if ( $key !== false ) {
				unset( $recent_viewed_ids[ $key ] );
			}
			$recent_viewed_ids[] = $product_id;
			if ( count( $recent_viewed_ids ) > 15 ) {
				array_shift( $recent_viewed_ids );
			}
			$recent_viewed_ids = implode( '|', $recent_viewed_ids );
			wc_setcookie( 'viwcaio_recently_viewed', $recent_viewed_ids );
		}
	}

	public static function add_inline_style( $element, $name, $style, $suffix = '' ) {
		if ( ! $element || ! is_array( $element ) ) {
			return '';
		}
		$settings = new VIWCAIO_CART_ALL_IN_ONE_DATA();
		$element  = implode( ',', $element );
		$return   = $element . '{';
		if ( is_array( $name ) && count( $name ) ) {
			foreach ( $name as $key => $value ) {
				$get_value  = $settings->get_params( $value );
				$get_suffix = $suffix[ $key ] ?? '';
				$return     .= $style[ $key ] . ':' . $get_value . $get_suffix . ';';
			}
		}
		$return .= '}';
		return $return;
	}
}