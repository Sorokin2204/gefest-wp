<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$class        = array(
	'vi-wcaio-sidebar-cart-content-close',
	'vi-wcaio-sidebar-cart-content-wrap',
);
$is_customize = $sidebar_cart::is_customize_preview();
$class[]      = $sidebar_cart::$sc_checkout || $is_customize ? 'vi-wcaio-sidebar-cart-content-wrap-checkout' : '';
$class[]      = $is_customize ? 'vi-wcaio-sidebar-cart-content-wrap-customize' : '';
$class[]      = is_user_logged_in() ? 'vi-wcaio-sidebar-cart-content-wrap-logged' : '';
$class        = trim( implode( ' ', $class ) );
$wc_cart      = WC()->cart;
do_action( 'vi_wcaio_before_mini_cart' );
$language              = $sidebar_cart::get_language();
$last_applied_coupon   = '';
$sc_footer_coupon      = $sidebar_cart->get_params( 'sc_footer_coupon' );
$sc_footer_coupon_html = '';
if ( wc_coupons_enabled() && $wc_cart && ! $wc_cart->is_empty() ) {
	$applied_coupons = method_exists( $wc_cart, 'get_applied_coupons' ) ? $wc_cart->get_applied_coupons() : '';
	if ( ! empty( $applied_coupons ) ) {
		$last_applied_coupon = $applied_coupons[ count( $applied_coupons ) - 1 ];
	}
}
?>
    <div class="<?php echo esc_attr( $class ); ?>">
        <div class="vi-wcaio-sidebar-cart-header-wrap">
            <div class="vi-wcaio-sidebar-cart-header-title-wrap">
				<?php echo wp_kses_post( $sidebar_cart->get_params( 'sc_header_title', $language ) ); ?>
            </div>
			<?php
			if ( $is_customize || $sidebar_cart::$settings->get_params( 'sc_header_coupon_enable' ) ) {
				if ($last_applied_coupon){
					$last_applied_coupon_t= $last_applied_coupon;
				}else{
					$last_applied_coupon_t= esc_attr__( 'Coupon code', 'woocommerce-cart-all-in-one'  );
				}
				?>
                <div class="vi-wcaio-sidebar-cart-header-coupon-wrap">
                    <input type="text" name="coupon_code" id="coupon_code" class="vi-wcaio-coupon-code"
                           placeholder="<?php echo esc_attr( $last_applied_coupon_t ); ?>">
                    <button type="submit" class="button vi-wcaio-bt-coupon-code" name="apply_coupon">
						<?php printf( '%s', apply_filters( 'vi_wcaio_get_bt_coupon_text', esc_html__( 'Apply', 'woocommerce-cart-all-in-one' ) ) ); ?>
                    </button>
                </div>
				<?php
			}
			?>
            <div class="vi-wcaio-sidebar-cart-close-wrap">
                <i class="vi_wcaio_cart_icon-clear-button"></i>
            </div>
        </div>
        <div class="vi-wcaio-sidebar-cart-content-wrap1 vi-wcaio-sidebar-cart-products-wrap">
			<?php
			do_action( 'vi_wcaio_before_mini_cart_content' );
			?>
            <ul class="vi-wcaio-sidebar-cart-products">
				<?php
				$sidebar_cart::get_sidebar_content_pd_html( $wc_cart );
				?>
            </ul>
			<?php
			do_action( 'vi_wcaio_after_mini_cart_content' );
			?>
        </div>
		<?php
		if ( $sidebar_cart::$sc_checkout || $is_customize ) {
			$sc_checkout_keyboard_nav = $sidebar_cart->get_params( 'sc_checkout_keyboard_nav' ) ?: '';
			$sc_checkout_class        = array( 'vi-wcaio-sidebar-cart-content-wrap1 vi-wcaio-sidebar-cart-checkout-wrap vi-wcaio-disabled' );
			if ( class_exists( 'THWCFE_Public_Checkout' ) ) {
				$sc_checkout_class[] = 'vi-wcaio-sidebar-cart-checkout-wrap-refresh';
			}
			$sc_checkout_class = trim( implode( ' ', $sc_checkout_class ) );
			?>
            <div class="<?php echo esc_attr( $sc_checkout_class ); ?>" data-use_keyboard="<?php echo esc_attr( $sc_checkout_keyboard_nav ); ?>">
				<?php
				if ( $is_customize || ! WC()->cart->is_empty() ) {
					wc_get_template( 'sc-form-checkout.php', array( 'checkout' => WC()->checkout(), 'language' => $language ),
						'woocommerce-cart-all-in-one' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'sidebar-cart' . DIRECTORY_SEPARATOR,
						VIWCAIO_CART_ALL_IN_ONE_TEMPLATES . 'sidebar-cart' . DIRECTORY_SEPARATOR );
				}else{
				    ?>
                    <div class="vi-wcaio-checkout-step-wrap woocommerce woocommerce-checkout">
                        <div class="vi-wcaio-checkout-before-checkout-form vi-wcaio-disabled"></div>
                        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
                            <?php
                            if (function_exists('stripe_wc')){
	                            $available_payments= WC()->payment_gateways()->get_available_payment_gateways();
	                            $available_payments_key= array_keys($available_payments);
	                            $stripe_wc_payments =array_intersect($available_payments_key,array_map( 'viwcaio_stripe_wc_payments', stripe_wc()->payment_gateways()));
	                            foreach ($stripe_wc_payments as $payment){
		                            wc_stripe_token_field( $available_payments[$payment] );
                                }
                            }
                            if (is_plugin_active( 'woocommerce-payments/woocommerce-payments.php' )){
	                            $available_payments= $available_payments ?? WC()->payment_gateways()->get_available_payment_gateways();
	                            if (!empty($available_payments['woocommerce_payments'])) {
		                            wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $available_payments['woocommerce_payments'] ) );
	                            }
                            }
                            ?>
                        </form>
                        <div class="vi-wcaio-checkout-after-checkout-form vi-wcaio-disabled"></div>
                    </div>
                    <?php
                }
				?>
            </div>
			<?php
		}
		?>
        <div class="vi-wcaio-sidebar-cart-footer-wrap">
			<?php
			if ( $is_customize || $sc_footer_coupon ) {
				?>
                <div class="vi-wcaio-sidebar-cart-footer vi-wcaio-sidebar-cart-footer-products">
                    <div class="vi-wcaio-sidebar-cart-footer-coupons<?php echo esc_attr( $sc_footer_coupon_html ? '' : ' vi-wcaio-disabled' ); ?>">
                        <div class="vi-wcaio-sidebar-cart-footer-coupons1">
							<?php echo wp_kses( $sc_footer_coupon_html, VIWCAIO_CART_ALL_IN_ONE_DATA::extend_post_allowed_html() ); ?>
                        </div>
                    </div>
                </div>
				<?php
			}
			?>
            <div class="vi-wcaio-sidebar-cart-footer vi-wcaio-sidebar-cart-footer-products">
				<?php
				$sc_footer_cart_total       = $sidebar_cart->get_params( 'sc_footer_cart_total' ) ?: 'total';
				$sc_footer_cart_total_title = $sidebar_cart->get_params( 'sc_footer_cart_total_text', $language );
				$sc_footer_button           = $sidebar_cart->get_params( 'sc_footer_button' ) ?: 'cart';
				if ( $is_customize ) {
					?>
                    <div class="vi-wcaio-sidebar-cart-footer-cart_total-wrap">
                        <div class="vi-wcaio-sidebar-cart-footer-cart_total vi-wcaio-sidebar-cart-footer-total<?php echo esc_attr( $sc_footer_cart_total === 'total' ? '' : ' vi-wcaio-disabled' ); ?>"
                             data-cart_total="<?php echo esc_attr( $cart_total = $wc_cart->get_total() ); ?>">
                            <div class="vi-wcaio-sidebar-cart-footer-cart_total-title"><?php echo wp_kses_post( $sc_footer_cart_total_title ); ?></div>
                            <div class="vi-wcaio-sidebar-cart-footer-cart_total1">
								<?php echo wp_kses_post( $cart_total ); ?>
                            </div>
                        </div>
                        <div class="vi-wcaio-sidebar-cart-footer-cart_total vi-wcaio-sidebar-cart-footer-subtotal<?php echo esc_attr($sc_footer_cart_total !== 'total' ? '' :  ' vi-wcaio-disabled' ); ?>"
                             data-cart_total="<?php echo esc_attr( $cart_subtotal = $wc_cart->get_cart_subtotal() ); ?>">
                            <div class="vi-wcaio-sidebar-cart-footer-cart_total-title"><?php echo wp_kses_post( $sc_footer_cart_total_title ); ?></div>
                            <div class="vi-wcaio-sidebar-cart-footer-cart_total1">
								<?php echo wp_kses_post( $cart_subtotal ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="vi-wcaio-sidebar-cart-footer-action">
                        <button class="vi-wcaio-sidebar-cart-bt vi-wcaio-sidebar-cart-bt-update button">
	                        <?php echo wp_kses_post( apply_filters( 'vi_wcaio_get_bt_update_text', esc_html__( 'Update Cart', 'woocommerce-cart-all-in-one' ) ) ); ?>
                        </button>
                        <a href="<?php echo esc_attr( esc_url( get_permalink( wc_get_page_id( 'cart' ) ) ) ); ?>"
                           class="button vi-wcaio-sidebar-cart-bt vi-wcaio-sidebar-cart-bt-nav vi-wcaio-sidebar-cart-bt-nav-cart<?php echo esc_attr($sc_footer_button === 'cart' ? '' :  ' vi-wcaio-disabled' ); ?>">
							<?php echo wp_kses_post( $sidebar_cart->get_params( 'sc_footer_bt_cart_text', $language ) ); ?>
                        </a>
                        <a href="#" data-href="<?php echo esc_attr( esc_url( get_permalink( wc_get_page_id( 'checkout' ) ) ) ); ?>"
                           class="button vi-wcaio-sidebar-cart-bt vi-wcaio-sidebar-cart-bt-nav vi-wcaio-sidebar-cart-bt-nav-checkout<?php echo esc_attr($sc_footer_button === 'checkout' ? '' :  ' vi-wcaio-disabled' ); ?>">
							<?php echo wp_kses_post( $sidebar_cart->get_params( 'sc_footer_bt_checkout_text', $language ) ); ?>
                        </a>
                    </div>
					<?php
				} else {
					?>
                    <div class="vi-wcaio-sidebar-cart-footer-cart_total-wrap">
                        <div class="vi-wcaio-sidebar-cart-footer-cart_total vi-wcaio-sidebar-cart-footer-<?php echo esc_attr( $sc_footer_cart_total ); ?>">
                            <div class="vi-wcaio-sidebar-cart-footer-cart_total-title"><?php echo wp_kses_post( $sc_footer_cart_total_title ); ?></div>
                            <div class="vi-wcaio-sidebar-cart-footer-cart_total1">
								<?php echo $sc_footer_cart_total === 'total' ? wp_kses_post( $wc_cart->get_cart_total() ) : wp_kses_post( $wc_cart->get_cart_subtotal() ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="vi-wcaio-sidebar-cart-footer-action">
                        <button class="vi-wcaio-sidebar-cart-bt vi-wcaio-sidebar-cart-bt-update vi-wcaio-disabled button">
							<?php echo wp_kses_post( apply_filters( 'vi_wcaio_get_bt_update_text', __( 'Update Cart', 'woocommerce-cart-all-in-one' ) ) ); ?>
                        </button>
						<?php
						if ( $sidebar_cart::$sc_checkout ) {
							?>
                            <button class="button vi-wcaio-sidebar-cart-bt vi-wcaio-sidebar-cart-bt-nav vi-wcaio-sidebar-cart-bt-nav-checkout">
								<?php echo wp_kses_post( $sidebar_cart::$settings->get_params( 'sc_footer_bt_checkout_text', $language ) ); ?>
                            </button>
							<?php
						} else {
							?>
                            <a href="<?php echo esc_attr( esc_url( get_permalink( wc_get_page_id( $sc_footer_button ) ) ) ); ?>"
                               class="button vi-wcaio-sidebar-cart-bt vi-wcaio-sidebar-cart-bt-nav vi-wcaio-sidebar-cart-bt-nav-<?php echo esc_attr( $sc_footer_button ); ?>">
								<?php echo wp_kses_post( $sidebar_cart::$settings->get_params( 'sc_footer_bt_' . $sc_footer_button . '_text', $language ) ); ?>
                            </a>
							<?php
						}
						?>
                    </div>
					<?php
				}
				?>
            </div>
			<?php
			if ( $sidebar_cart::$sc_checkout || $is_customize ) {
				$place_order_text = $sidebar_cart->get_params( 'sc_checkout_bt_place_order_title', $language );
				$bt_next_text     = $sidebar_cart->get_params( 'sc_checkout_bt_next_title', $language );
				$bt_pre_text      = $sidebar_cart->get_params( 'sc_checkout_bt_pre_title', $language );
				?>
                <div class="vi-wcaio-sidebar-cart-footer vi-wcaio-sidebar-cart-footer-checkout vi-wcaio-disabled">
                    <button class="vi-wcaio-sidebar-cart-bt vi-wcaio-sidebar-cart-bt-checkout vi-wcaio-sidebar-cart-bt-checkout-place_order button vi-wcaio-disabled"
                            data-place_order_text="<?php echo esc_attr( $place_order_text ); ?>">
						<?php echo wp_kses_post( $place_order_text ); ?>
                    </button>
                    <button class="vi-wcaio-sidebar-cart-bt vi-wcaio-sidebar-cart-bt-checkout vi-wcaio-sidebar-cart-bt-checkout-nav vi-wcaio-sidebar-cart-bt-checkout-next button vi-wcaio-disabled"
                            data-next_text="<?php echo esc_attr( $bt_next_text ); ?>">
						<?php echo wp_kses_post( $bt_next_text ); ?>
                    </button>
                    <button class="vi-wcaio-sidebar-cart-bt vi-wcaio-sidebar-cart-bt-checkout vi-wcaio-sidebar-cart-bt-checkout-nav vi-wcaio-sidebar-cart-bt-checkout-back button vi-wcaio-disabled"
                            data-pre_text="<?php echo esc_attr( $bt_pre_text ); ?>">
						<?php echo wp_kses_post( $bt_pre_text ); ?>
                    </button>
					<?php
					if ( $sidebar_cart::$settings->get_params( 'sc_checkout_bt_btc_enable' ) || $is_customize ) {
						$sc_checkout_bt_btc_title = $sidebar_cart->get_params( 'sc_checkout_bt_btc_title', $language );
						$back_icon_class          = $sidebar_cart::$settings->get_class_icon( $sidebar_cart->get_params( 'sc_checkout_bt_btc_icon' ), 'back_icons' ) ?: '';
						$sc_checkout_bt_btc_title = str_replace( '{back_icon}', '<i class="vi-wcaio-sc-checkout-bt-btc-back_icons ' . $back_icon_class . '"></i>', $sc_checkout_bt_btc_title );
						?>
                        <button class="vi-wcaio-sidebar-cart-bt vi-wcaio-sidebar-cart-bt-checkout vi-wcaio-sidebar-cart-bt-checkout-cancel button">
							<?php echo wp_kses_post( $sc_checkout_bt_btc_title ); ?>
                        </button>
						<?php
					}
					?>
                </div>
				<?php
			}
			?>
            <div class="vi-wcaio-sidebar-cart-footer-message-wrap">
				<?php
				$sidebar_cart::get_sc_footer_message_html( $sidebar_cart->get_params( 'sc_footer_message' ), true );
				?>
            </div>
        </div>
        <div class="vi-wcaio-sidebar-cart-loading-wrap vi-wcaio-disabled">
			<?php
			$sc_loading = $sidebar_cart::$settings->get_params( 'sc_loading' );
			if ( $is_customize ) {
				$loading = array(
					'default',
					'dual_ring',
					'animation_face_1',
					'animation_face_2',
					'ring',
					'roller',
					'loader_balls_1',
					'loader_balls_2',
					'loader_balls_3',
					'ripple',
					'spinner'
				);
				foreach ( $loading as $item ) {
					$sidebar_cart->get_sidebar_loading( $item );
				}
			} elseif ( $sc_loading ) {
				$sidebar_cart->get_sidebar_loading( $sc_loading );
			}
			?>
        </div>
    </div>
<?php do_action( 'vi_wcaio_after_mini_cart' ); ?>