<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class VIWCAIO_CART_ALL_IN_ONE_Frontend_Form_Checkout {
	public function __construct() {
		add_action( 'vi_wcaio_checkout_step_order_review', array( __CLASS__, 'viwcaio_checkout_step_order_review' ) );
		add_action( 'vi_wcaio_checkout_step_payment', array( __CLASS__, 'viwcaio_checkout_step_payment' ) );
		add_action( 'vi_wcaio_checkout_order_review', 'woocommerce_order_review' );
		add_action( 'vi_wcaio_checkout_payment', 'woocommerce_checkout_payment' );
		add_action( 'vi_wcaio_checkout_step_billing', array( __CLASS__, 'viwcaio_checkout_step_billing' ) );
		add_action( 'vi_wcaio_checkout_step_shipping', array( __CLASS__, 'viwcaio_checkout_step_shipping' ) );
	}
	public static function viwcaio_checkout_step_billing() {
		do_action( 'woocommerce_checkout_before_customer_details' );
		do_action( 'woocommerce_checkout_billing' );
	}
	public static function viwcaio_checkout_step_shipping() {
		do_action( 'woocommerce_checkout_shipping' );
		if ( ! class_exists( 'Xtra_Woocommerce' ) ) {
			do_action( 'woocommerce_checkout_after_customer_details' );
		}
	}
	public static function viwcaio_checkout_step_order_review() {
		do_action( 'woocommerce_checkout_before_order_review_heading' );
		do_action( 'woocommerce_checkout_before_order_review' );
		echo sprintf( '<div id="order_review" class="woocommerce-checkout-review-order">' );
		do_action( 'woocommerce_checkout_order_review' );
		do_action( 'vi_wcaio_checkout_order_review' );
		echo sprintf( '</div>' );
	}
	public static function viwcaio_checkout_step_order_review_germanized() {
		do_action( 'woocommerce_checkout_before_order_review_heading' );
		do_action( 'woocommerce_checkout_before_order_review' );
		echo sprintf( '<div id="order_review" class="woocommerce-checkout-review-order">' );
		do_action( 'vi_wcaio_checkout_order_review' );
		if ( wp_doing_ajax() && function_exists( 'woocommerce_gzd_template_order_submit' ) ) {
			woocommerce_gzd_template_order_submit();
		}
		echo sprintf( '</div>' );
	}

	public static function viwcaio_checkout_step_order_review_paysoncheckout() {
		?>
        <div id="pco-wrapper">
            <div id="pco-order-review">
				<?php do_action( 'pco_wc_before_order_review' ); ?>
				<?php woocommerce_order_review(); ?>
				<?php do_action( 'pco_wc_after_order_review' ); ?>
            </div>
            <div id="pco-iframe">
				<?php do_action( 'pco_wc_before_snippet' ); ?>
				<?php pco_wc_show_snippet(); ?>
				<?php do_action( 'pco_wc_after_snippet' ); ?>
            </div>
        </div>
		<?php do_action( 'pco_wc_after_wrapper' ); ?>
		<?php
	}
	public static function viwcaio_checkout_step_payment() {
		do_action( 'vi_wcaio_checkout_payment' );
		if ( ! class_exists( 'Xtra_Woocommerce' ) ) {
			do_action( 'woocommerce_checkout_after_order_review' );
		}
	}
	public static function viwcaio_is_not_checkout( $result ) {
		return apply_filters( 'viwcaio_is_not_checkout', false );
	}
	public static function viwcaio_is_checkout( $result ) {
		return apply_filters( 'viwcaio_is_checkout', true );
	}
	public static function viwcaio_wc_square_credit_card_payment_form_js_args( $args, $payment ) {
		wc_enqueue_js( sprintf( 'window.viwcaio_wc_%s_payment_form_handler = %s ;', esc_js( $payment->get_gateway()->get_id() ), json_encode( $args ) ) );
		return $args;
	}
	public static function viwcaio_woocommerce_update_order_review_fragments( $fragments ) {
		$wc_cart = WC()->cart;
		if ( ! empty( $_REQUEST['viwcaio_need_shipping'] ) && $wc_cart->needs_shipping_address() ) {
			ob_start();
			do_action( 'vi_wcaio_checkout_step_shipping' );
			$shipping = ob_get_clean();
			if ( ! $shipping ) {
				ob_start();
				wc_get_template( 'checkout/form-shipping.php', array( 'checkout' => WC()->checkout() ) );
				$shipping = ob_get_clean();
			}
			$fragments['.vi-wcaio-checkout-shipping'] = '<div class="vi-wcaio-checkout-shipping vi-wcaio-checkout-shipping-replace">' . $shipping . '</div>';
		}
		if ( ! empty( $_REQUEST['viwcaio_cart_total'] ) ) {
			$cart_total = $wc_cart->get_total();
			ob_start();
			?>
            <div class="vi-wcaio-sidebar-cart-footer-cart_total1">
	            <?php echo wp_kses_post( $cart_total ); ?>
            </div>
			<?php
			$sidebar_cart_total_html                                = ob_get_clean();
			$fragments['.vi-wcaio-sidebar-cart-footer-cart_total1'] = $sidebar_cart_total_html;
		}
		if ( ! empty( $_REQUEST['viwcaio_coupons'] ) ) {
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
			$sidebar_coupons_html                               = ob_get_clean();
			$fragments['.vi-wcaio-sidebar-cart-footer-coupons'] = $sidebar_coupons_html;
		}
		return $fragments;
	}

	public static function viwcuf_ob_get_action( $hook ) {
		if ( isset( $_REQUEST['viwcaio_get_checkout'] ) ) {
			if ( $hook === 'woocommerce_review_order_before_payment' ) {
				$hook = 'vi_wcaio_checkout_order_review';
			} elseif ( $hook === 'woocommerce_review_order_after_payment' ) {
				$hook = class_exists( 'Xtra_Woocommerce' ) ? 'vi_wcaio_checkout_step_payment' : 'woocommerce_checkout_after_order_review';
			}
		}
		return $hook;
	}
}