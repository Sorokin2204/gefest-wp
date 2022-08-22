<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class VIWCAIO_CART_ALL_IN_ONE_Frontend_Sidebar_Cart_Icon {
	protected $settings, $use_img;
	protected $is_customize, $customize_data;
	public function __construct() {
		$this->settings = new VIWCAIO_CART_ALL_IN_ONE_DATA();
		add_action( 'wp_enqueue_scripts', array( $this, 'viwcaio_wp_enqueue_scripts' ), 9 );
		add_action( 'vi_wcaio_get_sidebar_cart_icon', array( $this, 'get_sidebar_cart_icon' ) );
	}
	public function viwcaio_wp_enqueue_scripts() {
		if ( ( is_checkout() || is_cart() ) && ! is_product() ) {
			return;
		}
		if ( ! isset( WC()->cart ) ) {
			return;
		}
		$this->is_customize = is_customize_preview();
		if ( ! $this->is_customize && ! $this->assign_page() ) {
			return;
		} else {
			global $wp_customize;
			$this->customize_data = $wp_customize;
		}
		$this->use_img = $this->get_params( 'sc_icon_use_img' );
		wp_enqueue_style( 'vi-wcaio-cart-icons', VIWCAIO_CART_ALL_IN_ONE_CSS . 'cart-icons.min.css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
		$suffix = WP_DEBUG ? '' : 'min.';
		wp_enqueue_style( 'vi-wcaio-sidebar-cart-icon', VIWCAIO_CART_ALL_IN_ONE_CSS . 'sidebar-cart-icon.' . $suffix . 'css', array(), VIWCAIO_CART_ALL_IN_ONE_VERSION );
		if ( ! $this->is_customize ) {
			$css = $this->get_inline_css();
			wp_add_inline_style( 'vi-wcaio-sidebar-cart-icon', $css );
		}
		add_action( 'wp_footer', array( $this, 'frontend_html' ) );
	}
	public function frontend_html() {
		$class           = array(
			'vi-wcaio-sidebar-cart-icon-wrap',
			'vi-wcaio-sidebar-cart-icon-wrap-' . $sc_icon_position = $this->get_params( 'sc_icon_position' ),
			'vi-wcaio-sidebar-cart-icon-wrap-' . $sc_trigger_type = $this->get_params( 'sc_trigger_type' ),
		);
		$sc_empty_enable = $this->settings->get_params( 'sc_empty_enable' );
		if ( ! $this->is_customize ) {
			$class[] = ! $sc_empty_enable && WC()->cart->is_empty() ? 'vi-wcaio-disabled' : '';
		} elseif ( ! $this->get_params( 'sc_enable' ) || ! $this->get_params( 'sc_icon_enable' ) ) {
			$class[] = 'vi-wcaio-disabled';
		}
		$class = trim( implode( ' ', $class ) );
		?>
        <div class="<?php echo esc_attr( $class ); ?>"
             data-old_position="" data-position="<?php echo esc_attr( $sc_icon_position ); ?>"
             data-trigger="<?php echo esc_attr( $sc_trigger_type ); ?>">
			<?php
			do_action( 'vi_wcaio_get_sidebar_cart_icon' );
			?>
        </div>
		<?php
	}

	public function get_sidebar_cart_icon() {
		$sc_icon_style = $this->get_params( 'sc_icon_style' );
		$wrap_class    = array(
			'vi-wcaio-sidebar-cart-icon',
			'vi-wcaio-sidebar-cart-icon-' . $sc_icon_style,
		);
		$wrap_class[]  = $this->use_img ? 'vi-wcaio-sidebar-cart-icon-image' : '';
		$wrap_class    = trim( implode( ' ', $wrap_class ) );
		switch ( $sc_icon_style ) {
			case '1':
			case '2':
			case '3':
			case '5':
				?>
                <div class="<?php echo esc_attr( $wrap_class ); ?>" data-display_style="<?php echo esc_attr( $sc_icon_style ); ?>">
					<?php
					if ( $this->is_customize || ! $this->use_img ) {
						$icon_class = $icon_class ?? $this->settings->get_class_icon( $this->get_params( 'sc_icon_default_icon' ), 'cart_icons' );
						echo sprintf( '<i class="%s"></i>', esc_attr( $icon_class ) );
					}
					$sc_icon_count_type = $this->get_params( 'sc_icon_count_type' );
					if ( $sc_icon_count_type === 'cart_subtotal' && $sc_icon_style === '2' ) {
						$sc_icon_count_type = 'pd_count';
					}
					if ( $this->is_customize ) {
						$sc_icon_count_html = array(
							'item_count'    => WC()->cart ? count( WC()->cart->get_cart() ) : 0,
							'pd_count'      => WC()->cart ? WC()->cart->get_cart_contents_count() : 0,
							'cart_subtotal' => WC()->cart ? WC()->cart->get_cart_subtotal() : 0
						);
						$sc_icon_count_html = wp_json_encode( $sc_icon_count_html );
						$sc_icon_count_html = function_exists( 'wc_esc_json' ) ? wc_esc_json( $sc_icon_count_html ) : _wp_specialchars( $sc_icon_count_html, ENT_QUOTES, 'UTF-8', true );
						echo sprintf( '<div class="vi-wcaio-sidebar-cart-icon-count-html vi-wcaio-disabled" data-type="%s" data-count_html="%s"></div>', $sc_icon_count_type, $sc_icon_count_html );
					}
					?>
                    <div class="vi-wcaio-sidebar-cart-count-wrap vi-wcaio-sidebar-cart-count-wrap-<?php echo esc_attr( $sc_icon_count_type ); ?>">
                        <div class="vi-wcaio-sidebar-cart-count">
							<?php
							switch ( $sc_icon_count_type ) {
								case 'item_count':
									echo wp_kses_post( $sc_icon_count_html['item_count'] ?? ( WC()->cart ? count( WC()->cart->get_cart() ) : 0 ) );
									break;
								case 'cart_subtotal':
									echo wp_kses_post( $sc_icon_count_html['cart_subtotal'] ?? ( WC()->cart ? WC()->cart->get_cart_subtotal() : 0 ) );
									break;
								default:
									echo wp_kses_post( $sc_icon_count_html['pd_count'] ?? ( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ) );
							}
							?>
                        </div>
                    </div>
                </div>
				<?php
				break;
			default:
				?>
                <div class="<?php echo esc_attr( $wrap_class ); ?>">
					<?php
					if ( $this->is_customize || ! $this->use_img ) {
						$icon_class = $icon_class ?? $this->settings->get_class_icon( $this->get_params( 'sc_icon_default_icon' ), 'cart_icons' );
						echo sprintf( '<i class="%s"></i>', esc_attr( $icon_class ) );
					}
					?>
                </div>
			<?php
		}
	}
	public function get_inline_css() {
		$css           = '';
		$frontend      = 'VIWCAIO_CART_ALL_IN_ONE_Frontend_Frontend';
		$sc_horizontal = $this->settings->get_params( 'sc_icon_horizontal' ) ?: 0;
		$css           .= '.vi-wcaio-sidebar-cart-icon-wrap-top_left, .vi-wcaio-sidebar-cart-icon-wrap-bottom_left{';
		$css           .= 'left: ' . $sc_horizontal . 'px ;';
		$css           .= '}';
		$css           .= '.vi-wcaio-sidebar-cart-icon-wrap-top_right, .vi-wcaio-sidebar-cart-icon-wrap-bottom_right{';
		$css           .= 'right: ' . $sc_horizontal . 'px ;';
		$css           .= '}';
		$sc_vertical   = $this->settings->get_params( 'sc_icon_vertical' ) ?: 0;
		$css           .= '.vi-wcaio-sidebar-cart-icon-wrap-top_left, .vi-wcaio-sidebar-cart-icon-wrap-top_right{';
		$css           .= 'top: ' . $sc_vertical . 'px ;';
		$css           .= '}';
		$css           .= '.vi-wcaio-sidebar-cart-icon-wrap-bottom_right, .vi-wcaio-sidebar-cart-icon-wrap-bottom_left{';
		$css           .= 'bottom: ' . $sc_vertical . 'px ;';
		$css           .= '}';
		if ( $this->settings->get_params( 'sc_icon_box_shadow' ) ) {
			$css .= '.vi-wcaio-sidebar-cart-icon-wrap{
                box-shadow: inset 0 0 2px rgba(0,0,0,0.03), 0 4px 10px rgba(0,0,0,0.17);
            }';
		}
		if ( $sc_icon_scale = $this->settings->get_params( 'sc_icon_scale' ) ) {
			$css .= '.vi-wcaio-sidebar-cart-icon-wrap {
                transform: scale(' . $sc_icon_scale . ') ;
            }
            @keyframes vi-wcaio-cart-icon-slide_in_left {
                from {
                    transform: translate3d(-100%, 0, 0) scale(' . $sc_icon_scale . ');
                    visibility: hidden;
                }
                to {
                    transform: translate3d(0, 0, 0) scale(' . $sc_icon_scale . ');
                }
            }
            @keyframes vi-wcaio-cart-icon-slide_out_left {
                from {
                    transform: translate3d(0, 0, 0) scale(' . $sc_icon_scale . ');
                    visibility: visible;
                    opacity: 1;
                }
                to {
                    transform: translate3d(-100%, 0, 0) scale(' . $sc_icon_scale . ');
                    visibility: hidden;
                    opacity: 0;
                }
            }
            @keyframes vi-wcaio-cart-icon-shake_horizontal {
               0% {
		            transform: scale(' . $sc_icon_scale . ');
	            }
	           10%, 20% {
		            transform: scale(' . $sc_icon_scale . ') translateX(-10%);
	           }
	           30%, 50%, 70%, 90% {
		            transform: scale(' . $sc_icon_scale . ') translateX(10%);
	           }
	           40%, 60%, 80% {
		            transform: scale(' . $sc_icon_scale . ') translateX(-10%);
	           }
            	100% {
            		transform: scale(' . $sc_icon_scale . ');
            	}
            }
            @keyframes vi-wcaio-cart-icon-shake_vertical {
               0% {
		            transform: scale(' . $sc_icon_scale . ');
	            }
	           10%, 20% {
	                transform: scale(' . ( $sc_icon_scale * 0.9 ) . ') rotate3d(0, 0, 1, -3deg);
	           }
	           30%, 50%, 70%, 90% {
		            transform: scale(' . ( $sc_icon_scale * 1.1 ) . ') rotate3d(0, 0, 1, 3deg);
	           }
	           40%, 60%, 80% {
		            transform: scale(' . ( $sc_icon_scale * 1.1 ) . ') rotate3d(0, 0, 1, -3deg);
	           }
            	100% {
            		transform: scale(' . $sc_icon_scale . ');
            	}
            }';
		}
		if ( $sc_icon_hover_scale = $this->settings->get_params( 'sc_icon_hover_scale' ) ) {
			$css .= '@keyframes vi-wcaio-cart-icon-mouseenter {
                from {
                    transform: translate3d(0, 0, 0) scale(' . $sc_icon_scale . ');
                }
                to {
                    transform: translate3d(0, 0, 0) scale(' . $sc_icon_hover_scale . ');
                }
            }
            @keyframes vi-wcaio-cart-icon-mouseleave {
                from {
                    transform: translate3d(0, 0, 0) scale(' . $sc_icon_hover_scale . ');
                }
                to {
                    transform: translate3d(0, 0, 0) scale(' . $sc_icon_scale . ');
                }
            }
            @keyframes vi-wcaio-cart-icon-slide_out_left {
                from {
                    transform: translate3d(0, 0, 0) scale(' . $sc_icon_hover_scale . ');
                    visibility: visible;
                    opacity: 1;
                }
                to {
                    transform: translate3d(-100%, 0, 0) scale(' . $sc_icon_hover_scale . ');
                    visibility: hidden;
                    opacity: 0;
                }
            }
            @keyframes vi-wcaio-cart-icon-slide_out_right {
                from {
                    transform: translate3d(0, 0, 0) scale(' . $sc_icon_hover_scale . ');
                    visibility: visible;
                    opacity: 1;
                }
                to {
                    transform: translate3d(100%, 0, 0) scale(' . $sc_icon_hover_scale . ');
                    visibility: hidden;
                    opacity: 0;
                }
            }';
		}
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart-icon-wrap' ),
			array( 'sc_icon_border_radius', 'sc_icon_bg_color' ),
			array( 'border-radius', 'background' ),
			array( 'px', '' )
		);
		if ( $this->use_img ) {
			$css .= '.vi-wcaio-sidebar-cart-icon-image{background-image: url(' . $this->settings->get_params( 'sc_icon_img' ) . ');}';
		} else {
			$css .= $frontend::add_inline_style(
				array( '.vi-wcaio-sidebar-cart-icon-wrap .vi-wcaio-sidebar-cart-icon i' ),
				array( 'sc_icon_color' ),
				array( 'color' ),
				array( '' )
			);
		}
		$css .= $frontend::add_inline_style(
			array( '.vi-wcaio-sidebar-cart-icon-wrap .vi-wcaio-sidebar-cart-count-wrap' ),
			array( 'sc_icon_count_bg_color', 'sc_icon_count_color', 'sc_icon_count_border_radius' ),
			array( 'background', 'color', 'border-radius' ),
			array( '', '', 'px' )
		);
		if ( $this->settings->get_params( 'sc_icon_count_type' ) === 'cart_subtotal' ) {
			$css .= $frontend::add_inline_style(
				array( '.vi-wcaio-sidebar-cart-icon-wrap .vi-wcaio-sidebar-cart-count-wrap .amount' ),
				array( 'sc_icon_count_color' ),
				array( 'color' ),
				array( '' )
			);
		}
		$css = str_replace( array( "\r", "\n", "\t", '\r', '\n', '\t' ), ' ', $css );
		return $css;
	}
	public function assign_page() {
		if ( ! $this->settings->enable( 'sc_' ) || ! $this->settings->get_params( 'sc_icon_enable' ) ) {
			return false;
		}
		$assign_page = $this->settings->get_params( 'sc_assign_page' );
		if ( $assign_page ) {
			if ( stristr( $assign_page, "return" ) === false ) {
				$assign_page = "return (" . $assign_page . ");";
			}
			if ( ! eval( $assign_page ) ) {
				return false;
			}
		}
		return true;
	}

	private function get_params( $name = '' ) {
		if ( $this->customize_data && $name && $setting = $this->customize_data->get_setting( 'woo_cart_all_in_one_params[' . $name . ']' ) ) {
			return $this->customize_data->post_value( $setting, $this->settings->get_params( $name ) );
		} else {
			return $this->settings->get_params( $name );
		}
	}
}