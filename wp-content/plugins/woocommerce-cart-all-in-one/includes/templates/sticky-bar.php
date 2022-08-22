<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$settings     = $settings ?? new VIWCAIO_CART_ALL_IN_ONE_DATA();
$product_id   = $product->get_id();
$product_name = $product->get_name();
$class        = array(
	'vi-wcaio-sb-container',
	'vi-wcaio-sb-container-' . $template,
	'vi-wcaio-sb-container-ps-' . $position,
	'vi-wcaio-sb-container-pd-' . $product->get_type(),
);
$class[]      = is_rtl() ? 'vi-wcaio-sb-container-rtl' : '';
$class[]      = $customize_preview ? 'vi-wcaio-sb-container-customize_preview vi-wcaio-disabled' : '';
$class[]      = $always_appear ? 'vi-wcaio-sb-container-always_appear' : '';
$class[]      = is_user_logged_in() ? 'vi-wcaio-sb-container-logged' : '';
$class        = implode( ' ', $class );
?>
<div class="<?php echo esc_attr( trim( $class ) ); ?>">
    <div class="vi-wcaio-sb-wrap">
        <div class="vi-wcaio-sb-product-desc-wrap">
            <div class="vi-wcaio-sb-product-img-wrap">
	            <?php
	            $product_img_url = wp_get_attachment_image_url( get_post_thumbnail_id( $product_id ), 'woocommerce_gallery_thumbnail' ) ?? wc_placeholder_img_src();
	            echo sprintf( '<img src="" data-src="%s" class="vi-wcaio-sb-product-img" alt="%s">', $product_img_url, $product_name );
	            ?>
            </div>
            <div class="vi-wcaio-sb-product-title-wrap">
                <span class="vi-wcaio-sb-product-title" data-product_name="<?php echo esc_attr( $product_name ); ?>"><?php echo esc_html( $product_name ); ?></span>
	            <?php
	            if ( $settings->get_params( 'sb_pd_review' ) || $customize_preview ) {
		            echo sprintf( '<div class="vi-wcaio-sb-product-rating-wrap">%s</div>', wc_get_rating_html( $product->get_average_rating() ) );
	            }
	            if ( in_array( $template, [ 2, 4 ] ) ) {
		            ?>
                    <div class="vi-wcaio-sb-product-price-wrap">
                    <span class="price">
                        <?php echo wp_kses( $product->get_price_html(), VIWCAIO_CART_ALL_IN_ONE_DATA::extend_post_allowed_html() ); ?>
                    </span>
                    </div>
		            <?php
	            }
	            ?>
            </div>
        </div>
	    <?php
		if ( $product->is_in_stock() ) {
			?>
            <div class="vi-wcaio-sb-product-control">
                <div class="vi-wcaio-sb-product-cart">
					<?php do_action( 'vi-wcaio-product-' . $product->get_type() . '-add-to-cart', $product, $template, $settings ); ?>
                </div>
            </div>
			<?php
		} else {
			echo sprintf( '<div class="vi-wcaio-sb-product-oft">%s</div>', apply_filters( 'vi-wcaio-set-out-of-stock-text', esc_html__( 'Out Of Stock', 'woocommerce-cart-all-in-one' ), $product ) );
		}
		?>
    </div>
</div>