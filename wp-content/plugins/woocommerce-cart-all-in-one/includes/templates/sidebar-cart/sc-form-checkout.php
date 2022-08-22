<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$settings          = new VIWCAIO_CART_ALL_IN_ONE_DATA();
$is_customize      = is_customize_preview();
$login_step_enable = ! ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) && $settings->get_params( 'sc_checkout_login' );
$stop_at_login     = ! $is_customize && ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in();
$login_title       = $skip_login_title = '';
if ( $login_step_enable ) {
	$login_title      = apply_filters( 'vi-wcaio-checkout-login-title', esc_html__( 'Login', 'woocommerce-cart-all-in-one' ) );
	$skip_login_title = apply_filters( 'vi-wcaio-checkout-skip_login-title', esc_html__( 'Skip Login', 'woocommerce-cart-all-in-one' ) );
}
if (isset($param['is_paysoncheckout'])) {
	$is_paysoncheckout = $param['is_paysoncheckout'];
}else{
//	$is_paysoncheckout = class_exists( 'PaysonCheckout_For_WooCommerce_Templates' ) && ( 'paysoncheckout' === WC()->session->get( 'chosen_payment_method' ) || array_search( 'paysoncheckout', WC()->payment_gateways()->get_available_payment_gateways() )===0 );
	$is_paysoncheckout = class_exists( 'PaysonCheckout_For_WooCommerce_Templates' ) && array_key_exists( 'paysoncheckout', WC()->payment_gateways()->get_available_payment_gateways() );
}
?>
<div class="vi-wcaio-checkout-step-wrap woocommerce woocommerce-checkout">
	<?php
	if ( ! $stop_at_login ) {
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
		remove_action( 'woocommerce_before_checkout_form', 'neobeat_add_main_woo_page_holder', 5 );
		remove_action( 'woocommerce_after_checkout_form', 'neobeat_add_main_woo_page_holder_end', 20 );
		global $avada_woocommerce;
		if ( $avada_woocommerce ) {
			remove_action( 'woocommerce_before_checkout_form', array( $avada_woocommerce, 'checkout_coupon_form' ), 10 );
			remove_action( 'woocommerce_before_checkout_form', array( $avada_woocommerce, 'avada_top_user_container' ), 1 );
			remove_action( 'woocommerce_before_checkout_form', array( $avada_woocommerce, 'before_checkout_form' ), 10 );
			remove_action( 'woocommerce_before_checkout_form', array( $avada_woocommerce, 'before_checkout_form' ), 10 );
			remove_action( 'woocommerce_after_checkout_form', array( $avada_woocommerce, 'after_checkout_form' ), 10 );
			remove_action( 'woocommerce_checkout_before_customer_details', array( $avada_woocommerce, 'checkout_before_customer_details' ), 10 );
			remove_action( 'woocommerce_checkout_after_customer_details', array( $avada_woocommerce, 'checkout_after_customer_details' ), 10 );
			remove_action( 'woocommerce_checkout_after_order_review', array( $avada_woocommerce, 'checkout_after_order_review' ), 20 );
			remove_action( 'woocommerce_checkout_billing', array( $avada_woocommerce, 'checkout_billing' ), 20 );
			remove_action( 'woocommerce_checkout_shipping', array( $avada_woocommerce, 'checkout_shipping' ), 20 );
		}
		$viwcaio_checkout_class = 'VIWCAIO_CART_ALL_IN_ONE_Frontend_Form_Checkout';
		if ( class_exists( 'WooCommerce_Germanized' ) ) {
			// Germanized for WooCommerce plugin.
			$swap_payment_review = true;
			remove_action( 'vi_wcaio_checkout_step_order_review', array( $viwcaio_checkout_class, 'viwcaio_checkout_step_order_review' ) );
			if ( wp_doing_ajax() ) {
				add_action( 'vi_wcaio_checkout_step_order_review', 'woocommerce_gzd_template_render_checkout_checkboxes' );
			}
			add_action( 'vi_wcaio_checkout_step_order_review', array( $viwcaio_checkout_class, 'viwcaio_checkout_step_order_review_germanized' ) );
		} elseif ( class_exists( 'Woocommerce_German_Market' ) ) {
			// WooCommerce German Market plugin
			$swap_payment_review = true;
		}
		if ( $is_paysoncheckout ) {
			remove_action( 'vi_wcaio_checkout_step_order_review', array( $viwcaio_checkout_class, 'viwcaio_checkout_step_order_review' ) );
			remove_action( 'vi_wcaio_checkout_step_payment', array( $viwcaio_checkout_class, 'viwcaio_checkout_step_payment' ) );
			add_action( 'vi_wcaio_checkout_step_order_review', array( $viwcaio_checkout_class, 'viwcaio_checkout_step_order_review_paysoncheckout' ) );
		}
		$swap_payment_review = apply_filters( 'vi-wcaio-swap-payment-review', $swap_payment_review ?? false );
		$steps               = array(
			'billing'      => array(
				'priority' => 0,
			),
			'shipping'     => array(
				'priority' => 1,
			),
			'order_review' => array(
				'priority' => 2,
			),
			'payment'      => array(
				'priority' => 3,
			),
		);
		if ( $is_paysoncheckout ) {
			$param['sc_checkout_order_payment'] = true;
		}
		$language            = $language ?? $param['language'] ?? '';
		if ( ! $checkout->get_checkout_fields() || $is_paysoncheckout ) {
			unset( $steps['billing'], $steps['shipping'] );
		} elseif ( $param['sc_checkout_billing_shipping'] ?? $settings->get_params( 'sc_checkout_billing_shipping' ) ) {
			unset( $steps['shipping'] );
			$steps['billing']['elements'] = array( 'billing', 'shipping' );
			$steps['billing']['class']    = 'vi-wcaio-checkout-step-billing-shipping';
			$steps['billing']['title']    = $param['sc_checkout_billing_shipping_title'] ?? $settings->get_params( 'sc_checkout_billing_shipping_title', $language );
		} else {
			$steps['billing']['title']  = $param['sc_checkout_billing_title'] ?? $settings->get_params( 'sc_checkout_billing_title', $language );
			$steps['shipping']['title'] = $param['sc_checkout_shipping_title'] ?? $settings->get_params( 'sc_checkout_shipping_title', $language );
		}
		if ( $param['sc_checkout_order_payment'] ?? $settings->get_params( 'sc_checkout_order_payment' ) ) {
			unset( $steps['payment'] );
			$steps['order_review']['elements'] = $swap_payment_review ? array( 'payment', 'order_review' ) : array( 'order_review', 'payment' );
			$steps['order_review']['class']    = 'vi-wcaio-checkout-step-order-payment';
			$steps['order_review']['title']    = $param['sc_checkout_order_payment_title'] ?? $settings->get_params( 'sc_checkout_order_payment_title', $language );
		} else {
			$steps['order_review']['title'] = $param['sc_checkout_order_review_title'] ?? $settings->get_params( 'sc_checkout_order_review_title' );
			$steps['payment']['title']      = $param['sc_checkout_payment_title'] ?? $settings->get_params( 'sc_checkout_payment_title', $language );
			if ( $swap_payment_review ) {
				$payment_ps                        = $steps['payment']['priority'];
				$steps['payment']['priority']      = $steps['order_review']['priority'];
				$steps['order_review']['priority'] = $payment_ps;
			}
		}
		$steps = apply_filters( 'vi-wcaio-checkout-steps', $steps );
		uasort( $steps, 'viwcaio_sort_array' );
		if ( $settings->get_params( 'sc_checkout_nav_bar' ) || $is_customize ) {
			echo sprintf( '<div class="vi-wcaio-checkout-nav-step-wrap%s">', $settings->get_params( 'sc_checkout_nav_bar' ) ? '' : esc_attr( ' vi-wcaio-disabled' ) );
			if ( $login_step_enable ) {
				echo sprintf( '<div class="vi-wcaio-checkout-nav-step" data-step="login" data-step_title="%s" data-next_title="%s"></div>', $login_title, $skip_login_title );
			}
			foreach ( $steps as $step => $value ) {
				echo sprintf( '<div class="vi-wcaio-checkout-nav-step" data-step="%s" data-step_title="%s"></div>', $step, $value['title'] ?? '' );
			}
			echo sprintf( '</div>' );
		}
	}
	if ( $login_step_enable ) {
		?>
        <div class="vi-wcaio-checkout-step vi-wcaio-checkout-step-login" data-step_title="<?php echo esc_attr( $login_title ); ?>" data-next_title="<?php echo esc_attr( $skip_login_title ); ?>">
            <div class="vi-wcaio-checkout-login">
				<?php
				woocommerce_login_form(
					array(
						'message'  => esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'woocommerce-cart-all-in-one' ),
						'redirect' => wc_get_checkout_url(),
						'hidden'   => false,
					)
				);
				?>
            </div>
        </div>
		<?php
	}
	if ( $stop_at_login ) {
		echo sprintf( '<div class="vi-wcaio-checkout-login-notice%s">%s</div>',
			! $login_step_enable ? esc_attr( ' vi-wcaio-checkout-login-notice-warning' ) : '',
			apply_filters( 'woocommerce_checkout_must_be_logged_in_message', esc_html__( 'You must be logged in to checkout.', 'woocommerce-cart-all-in-one' ) )
		);
		echo sprintf( '</div>' );
		return false;
	}
	?>
    <div class="vi-wcaio-checkout-before-checkout-form vi-wcaio-disabled">
	    <?php do_action( 'vi_wcaio_before_checkout_form', $checkout ); ?>
		<?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>
    </div>
	<?php
	printf( '<form name="checkout" method="post" class="checkout woocommerce-checkout" action="%s" enctype="multipart/form-data">', esc_url( wc_get_checkout_url() ) );
	foreach ( $steps as $step => $value ) {
		$class   = array( 'vi-wcaio-checkout-step vi-wcaio-checkout-step-' . $step );
		$class[] = $value['class'] ?? '';
		$class   = trim( implode( ' ', $class ) );
		printf( '<div class="%s" data-step_title="%s">', $class, $value['title'] ?? '' );
		if ( ! empty( $value['elements'] ) ) {
			foreach ( $value['elements'] as $element ) {
				echo sprintf( '<div class="vi-wcaio-checkout-%s">', $element );
				do_action( 'vi_wcaio_checkout_step_' . $element );
				echo sprintf( '</div>' );
			}
		} else {
			do_action( 'vi_wcaio_checkout_step_' . $step );
		}
		printf( '</div>' );
	}
	printf( '</form>' );
	?>
    <div class="vi-wcaio-checkout-after-checkout-form vi-wcaio-disabled">
		<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
    </div>
</div>
