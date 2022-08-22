<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$settings      = $settings ?? new VIWCAIO_CART_ALL_IN_ONE_DATA();
$product_id    = $product->get_id();
$product_name  = $product->get_name();
$class         = array(
	'vi-wcaio-sb-container vi-wcaio-sb-container-mobile',
	'vi-wcaio-sb-container-ps-' . $position,
	'vi-wcaio-sb-container-pd-' . $product->get_type(),
);
$class[]       = is_rtl() ? 'vi-wcaio-sb-container-rtl' : '';
$class[]       = is_user_logged_in() ? 'vi-wcaio-sb-container-logged' : '';
$popup_class   = $class;
$popup_class[] = 'vi-wcaio-sb-container-popup';
$popup_class   = trim( implode( ' ', $popup_class ) );
$class[]       = $always_appear ? 'vi-wcaio-sb-container-always_appear' : '';
$class         = trim( implode( ' ', $class ) );
?>
<div class="<?php echo esc_attr( $class ); ?>">
    <div class="vi-wcaio-sb-wrap">
		<?php
		if ( $settings->get_params( 'sb_mobile_img' ) ) {
			?>
            <div class="vi-wcaio-sb-product-img-wrap">
				<?php
				$product_img_url = wp_get_attachment_image_url( get_post_thumbnail_id( $product_id ), 'woocommerce_gallery_thumbnail' ) ?? wc_placeholder_img_src();
				echo sprintf( '<img src="" data-src="%s" class="vi-wcaio-sb-product-img" alt="%s">', $product_img_url, $product_name );
				?>
            </div>
			<?php
		}
		if ( $product->is_in_stock() ) {
			do_action( 'vi_wcaio_sb_mobile_button_action', $product );
		} else {
			echo sprintf( '<div class="vi-wcaio-sb-product-oft">%s</div>', apply_filters( 'vi-wcaio-set-out-of-stock-text', esc_html__( 'Out Of Stock', 'woocommerce-cart-all-in-one' ), $product ) );
		}
		?>
    </div>
</div>
<div class="<?php echo esc_attr( $popup_class ); ?>">
    <div class="vi-wcaio-sb-container-popup-form-wrap">
	    <?php do_action( 'vi_wcaio_sb_mobile_' . $product->get_type() . '_add_to_cart', $product ); ?>
        <span class="vi-wcaio-sb-container-popup-cancel">x</span>
    </div>
    <div class="vi-wcaio-sb-container-popup-overlay"></div>
</div>
