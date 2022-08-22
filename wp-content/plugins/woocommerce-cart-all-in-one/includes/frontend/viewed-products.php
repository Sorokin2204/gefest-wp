<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class VIWCAIO_CART_ALL_IN_ONE_Frontend_Viewed_Products {
	protected $settings, $language;

	public function __construct() {
		$this->settings = new VIWCAIO_CART_ALL_IN_ONE_DATA();
		add_action( 'init', array( $this, 'shortcode_init' ) );
		if ( ! $this->settings->enable( 'vp_' ) ) {
			return;
		}
		$hook = array(
			'1' => 'woocommerce_before_single_product_summary',
			'2' => 'woocommerce_after_single_product_summary',
			'3' => 'woocommerce_after_single_product',
		);
		$hook = apply_filters( 'vi_wcaio_vp_single_position', $hook[ $this->settings->get_params( 'vp_single_position' ) ] ?? '' );
		if ( $hook ) {
			$this->language = null;
			add_action( $hook, array( $this, 'frontend_html' ) );
		}
		add_filter( 'wp_kses_allowed_html', array( $this, 'viwcuf_wp_kses_allowed_html' ), PHP_INT_MAX, 2 );
	}

	public function viwcuf_wp_kses_allowed_html( $allowed, $context ) {
		if ( is_array( $context ) ) {
			return $allowed;
		}
		if ( $context === 'post' ) {
			$allowed['a']['data-*']      = true;
			$allowed['select']['name']   = true;
			$allowed['select']['class']  = true;
			$allowed['select']['id']     = true;
			$allowed['select']['data-*'] = true;
			$allowed['option']['data-*'] = true;
			$allowed['option']['value']  = true;
			$allowed['div']['data-*']    = true;
		}
		return $allowed;
	}

	public function frontend_html() {
		if ( $this->language === null ) {
			if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
				$default_lang     = apply_filters( 'wpml_default_language', null );
				$current_language = apply_filters( 'wpml_current_language', null );
				if ( $current_language && $current_language !== $default_lang ) {
					$this->language = '_' . $current_language;
				}
			} else if ( class_exists( 'Polylang' ) ) {
				$default_lang     = pll_default_language( 'slug' );
				$current_language = pll_current_language( 'slug' );
				if ( $current_language && $current_language !== $default_lang ) {
					$this->language = '_' . $current_language;
				}
			}
			$this->language = $this->language ?? '';
		}
		$vp_title        = $this->settings->get_params( 'vp_title', $this->language );
		$vp_pd_limit     = $this->settings->get_params( 'vp_pd_limit' );
		$vp_pd_column    = $this->settings->get_params( 'vp_pd_column' );
		$vp_slider_loop  = $this->settings->get_params( 'vp_slider_loop' );
		$vp_slider_move  = $this->settings->get_params( 'vp_slider_move' );
		$vp_slider_auto  = $this->settings->get_params( 'vp_slider_auto' );
		$vp_slider_speed = $this->settings->get_params( 'vp_slider_speed' );
		$vp_slider_pause = $this->settings->get_params( 'vp_slider_pause' );
		$shortcode       = '[vi_wcaio_viewed_product class="vi-wcaio-vp-wrap-single" title="' . $vp_title . '" display="' . $vp_pd_limit . '" columns="' . $vp_pd_column . '" loop="' . $vp_slider_loop . '" move="' . $vp_slider_move . '" auto_play="' . $vp_slider_auto . '" speed="' . $vp_slider_speed . '" pause="' . $vp_slider_pause . '" ]';
		echo wp_kses( do_shortcode( $shortcode ), VIWCAIO_CART_ALL_IN_ONE_DATA::extend_post_allowed_html() );
	}

	public function shortcode_init() {
		add_shortcode( 'vi_wcaio_viewed_product', array( $this, 'viwcaio_viewed_product' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'viwcaio_wp_enqueue_scripts' ) );
	}

	public function viwcaio_wp_enqueue_scripts() {
		if ( ! $this->settings->enable( 'vp_' ) ) {
			return;
		}
		wp_register_style( 'vi-wcaio-nav-icons', VIWCAIO_CART_ALL_IN_ONE_CSS . 'nav-icons.min.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
		wp_register_style( 'vi-wcaio-vp', VIWCAIO_CART_ALL_IN_ONE_CSS . 'viewed-products.min.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
		wp_register_script( 'vi-wcaio-flexslider', VIWCAIO_CART_ALL_IN_ONE_JS . 'vi-flexslider.min.js', array( 'jquery' ), VIWCAIO_CART_ALL_IN_ONE_VERSION );
		if ( WP_DEBUG ) {
			wp_register_script( 'vi-wcaio-vp', VIWCAIO_CART_ALL_IN_ONE_JS . 'viewed-products.js', array( 'jquery' ), VIWCAIO_CART_ALL_IN_ONE_VERSION );
		} else {
			wp_register_script( 'vi-wcaio-vp', VIWCAIO_CART_ALL_IN_ONE_JS . 'viewed-products.min.js', array( 'jquery' ), VIWCAIO_CART_ALL_IN_ONE_VERSION );
		}
	}

	public function viwcaio_viewed_product( $atts ) {
		if ( ! $this->settings->enable( 'vp_' ) ) {
			return false;
		}
		extract( shortcode_atts( array(
			'class'     => '',
			'title'     => '',
			'display'   => 4,
			'columns'   => 4,
			'loop'      => 1,
			'move'      => 4,
			'auto_play' => 1,
			'speed'     => 2000,
			'pause'     => 1,
		), $atts ) );
		if ( ! $display || ! $columns ) {
			return false;
		}
		$viewed_products = is_active_widget( false, false, 'woocommerce_recently_viewed_products', true ) ? ( $_COOKIE['woocommerce_recently_viewed'] ?? '' ) : '';
		$viewed_products = $viewed_products ?: ( $_COOKIE['viwcaio_recently_viewed'] ?? '' );
		$viewed_products = $viewed_products ? explode( '|', wp_unslash( $viewed_products ) ) : array();
		if ( empty( $viewed_products ) ) {
			return false;
		}
		$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
		if ( $display < count( $viewed_products ) ) {
			$viewed_products = array_slice( $viewed_products, 0, $display );
		}
		if ( ! wp_style_is( 'vi-wcaio-vp' ) ) {
			wp_enqueue_style( 'vi-wcaio-nav-icons' );
			wp_enqueue_style( 'vi-wcaio-vp' );
		}
		if ( ! wp_script_is( 'vi-wcaio-vp' ) ) {
			wp_enqueue_script( 'vi-wcaio-vp' );
			wp_enqueue_script( 'vi-wcaio-flexslider' );
		}
		$wrap_class   = array( 'vi-wcaio-vp-wrap' );
		$wrap_class[] = is_rtl() ? 'vi-wcaio-vp-wrap-rtl' : '';
		$wrap_class[] = $class;
		$wrap_class   = trim( implode( ' ', $wrap_class ) );
		ob_start();
		?>
        <div class="<?php echo esc_attr( $wrap_class ); ?>">
			<?php
			if ( $title ) {
				?>
                <div class="vi-wcaio-vp-title-wrap">
					<?php echo wp_kses_post( $title ); ?>
                </div>
				<?php
			}
			?>
            <div class="vi-wcaio-vp vi-wcaio-vp-slider" data-columns="<?php echo esc_attr( $columns ); ?>"
                 data-loop="<?php echo esc_attr( $loop ); ?>" data-move="<?php echo esc_attr( $move ); ?>"
                 data-auto_play="<?php echo esc_attr( $auto_play ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>"
                 data-pause="<?php echo esc_attr( $pause ); ?>">
                <div class="vi-wcaio-vp-products">
					<?php
					foreach ( $viewed_products as $product_id ) {
						?>
                        <div class="vi-wcaio-vp-product"
                             data-product_id="<?php echo esc_attr( $product_id ); ?>">
							<?php
							echo do_shortcode( '[products ids="' . $product_id . '" limit="1"]' );
							?>
                        </div>
						<?php
					}
					?>
                </div>
            </div>
        </div>
		<?php
		$html = ob_get_clean();
		return $html;
	}
}