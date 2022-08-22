<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class VIWCAIO_CART_ALL_IN_ONE_Frontend_Sticky_Bar {
	protected $settings, $language;

	public function __construct() {
		$this->settings = new VIWCAIO_CART_ALL_IN_ONE_DATA();
		$this->language = '';
		add_action( 'wp_enqueue_scripts', array( $this, 'viwcaio_wp_enqueue_scripts' ) );
		add_action( 'vi-wcaio-product-simple-add-to-cart', array( $this, 'product_simple_add_to_cart' ), 10, 3 );
		add_action( 'vi-wcaio-product-variable-add-to-cart', array( $this, 'product_variable_add_to_cart' ), 10, 3 );
		add_action( 'vi-wcaio-product-external-add-to-cart', array( $this, 'product_external_add_to_cart' ), 10, 3 );
		add_action( 'vi-wcaio-product-grouped-add-to-cart', array( $this, 'product_grouped_add_to_cart' ), 10, 3 );
		add_action( 'vi_wcaio_sb_mobile_button_action', array( $this, 'viwcaio_sb_mobile_button_action' ), 10, 1 );
		add_action( 'vi_wcaio_sb_mobile_variable_add_to_cart', array( $this, 'viwcaio_sb_mobile_variable_add_to_cart' ), 10, 1 );
	}

	public function viwcaio_wp_enqueue_scripts() {
		if ( ! is_single() && ! is_product() ) {
			return;
		}
		if ( ! $this->settings->enable( 'sb_' ) && ! is_customize_preview() ) {
			return;
		}
		global $post;
		$product_id = $post->ID;
		if ( in_array( $product_id, $this->settings->get_params( 'sb_pd_exclude' ) ) ) {
			return;
		}
		$cats_id       = wc_get_product_cat_ids( $product_id );
		$cats_id_check = $this->settings->get_params( 'sb_cats_exclude' );
		if ( $cats_id_check && is_array( $cats_id_check ) && count( array_intersect( $cats_id, $cats_id_check ) ) ) {
			return;
		}
		$product = wc_get_product( $product_id );
		wp_enqueue_style( 'vi-wcaio-cart-icons-atc', VIWCAIO_CART_ALL_IN_ONE_CSS . 'cart-icons-atc.min.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
		if ( WP_DEBUG ) {
			wp_enqueue_style( 'vi-wcaio-sticky-bar', VIWCAIO_CART_ALL_IN_ONE_CSS . 'sticky-bar.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
			wp_enqueue_script( 'vi-wcaio-sticky-bar', VIWCAIO_CART_ALL_IN_ONE_JS . 'sticky-bar.js', array( 'jquery' ), VIWCAIO_CART_ALL_IN_ONE_VERSION );
			if ( $product && $product->is_type( [ 'variable' ] ) ) {
				wp_enqueue_script( 'vi-wcaio-frontend-swatches', VIWCAIO_CART_ALL_IN_ONE_JS . 'frontend-swatches.js', array( 'jquery' ), VIWCAIO_CART_ALL_IN_ONE_VERSION );
			}
		} else {
			wp_enqueue_style( 'vi-wcaio-sticky-bar', VIWCAIO_CART_ALL_IN_ONE_CSS . 'sticky-bar.min.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
			wp_enqueue_script( 'vi-wcaio-sticky-bar', VIWCAIO_CART_ALL_IN_ONE_JS . 'sticky-bar.min.js', array( 'jquery' ), VIWCAIO_CART_ALL_IN_ONE_VERSION );
			if ( $product && $product->is_type( [ 'variable' ] ) ) {
				wp_enqueue_script( 'vi-wcaio-frontend-swatches', VIWCAIO_CART_ALL_IN_ONE_JS . 'frontend-swatches.min.js', array( 'jquery' ), VIWCAIO_CART_ALL_IN_ONE_VERSION );
			}
		}
		if ( ! is_customize_preview() ) {
			$args = array(
				'wc_ajax_url' => WC_AJAX::get_endpoint( "%%endpoint%%" ),
			);
			wp_localize_script( 'vi-wcaio-sticky-bar', 'viwcaio_sb_params', $args );
			$css = $this->get_inline_css();
			wp_add_inline_style( 'vi-wcaio-sticky-bar', $css );
		}
		add_action( 'wp_footer', array( $this, 'frontend_html' ) );
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
	}

	public function get_inline_css() {
		$css = '';
		if ( $sb_box_shadow_color = $this->settings->get_params( 'sb_box_shadow_color' ) ) {
			$css .= '.vi-wcaio-sb-container:not(.vi-wcaio-sb-container-popup) { box-shadow: 0 1px 4px 0 ' . $sb_box_shadow_color . ';}';
		}
		$frontend = 'VIWCAIO_CART_ALL_IN_ONE_Frontend_Frontend';
		$css      .= $frontend::add_inline_style(
			array( '.vi-wcaio-sb-container' ),
			array( 'sb_bg_color', 'sb_padding', 'sb_border_radius' ),
			array( 'background', 'padding', 'border-radius' ),
			array( '', '', 'px' )
		);
		$css      .= $frontend::add_inline_style(
			array( '.vi-wcaio-sb-container .vi-wcaio-sb-wrap .quantity' ),
			array( 'sb_quantity_border_radius' ),
			array( 'border-radius' ),
			array( 'px' )
		);
		$css      .= $frontend::add_inline_style(
			array( '.vi-wcaio-sb-container .button.vi-wcaio-product-bt-atc' ),
			array( 'sb_bt_atc_bg_color', 'sb_bt_atc_color', 'sb_bt_atc_border_radius', 'sb_bt_atc_font_size' ),
			array( 'background', 'color', 'border-radius', 'font-size' ),
			array( '', '', 'px', 'px' )
		);
		$css      .= $frontend::add_inline_style(
			array( '.vi-wcaio-sb-container .vi-wcaio-sb-product-title' ),
			array( 'sb_pd_name_color' ),
			array( 'color' ),
			array( '' )
		);
		$css      .= $frontend::add_inline_style(
			array( '.vi-wcaio-sb-container .vi-wcaio-sb-product-price-wrap .price' ),
			array( 'sb_pd_price_color2' ),
			array( 'color' ),
			array( '' )
		);
		$css      .= $frontend::add_inline_style(
			array( '.vi-wcaio-sb-container .vi-wcaio-sb-product-price-wrap .price del' ),
			array( 'sb_pd_price_color1' ),
			array( 'color' ),
			array( '' )
		);
		$css      .= $frontend::add_inline_style(
			array( '.vi-wcaio-sb-container .vi-wcaio-sb-product-rating-wrap *:before' ),
			array( 'sb_pd_review_color' ),
			array( 'color' ),
			array( '' )
		);
		$css      .= $frontend::add_inline_style(
			array( '.vi-wcaio-sb-container .vi-wcaio-sb-product-img-wrap, .vi-wcaio-sb-container .vi-wcaio-sb-product-img-wrap img' ),
			array( 'sb_pd_img_width', 'sb_pd_img_width', 'sb_pd_img_height', 'sb_pd_img_height' ),
			array( 'width', 'max-width', 'height', 'max-height' ),
			array( 'px', 'px', 'px', 'px' )
		);
		$css      .= '@media screen and (max-width: 1000px){';
		$css      .= $frontend::add_inline_style(
			array( '.vi-wcaio-sb-container .button.vi-wcaio-product-bt-atc' ),
			array( 'sb_mobile_bt_atc_bg_color', 'sb_mobile_bt_atc_color', 'sb_mobile_bt_atc_border_radius', 'sb_mobile_bt_atc_font_size' ),
			array( 'background', 'color', 'border-radius', 'font-size' ),
			array( '', '', 'px', 'px' )
		);
		$css      .= '}';
		return $css;
	}

	public function frontend_html() {
		global $post;
		$product = wc_get_product( $post->ID );
		if ( ! $product ) {
			return;
		}
		$sb_template = $this->settings->get_params( 'sb_template' );
		$sb_position = $this->settings->get_params( 'sb_position' );
		if ( is_customize_preview() ) {
			for ( $i = 1; $i < 5; $i ++ ) {
				wc_get_template( 'sticky-bar.php',
					array(
						'product'           => $product,
						'template'          => $i,
						'position'          => $sb_position,
						'settings'          => $this->settings,
						'always_appear'     => 0,
						'customize_preview' => true,
					),
					'woocommerce-cart-all-in-one' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR,
					VIWCAIO_CART_ALL_IN_ONE_TEMPLATES );
			}
		} elseif ( wp_is_mobile() ) {
			wc_get_template( 'sticky-bar-mobile.php',
				array(
					'product'       => $product,
					'position'      => $this->settings->get_params( 'sb_mobile_position' ),
					'settings'      => $this->settings,
					'always_appear' => $this->settings->get_params( 'sb_always_appear' )
				),
				'woocommerce-cart-all-in-one' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR,
				VIWCAIO_CART_ALL_IN_ONE_TEMPLATES );
		} else {
			wc_get_template( 'sticky-bar.php',
				array(
					'product'           => $product,
					'template'          => $sb_template,
					'position'          => $sb_position,
					'settings'          => $this->settings,
					'always_appear'     => $this->settings->get_params( 'sb_always_appear' ),
					'customize_preview' => false,
				),
				'woocommerce-cart-all-in-one' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR,
				VIWCAIO_CART_ALL_IN_ONE_TEMPLATES );
		}
	}
	public function viwcaio_sb_mobile_variable_add_to_cart( $product ) {
		$attributes = $product->get_variation_attributes();
		if ( empty( $attributes ) ) {
			return;
		}
		$variation_count     = count( $product->get_children() );
		$get_variations      = $variation_count <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
		$selected_attributes = $product->get_default_attributes();
		if ( $get_variations ) {
			$available_variations = $product->get_available_variations();
			if ( empty( $available_variations ) ) {
				return;
			}
			$variations_json = wp_json_encode( $available_variations );
			$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );
		} else {
			$variations_attr = false;
		}
		$product_id   = $product->get_id();
		$product_name = $product->get_name();
		?>
        <form class="vi-wcaio-sb-cart-form vi-wcaio-sb-cart-swatches vi-wcaio-cart-swatches-wrap"
              action="<?php echo esc_attr( esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ) ); ?>"
              method="post" enctype="multipart/form-data"
              data-product_id="<?php echo esc_attr( $product_id ); ?>"
              data-product_name="<?php echo esc_attr( $product_name ); ?>"
              data-variation_count="<?php echo esc_attr( $variation_count ); ?>"
              data-product_variations="<?php echo esc_attr( $variations_attr ); ?>">
            <div class="vi-wcaio-sb-cart-form-header-wrap">
                <div class="vi-wcaio-sb-cart-form-header vi-wcaio-sb-cart-form-header-image">
					<?php
					$product_img_url = wp_get_attachment_image_url( get_post_thumbnail_id( $product_id ), 'woocommerce_gallery_thumbnail' ) ?? wc_placeholder_img_src();
					echo sprintf( '<img src="" data-src="%s" class="vi-wcaio-sb-product-img" alt="%s">', $product_img_url, $product_name );
					?>
                </div>
                <div class="vi-wcaio-sb-cart-form-header vi-wcaio-sb-cart-form-header-desc">
                    <div class="vi-wcaio-sb-product-price-wrap">
                        <span class="price"><?php echo wp_kses( $product->get_price_html(), VIWCAIO_CART_ALL_IN_ONE_DATA::extend_post_allowed_html() ); ?></span>
                    </div>
                    <div class="vi-wcaio-sb-product-rating-wrap"><?php echo wc_get_rating_html( $product->get_average_rating() ); ?></div>
                </div>
            </div>
            <div class="vi-wcaio-sb-cart-form-content-wrap">
	            <?php
	            foreach ( $attributes as $attribute_name => $options ) {
		            $selected = $selected_attributes[ $attribute_name ] ?? $product->get_variation_default_attribute( $attribute_name );
		            echo sprintf( '<div class="vi-wcaio-sb-cart-form-content vi-wcaio-sb-swatches-wrap vi-wcaio-swatches-wrap"><div class="vi-wcaio-sb-cart-form-content-title">%s</div>', wc_attribute_label( $attribute_name, $product ) );
		            echo sprintf( '<div class="vi-wcaio-sb-cart-form-content-value vi-wcaio-sb-swatches-value vi-wcaio-swatches-value value">' );
		            wc_dropdown_variation_attribute_options( apply_filters( 'vi_wcaio_dropdown_variation_attribute_options', array(
			            'options'                 => $options,
			            'attribute'               => $attribute_name,
			            'product'                 => $product,
			            'selected'                => $selected,
			            'class'                   => 'vi-wcaio-attribute-options vi-wcaio-sb-attribute-options',
			            'viwpvs_swatches_disable' => ! $this->settings->get_params( 'sb_use_viwpvs' )
		            ), $attribute_name, $product ) );
		            echo sprintf( '</div></div>' );
	            }
	            echo sprintf( '<div class="vi-wcaio-sb-cart-form-content vi-wcaio-sb-cart-form-content-qty"><div class="vi-wcaio-sb-cart-form-content-title">%s</div>',
		            apply_filters( 'vi_wcaio_sb_mobile_set_qty_title', esc_html__( 'Quantity', 'woocommerce-cart-all-in-one' ) ) );
	            echo sprintf( '<div class="vi-wcaio-sb-cart-form-content-value">' );
	            self::product_get_quantity( $product, $this->settings->get_params( 'sb_quantity' ), true );
	            echo sprintf( '</div></div>' );
	            ?>
            </div>
            <div class="vi-wcaio-sb-cart-form-footer-wrap">
	            <?php do_action( 'vi_wcaio_before_add_to_cart_button' ); ?>
                <button type="submit" class="vi-wcaio-product-bt-atc vi-wcaio-sb-product-bt-atc button">
	                <?php echo wp_kses_post( self::product_get_bt_atc_html( $this->settings, $this->language ) ); ?>
                </button>
                <input type="hidden" name="add-to-cart" class="vi-wcaio-add-to-cart" value="<?php echo esc_attr( $product_id ); ?>"/>
                <input type="hidden" name="product_id" class="vi-wcaio-product_id" value="<?php echo esc_attr( $product_id ); ?>"/>
                <input type="hidden" name="variation_id" class="variation_id" value="0"/>
	            <?php do_action( 'vi_wcaio_after_add_to_cart_button' ); ?>
            </div>
        </form>
		<?php
	}
	public function viwcaio_sb_mobile_button_action( $product ) {
		switch ( $product_type = $product->get_type() ) {
			case 'simple':
				$product_id = $product->get_id();
				?>
                <form class="vi-wcaio-sb-cart-form" action="<?php echo esc_attr( esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ) ); ?>"
                      method="post" enctype="multipart/form-data" data-product_id="<?php echo esc_attr( $product_id ); ?>">
					<?php
					do_action( 'vi_wcaio_before_add_to_cart_button' );
					self::product_get_quantity( $product, $this->settings->get_params( 'sb_quantity' ) );
					?>
                    <button type="submit" class="vi-wcaio-product-bt-atc vi-wcaio-sb-product-bt-atc button">
						<?php echo wp_kses_post( self::product_get_bt_atc_html( $this->settings, $this->language ) ); ?>
                    </button>
                    <input type="hidden" name="add-to-cart" class="vi-wcaio-add-to-cart" value="<?php echo esc_attr( $product_id ); ?>"/>
                    <input type="hidden" name="product_id" class="vi-wcaio-product_id" value="<?php echo esc_attr( $product_id ); ?>"/>
					<?php do_action( 'vi_wcaio_after_add_to_cart_button' ); ?>
                </form>
				<?php
				break;
			case 'variable':
				echo sprintf( '<button class="vi-wcaio-product-bt-atc vi-wcaio-sb-product-bt-atc vi-wcaio-sb-product-bt-popup vi-wcaio-product-bt-not-atc button">%s</button>',
					self::product_get_bt_atc_html( $this->settings, $this->language )
				);
				break;
			case 'external':
				?>
                <form class="vi-wcaio-sb-cart-form" action="<?php echo esc_attr( esc_url( $product->add_to_cart_url() ) ); ?>" method="get">
					<?php do_action( 'vi_wcaio_before_add_to_cart_button' ); ?>
                    <button type="submit" class="vi-wcaio-product-bt-atc vi-wcaio-sb-product-bt-atc vi-wcaio-product-bt-external vi-wcaio-product-bt-not-atc button">
						<?php echo wp_kses_post( self::product_get_bt_atc_html( $this->settings, $this->language ) ); ?>
                    </button>
					<?php do_action( 'vi_wcaio_after_add_to_cart_button' ); ?>
                </form>
				<?php
				break;
			case 'grouped':
				do_action( 'vi_wcaio_before_add_to_cart_button' );
				echo sprintf( '<button type="button" class="vi-wcaio-product-bt-atc vi-wcaio-sb-product-bt-atc vi-wcaio-product-bt-select vi-wcaio-product-bt-not-atc button">%s</button>',
					self::product_get_bt_atc_html( $this->settings, $this->language ) );
				do_action( 'vi_wcaio_after_add_to_cart_button' );
				break;
			default:
				echo sprintf( '<button class="vi-wcaio-product-bt-atc vi-wcaio-sb-product-bt-atc vi-wcaio-product-bt-select vi-wcaio-product-bt-not-atc button">%s</button>',
					self::product_get_bt_atc_html( $this->settings, $this->language )
				);
		}
	}
	public function product_simple_add_to_cart( $product, $template, $settings ) {
		$product_id = $product->get_id();
		?>
        <form class="vi-wcaio-sb-cart-form" action="<?php echo esc_attr( esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ) ); ?>"
              method="post" enctype="multipart/form-data" data-product_id="<?php echo esc_attr( $product_id ); ?>">
			<?php
			do_action( 'vi_wcaio_before_add_to_cart_button' );
			self::product_get_quantity( $product, $settings->get_params( 'sb_quantity' ) );
			if ( in_array( $template, [ 1, 3 ] ) ) {
				?>
                <div class="vi-wcaio-sb-product-price-wrap"><span class="price"><?php echo wp_kses( $product->get_price_html(), VIWCAIO_CART_ALL_IN_ONE_DATA::extend_post_allowed_html() ); ?></span></div><?php
			}
			?>
            <button type="submit" class="vi-wcaio-product-bt-atc vi-wcaio-sb-product-bt-atc button">
				<?php echo wp_kses_post( self::product_get_bt_atc_html( $settings, $this->language ) ); ?>
            </button>
            <input type="hidden" name="add-to-cart" class="vi-wcaio-add-to-cart" value="<?php echo esc_attr( $product_id ); ?>"/>
            <input type="hidden" name="product_id" class="vi-wcaio-product_id" value="<?php echo esc_attr( $product_id ); ?>"/>
			<?php do_action( 'vi_wcaio_after_add_to_cart_button' ); ?>
        </form>
		<?php
	}

	public function product_variable_add_to_cart( $product, $template, $settings ) {
		$attributes = $product->get_variation_attributes();
		if ( empty( $attributes ) ) {
			echo sprintf( '<button type="button" class="vi-wcaio-product-bt-atc vi-wcaio-sb-product-bt-atc vi-wcaio-product-bt-select vi-wcaio-product-bt-not-atc button">%s</button>',
				self::product_get_bt_atc_html( $settings, $this->language ) );
			return;
		}
		$variation_count     = count( $product->get_children() );
		$get_variations      = $variation_count <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
		$selected_attributes = $product->get_default_attributes();
		if ( $get_variations ) {
			$available_variations = $product->get_available_variations();
			if ( empty( $available_variations ) ) {
				echo sprintf( '<button type="button" class="vi-wcaio-product-bt-atc vi-wcaio-sb-product-bt-atc vi-wcaio-product-bt-select vi-wcaio-product-bt-not-atc button">%s</button>',
					self::product_get_bt_atc_html( $settings, $this->language ) );
				return;
			}
			$variations_json = wp_json_encode( $available_variations );
			$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );
		} else {
			$variations_attr = false;
		}
		$product_id   = $product->get_id();
		$product_name = $product->get_name();
		?>
        <form class="vi-wcaio-sb-cart-form vi-wcaio-sb-cart-swatches vi-wcaio-cart-swatches-wrap"
              action="<?php echo esc_attr( esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ) ); ?>"
              method="post" enctype="multipart/form-data"
              data-product_id="<?php echo esc_attr( $product_id ); ?>"
              data-product_name="<?php echo esc_attr( $product_name ); ?>"
              data-variation_count="<?php echo esc_attr( $variation_count ); ?>"
              data-product_variations="<?php echo esc_attr( $variations_attr ); ?>">
            <div class="vi-wcaio-sb-swatches-wrap-wrap vi-wcaio-swatches-wrap-wrap">
				<?php
				foreach ( $attributes as $attribute_name => $options ) {
					$selected = $selected_attributes[ $attribute_name ] ?? $product->get_variation_default_attribute( $attribute_name );
					echo sprintf( '<div class="vi-wcaio-sb-swatches-wrap vi-wcaio-swatches-wrap"><div class="vi-wcaio-sb-swatches-value vi-wcaio-swatches-value value">' );
					wc_dropdown_variation_attribute_options( apply_filters( 'vi_wcaio_dropdown_variation_attribute_options', array(
						'options'                 => $options,
						'attribute'               => $attribute_name,
						'product'                 => $product,
						'selected'                => $selected,
						'class'                   => 'vi-wcaio-attribute-options vi-wcaio-sb-attribute-options',
						'viwpvs_swatches_disable' => ! $this->settings->get_params( 'sb_use_viwpvs' )
					), $attribute_name, $product ) );
					echo sprintf( '</div></div>' );
				}
				?>
            </div>
			<?php
			do_action( 'vi_wcaio_before_add_to_cart_button' );
			self::product_get_quantity( $product, $settings->get_params( 'sb_quantity' ) );
			if ( in_array( $template, [ 1, 3 ] ) ) {
				?>
                <div class="vi-wcaio-sb-product-price-wrap"><span class="price"><?php echo wp_kses( $product->get_price_html(), VIWCAIO_CART_ALL_IN_ONE_DATA::extend_post_allowed_html() ); ?></span></div><?php
			}
			?>
            <button type="submit" class="vi-wcaio-product-bt-atc vi-wcaio-sb-product-bt-atc button">
				<?php echo wp_kses_post( self::product_get_bt_atc_html( $settings, $this->language ) ); ?>
            </button>
            <input type="hidden" name="add-to-cart" class="vi-wcaio-add-to-cart" value="<?php echo esc_attr( $product_id ); ?>"/>
            <input type="hidden" name="product_id" class="vi-wcaio-product_id" value="<?php echo esc_attr( $product_id ); ?>"/>
            <input type="hidden" name="variation_id" class="variation_id" value="0"/>
			<?php do_action( 'vi_wcaio_after_add_to_cart_button' ); ?>
        </form>
		<?php
	}

	public function product_external_add_to_cart( $product, $template, $settings ) {
		?>
        <form class="vi-wcaio-sb-cart-form" action="<?php echo esc_attr( esc_url( $product->add_to_cart_url() ) ); ?>" method="get">
			<?php do_action( 'vi_wcaio_before_add_to_cart_button' ); ?>
            <button type="submit" class="vi-wcaio-product-bt-atc vi-wcaio-sb-product-bt-atc vi-wcaio-product-bt-external vi-wcaio-product-bt-not-atc button">
				<?php echo wp_kses_post( self::product_get_bt_atc_html( $settings, $this->language ) ); ?>
            </button>
			<?php do_action( 'vi_wcaio_after_add_to_cart_button' ); ?>
        </form>
		<?php
	}

	public function product_grouped_add_to_cart( $product, $template, $settings ) {
		do_action( 'vi_wcaio_before_add_to_cart_button' );
		echo sprintf( '<button type="button" class="vi-wcaio-product-bt-atc vi-wcaio-sb-product-bt-atc vi-wcaio-product-bt-select vi-wcaio-product-bt-not-atc button">%s</button>',
			self::product_get_bt_atc_html( $settings, $this->language ) );
		do_action( 'vi_wcaio_after_add_to_cart_button' );
	}

	public static function product_get_bt_atc_html( $settings, $language ) {
		if ( ! $settings ) {
			return '';
		}
		$html            = $settings->get_params( 'sb_bt_atc_title', $language );
		$cart_icon_class = $settings->get_class_icon( $settings->get_params( 'sb_bt_atc_cart_icon' ), 'cart_icons_atc' ) ?: '';
		$html            = str_replace( '{cart_icon}', '<i class="vi-wcaio-sb-bt-atc-cart_icons ' . $cart_icon_class . '"></i>', $html );
		return $html;
	}

	public static function product_get_quantity( $product, $enable = false ) {
		if ( ! $product ) {
			return;
		}
		if ( ! $enable && ! is_customize_preview() ) {
			echo apply_filters( 'vi_wcaio_sticky_bar_qty', sprintf( '<input type="hidden" name="quantity" value="1" />' ), $product, [] );
			return;
		}
		if ( $product->is_sold_individually() ) {
			echo apply_filters( 'vi_wcaio_sticky_bar_qty', sprintf( '<input type="hidden" name="quantity" value="1" />' ), $product, [] );
		} else {
			$quantity_args = apply_filters( 'viwcaio_quantity_input_args', array(
				'input_name'   => "quantity",
				'input_value'  => 1,
				'max_value'    => $product->get_max_purchase_quantity(),
				'min_value'    => '0',
				'classes'      => [ 'vi-wcaio-sb-product-qty' ],
				'product_name' => $product->get_name()
			), $product );
			echo apply_filters( 'vi_wcaio_sticky_bar_qty', self::product_get_quantity_html( $quantity_args ), $product, $quantity_args );
		}
	}
	public static function product_get_quantity_html( $args = array() ) {
		if ( empty( $args ) ) {
			return '';
		}
		extract( $args );
		ob_start();
		if ( $max_value && $min_value === $max_value ) {
			?>
            <input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>">
			<?php
		} else {
			?>
            <div class="quantity">
				<?php do_action( 'woocommerce_before_quantity_input_field' ); ?>
                <span class="vi_wcaio_change_qty vi_wcaio_minus">-</span>
                <input type="number" title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce-cart-all-in-one' ); ?>"
                       placeholder="<?php echo esc_attr( $placeholder ); ?>"
                       id="<?php echo esc_attr( $input_id ); ?>"
                       class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
                       name="<?php echo esc_attr( $input_name ); ?>"
                       inputmode="<?php echo esc_attr( $inputmode ); ?>"
                       min="<?php echo esc_attr( $min_value ); ?>"
                       max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
                       step="<?php echo esc_attr( $step ); ?>"
                       value="<?php echo esc_attr( $input_value ); ?>">
                <span class="vi_wcaio_change_qty vi_wcaio_plus">+</span>
				<?php do_action( 'woocommerce_after_quantity_input_field' ); ?>
            </div>
			<?php
		}
		$html = ob_get_clean();
		return $html;
	}
}