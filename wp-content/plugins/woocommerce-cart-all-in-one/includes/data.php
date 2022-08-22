<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VIWCAIO_CART_ALL_IN_ONE_DATA {
	private $params, $default, $class_icons;
	protected $prefix;

	public function __construct() {
		global $vi_wcaio_settings;
		if ( ! $vi_wcaio_settings ) {
			$vi_wcaio_settings = get_option( 'woo_cart_all_in_one_params', array() );
		}
		if ( isset( $vi_wcaio_settings['sidebar_cart_enable'] ) || isset( $vi_wcaio_settings['sidebar_content_display'] ) ) {
			$vi_wcaio_settings_t                    = array();
			$vi_wcaio_settings_t['sc_enable']       = $vi_wcaio_settings['sidebar_cart_enable'] ?? '';
			$vi_wcaio_settings_t['sc_empty_enable'] = $vi_wcaio_settings['sidebar_cart_enable_empty'] ?? '';
			if ( isset( $vi_wcaio_settings['sidebar_cart_enable_device'] ) && in_array( $vi_wcaio_settings['sidebar_cart_enable_device'], [ 'all', 'mobile' ] ) ) {
				$vi_wcaio_settings_t['sc_mobile_enable'] = 1;
			}
			if ( ! empty( $vi_wcaio_settings['sidebar_cart_enable_pages'] ) ) {
				$sc_assign_page = array();
				if ( ! in_array( 'all', $vi_wcaio_settings['sidebar_cart_enable_pages'] ) ) {
					$sc_assign_page[] = in_array( 'shop', $vi_wcaio_settings['sidebar_cart_enable_pages'] ) ? 'is_shop()' : '';
					$sc_assign_page[] = in_array( 'category', $vi_wcaio_settings['sidebar_cart_enable_pages'] ) ? 'is_product_category()' : '';
					$sc_assign_page[] = in_array( 'product', $vi_wcaio_settings['sidebar_cart_enable_pages'] ) ? 'is_product()' : '';
					$sc_assign_page[] = in_array( 'my_account', $vi_wcaio_settings['sidebar_cart_enable_pages'] ) ? 'is_account_page()' : '';
				}
				$sc_assign_page                        = empty( $sc_assign_page ) ? '' : implode( ' || ', $sc_assign_page );
				$vi_wcaio_settings_t['sc_assign_page'] = $sc_assign_page;
			}
			$vi_wcaio_settings_t['mc_enable']       = $vi_wcaio_settings['menu_cart_enable'] ?? '';
			$vi_wcaio_settings_t['mc_empty_enable'] = $vi_wcaio_settings['menu_cart_enable_empty'] ?? '';
			if ( isset( $vi_wcaio_settings['menu_cart_enable_device'] ) && in_array( $vi_wcaio_settings['menu_cart_enable_device'], [ 'all', 'mobile' ] ) ) {
				$vi_wcaio_settings_t['mc_mobile_enable'] = 1;
			}
			$vi_wcaio_settings_t['mc_menu_display']                = $vi_wcaio_settings['menu_cart_enable_menu_type'] ?? array();
			$vi_wcaio_settings_t['ajax_atc']                       = $vi_wcaio_settings['ajax_add_to_cart_single_page'] ?? '';
			$vi_wcaio_settings_t['ajax_atc_pd_variable']           = $vi_wcaio_settings['show_variation_enable'] ?? '';
			$vi_wcaio_settings_t['pd_variable_bt_atc_text_enable'] = $vi_wcaio_settings['set_text_select_option_button_enable'] ?? '';
			$vi_wcaio_settings_t['pd_variable_bt_atc_text']        = $vi_wcaio_settings['set_text_select_option_button'] ?? '';
			$vi_wcaio_settings_t['custom_css']                             = $vi_wcaio_settings['custom_css'] ?? '';
			$vi_wcaio_settings_t['sc_display_type']                        = $vi_wcaio_settings['sidebar_content_display'] ?? 1;
			$vi_wcaio_settings_t['sc_position']                            = $vi_wcaio_settings['sidebar_position'] ?? 'bottom_left';
			$vi_wcaio_settings_t['sc_horizontal']                          = $vi_wcaio_settings['sidebar_horizontal'] ?? 20;
			$vi_wcaio_settings_t['sc_vertical']                            = $vi_wcaio_settings['sidebar_vertical'] ?? 20;
			$vi_wcaio_settings_t['sc_radius']                              = $vi_wcaio_settings['sidebar_cart_content_radius'] ?? 10;
			$vi_wcaio_settings_t['sc_effect_after_atc']                    = ! empty( $vi_wcaio_settings['sidebar_open'] ) ? 'open' : 0;
			$vi_wcaio_settings_t['sc_fly_to_cart']                         = $vi_wcaio_settings['sidebar_fly_img'] ?? 1;
			$vi_wcaio_settings_t['sc_trigger_type']                        = $vi_wcaio_settings['sidebar_show_cart_type'] ?? 'click';
			$vi_wcaio_settings_t['sc_loading_color']                       = $vi_wcaio_settings['mini_cart_loading_color'] ?? '#a0e224';
			$vi_wcaio_settings_t['sc_icon_style']                          = $vi_wcaio_settings['sidebar_cart_icon_default_style'] ?? 1;
			$vi_wcaio_settings_t['sc_icon_box_shadow']                     = $vi_wcaio_settings['sidebar_cart_icon_box_shadow'] ?? 0;
			$vi_wcaio_settings_t['sc_icon_scale']                          = $vi_wcaio_settings['sidebar_cart_icon_scale'] ?? 1;
			$vi_wcaio_settings_t['sc_icon_hover_scale']                    = $vi_wcaio_settings['sidebar_cart_icon_hover_scale'] ?? 1;
			$vi_wcaio_settings_t['sc_icon_border_radius']                  = $vi_wcaio_settings['sidebar_cart_icon_radius'] ?? 80;
			$vi_wcaio_settings_t['sc_icon_default_icon']                   = ! empty( $vi_wcaio_settings['sidebar_cart_icon_default_icon'] ) ? $vi_wcaio_settings['sidebar_cart_icon_default_icon'] - 1 : 32;
			$vi_wcaio_settings_t['sc_icon_bg_color']                       = $vi_wcaio_settings['sidebar_cart_icon_background'] ?? '#fff';
			$vi_wcaio_settings_t['sc_icon_color']                          = $vi_wcaio_settings['sidebar_cart_icon_default_color'] ?? '#d2691e';
			$vi_wcaio_settings_t['sc_icon_count_bg_color']                 = $vi_wcaio_settings['sidebar_cart_icon_text_background_color'] ?? '#20cc59';
			$vi_wcaio_settings_t['sc_icon_count_color']                    = $vi_wcaio_settings['sidebar_cart_icon_text_color'] ?? '#fff';
			$vi_wcaio_settings_t['sc_icon_count_border_radius']            = $vi_wcaio_settings['sidebar_cart_icon_text_radius'] ?? 25;
			$vi_wcaio_settings_t['sc_header_bg_color']                     = $vi_wcaio_settings['sidebar_header_background_color'] ?? '#fff';
			$vi_wcaio_settings_t['sc_header_border_style']                 = $vi_wcaio_settings['sidebar_header_border'] ?? 'solid';
			$vi_wcaio_settings_t['sc_header_border_color']                 = $vi_wcaio_settings['sidebar_header_border_color'] ?? '#e6e6e6';
			$vi_wcaio_settings_t['sc_header_title']                        = $vi_wcaio_settings['sidebar_header_title'] ?? 'Your Cart';
			$vi_wcaio_settings_t['sc_header_title_color']                  = $vi_wcaio_settings['sidebar_header_title_color'] ?? '#181818';
			$vi_wcaio_settings_t['sc_header_coupon_enable']                = $vi_wcaio_settings['sidebar_header_coupon_enable'] ?? 1;
			$vi_wcaio_settings_t['sc_header_coupon_input_radius']          = $vi_wcaio_settings['sidebar_header_coupon_input_radius'] ?? 0;
			$vi_wcaio_settings_t['sc_header_coupon_button_bg_color']       = $vi_wcaio_settings['sidebar_header_coupon_button_background'] ?? '#a4a7a9';
			$vi_wcaio_settings_t['sc_header_coupon_button_color']          = $vi_wcaio_settings['sidebar_header_coupon_button_text_color'] ?? '#fff';
			$vi_wcaio_settings_t['sc_header_coupon_button_bg_color_hover'] = $vi_wcaio_settings['sidebar_header_coupon_button_hover_background'] ?? '#a4a7a9';
			$vi_wcaio_settings_t['sc_header_coupon_button_color_hover']    = $vi_wcaio_settings['sidebar_header_coupon_button_text_color_hover'] ?? '#fff';
			$vi_wcaio_settings_t['sc_header_coupon_button_border_radius']  = $vi_wcaio_settings['sidebar_header_coupon_button_radius'] ?? 0;
			$vi_wcaio_settings_t['sc_footer_bg_color']                     = $vi_wcaio_settings['sidebar_footer_background_color'] ?? '#fff';
			$vi_wcaio_settings_t['sc_footer_border_type']                  = $vi_wcaio_settings['sidebar_footer_border'] ?? 'solid';
			$vi_wcaio_settings_t['sc_footer_border_color']                 = $vi_wcaio_settings['sidebar_footer_border_color'] ?? '#e6e6e6';
			$vi_wcaio_settings_t['sc_footer_cart_total']                   = $vi_wcaio_settings['sidebar_footer_price_enable'] ?? 'total';
			$vi_wcaio_settings_t['sc_footer_cart_total_color']             = $vi_wcaio_settings['sidebar_footer_total_color'] ?? '#181818';
			$vi_wcaio_settings_t['sc_footer_cart_total_color1']            = $vi_wcaio_settings['sidebar_footer_price_color'] ?? '#181818';
			$vi_wcaio_settings_t['sc_footer_button']                       = $vi_wcaio_settings['sidebar_footer_button_enable'] ?? 'checkout';
			$vi_wcaio_settings_t['sc_footer_bt_cart_text']                 = $vi_wcaio_settings['sidebar_footer_cart_button_text'] ?? 'View Cart';
			$vi_wcaio_settings_t['sc_footer_bt_checkout_text']             = $vi_wcaio_settings['sidebar_footer_checkout_button_text'] ?? 'CHECKOUT';
			$vi_wcaio_settings_t['sc_footer_button_bg_color']              = $vi_wcaio_settings['sidebar_footer_button_background'] ?? '#0888dd';
			$vi_wcaio_settings_t['sc_footer_button_color']                 = $vi_wcaio_settings['sidebar_footer_button_text_color'] ?? '#fff';
			$vi_wcaio_settings_t['sc_footer_button_hover_bg_color']        = $vi_wcaio_settings['sidebar_footer_button_hover_background'] ?? '#2795dd';
			$vi_wcaio_settings_t['sc_footer_button_hover_color']           = $vi_wcaio_settings['sidebar_footer_button_text_color_hover'] ?? '#fff';
			$vi_wcaio_settings_t['sc_footer_button_border_radius']         = $vi_wcaio_settings['sidebar_footer_button_radius'] ?? 0;
			$vi_wcaio_settings_t['sc_footer_bt_update_bg_color']           = $vi_wcaio_settings['sidebar_footer_update_button_background'] ?? '#a4a7a9';
			$vi_wcaio_settings_t['sc_footer_bt_update_color']              = $vi_wcaio_settings['sidebar_footer_update_button_text_color'] ?? '#fff';
			$vi_wcaio_settings_t['sc_footer_bt_update_hover_bg_color']     = $vi_wcaio_settings['sidebar_footer_update_button_hover_background'] ?? '#a4a7a9';
			$vi_wcaio_settings_t['sc_footer_bt_update_hover_color']        = $vi_wcaio_settings['sidebar_footer_update_button_text_color_hover'] ?? '#fff';
			$vi_wcaio_settings_t['sc_footer_bt_update_border_radius']      = $vi_wcaio_settings['sidebar_footer_update_button_radius'] ?? 0;
			$sidebar_footer_pro_plus_enable                                = $vi_wcaio_settings['sidebar_footer_pro_plus_enable'] ?? '';
			if ( in_array( $sidebar_footer_pro_plus_enable, [ 'best_selling', 'viewed_product', 'product_rating' ] ) ) {
				$vi_wcaio_settings_t['sc_footer_message'] = '{product_plus}';
				$vi_wcaio_settings_t['sc_footer_pd_plus'] = $sidebar_footer_pro_plus_enable;
				switch ( $sidebar_footer_pro_plus_enable ) {
					case 'best_selling':
						$vi_wcaio_settings_t['sc_footer_pd_plus_title'] = $vi_wcaio_settings['sidebar_footer_best_selling_text'] ?? 'Best selling products';
						break;
					case 'viewed_product':
						$vi_wcaio_settings_t['sc_footer_pd_plus_title'] = $vi_wcaio_settings['sidebar_footer_viewed_pro_text'] ?? 'Your recently viewed items';
						break;
					case 'product_rating':
						$vi_wcaio_settings_t['sc_footer_pd_plus_title'] = $vi_wcaio_settings['sidebar_footer_rating_pro_text'] ?? 'Top rated products';
						break;
				}
			}
			$vi_wcaio_settings_t['sc_footer_pd_plus_color']       = $vi_wcaio_settings['sidebar_footer_pro_plus_text_color'] ?? '#181818';
			$vi_wcaio_settings_t['sc_footer_pd_plus_limit']       = $vi_wcaio_settings['sidebar_footer_pro_plus_number'] ?? 5;
			$vi_wcaio_settings_t['sc_pd_bg_color']                = $vi_wcaio_settings['list_pro_background_color'] ?? '#fff';
			$vi_wcaio_settings_t['sc_pd_img_box_shadow']          = $vi_wcaio_settings['list_pro_image_box_shadow'] ?? 0;
			$vi_wcaio_settings_t['sc_pd_img_border_radius']       = $vi_wcaio_settings['list_pro_image_radius'] ?? 100;
			$vi_wcaio_settings_t['sc_pd_name_color']              = $vi_wcaio_settings['list_pro_name_color'] ?? '#2b3e51';
			$vi_wcaio_settings_t['sc_pd_name_hover_color']        = $vi_wcaio_settings['list_pro_hover_name_color'] ?? '#4096dd';
			$vi_wcaio_settings_t['sc_pd_price_color']             = $vi_wcaio_settings['list_pro_price_color'] ?? '#222';
			$vi_wcaio_settings_t['sc_pd_delete_icon']             = $vi_wcaio_settings['list_pro_remove_icon_style'] ?? 1;
			$vi_wcaio_settings_t['sc_pd_delete_icon_color']       = $vi_wcaio_settings['list_pro_remove_icon_color'] ?? '#808b97';
			$vi_wcaio_settings_t['sc_pd_delete_icon_hover_color'] = $vi_wcaio_settings['list_pro_remove_icon_color_hover'] ?? '#4096dd';
			$vi_wcaio_settings_t['mc_nav_page']                   = $vi_wcaio_settings['menu_cart_navigation_page'] ?? 1;
			$vi_wcaio_settings_t['mc_content']                    = $vi_wcaio_settings['menu_cart_show_content'] ?? 1;
			$vi_wcaio_settings_t['mc_icon']                       = ! empty( $vi_wcaio_settings['menu_cart_icon'] ) ? $vi_wcaio_settings['menu_cart_icon'] - 1 : 1;
			$vi_wcaio_settings_t['mc_icon_color']                 = $vi_wcaio_settings['menu_cart_icon_color'] ?? '';
			$vi_wcaio_settings_t['mc_icon_hover_color']           = $vi_wcaio_settings['menu_cart_icon_color_hover'] ?? '';
			$vi_wcaio_settings_t['mc_display_style']              = $vi_wcaio_settings['menu_cart_style_one_text'] ?? 'all';
			$vi_wcaio_settings_t['mc_cart_total']                 = $vi_wcaio_settings['menu_cart_style_one_price'] ?? 'total';
			$vi_wcaio_settings_t['mc_color']                      = $vi_wcaio_settings['menu_cart_style_one_text_color'] ?? '';
			$vi_wcaio_settings_t['mc_hover_color']                = $vi_wcaio_settings['menu_cart_style_one_text_color_hover'] ?? '';
			$vi_wcaio_settings                                    = $vi_wcaio_settings_t;
			update_option( 'woo_cart_all_in_one_params', $vi_wcaio_settings );
		}
		$cart              = array(
			//sidebar cart
			'sc_enable'                                 => 1,
			'sc_mobile_enable'                          => 1,
			'sc_empty_enable'                           => 1,
			'sc_icon_enable'                            => 1,
			'sc_assign_page'                            => '',
			'sc_content_class_open'                     => '',
			//menu cart
			'mc_enable'                                 => 0,
			'mc_mobile_enable'                          => 0,
			'mc_empty_enable'                         => 1,
			'mc_menu_display'                         => array(),
			//button add to cart
			'ajax_atc'                                => 0,
			'ajax_atc_pd_exclude'                     => array(),
			'ajax_atc_notice'                         => 0,
			'ajax_atc_pd_variable'                    => 0,
			'pd_variable_bt_atc_text_enable'          => 1,
			'pd_variable_bt_atc_text'                 => 'Add To Cart',
			//design
			'sc_display_type'                         => 2,
			'sc_position'                             => 'bottom_left',
			'sc_horizontal'                           => 20,
			'sc_vertical'                             => 10,
			'sc_radius'                               => 0,
			'sc_effect_after_atc'                     => 'shake_vertical',
			'sc_fly_to_cart'                          => 1,
			'sc_trigger_style'                        => 'slide',
			'sc_trigger_type'                         => 'click',
			'sc_loading'                              => 'default',
			'sc_loading_color'                        => '#ff9065',
			'sc_icon_position'                        => 'bottom_left',
			'sc_icon_horizontal'                      => 20,
			'sc_icon_vertical'                        => 10,
			'sc_icon_style'                           => 1,
			'sc_icon_box_shadow'                      => 1,
			'sc_icon_scale'                           => 1,
			'sc_icon_hover_scale'                     => 1,
			'sc_icon_border_radius'                   => 30,
			'sc_icon_use_img'                         => 0,
			'sc_icon_img'                             => '',
			'sc_icon_default_icon'                    => 2,
			'sc_icon_bg_color'                        => '#fff',
			'sc_icon_color'                           => '#ff9065',
			'sc_icon_count_type'                      => 'pd_count',
			'sc_icon_count_bg_color'                  => '#212121',
			'sc_icon_count_color'                     => '#fff',
			'sc_icon_count_border_radius'             => 10,
			'sc_header_bg_color'                      => '#fff',
			'sc_header_border_style'                  => 'solid',
			'sc_header_border_color'                  => '#e6e6e6',
			'sc_header_title'                         => 'YOUR CART',
			'sc_header_title_color'                   => '#181818',
			'sc_header_coupon_enable'                 => 1,
			'sc_header_coupon_input_radius'           => 0,
			'sc_header_coupon_button_bg_color'        => '#212121',
			'sc_header_coupon_button_color'           => '#fff',
			'sc_header_coupon_button_bg_color_hover'  => '#ff9065',
			'sc_header_coupon_button_color_hover'     => '#fff',
			'sc_header_coupon_button_border_radius'   => 0,
			'sc_footer_bg_color'                      => '#fff',
			'sc_footer_border_type'                   => 'solid',
			'sc_footer_border_color'                  => '#e6e6e6',
			'sc_footer_coupon'                          => 0,
			'sc_footer_cart_total'                      => 'subtotal',
			'sc_footer_cart_total_text'                 => 'Subtotal:',
			'sc_footer_cart_total_color'                => '#181818',
			'sc_footer_cart_total_color1'               => '#181818',
			'sc_footer_button'                          => 'checkout',
			'sc_footer_bt_cart_text'                    => 'View Cart',
			'sc_footer_bt_checkout_text'                => 'CHECKOUT',
			'sc_footer_button_bg_color'                 => '#ff9065',
			'sc_footer_button_color'                    => '#fff',
			'sc_footer_button_hover_bg_color'           => '#ff7b54',
			'sc_footer_button_hover_color'            => '#212121',
			'sc_footer_button_border_radius'          => 0,
			'sc_footer_bt_update_bg_color'            => '#a4a7a9',
			'sc_footer_bt_update_color'               => '#fff',
			'sc_footer_bt_update_hover_bg_color'      => '#181818',
			'sc_footer_bt_update_hover_color'         => '#fff',
			'sc_footer_bt_update_border_radius'       => 0,
			'sc_footer_message'                       => '{product_plus}',
			'sc_footer_pd_plus'                       => 'best_selling',
			'sc_footer_pd_plus_cats'                  => '',
			'sc_footer_pd_plus_products'              => '',
			'sc_footer_pd_plus_title'                 => 'BEST SELLING PRODUCTS',
			'sc_footer_pd_plus_color'                 => '#181818',
			'sc_footer_pd_plus_bt_atc_title'          => '{cart_icon}',
			'sc_footer_pd_plus_bt_atc_cart_icon'      => 1,
			'sc_footer_pd_plus_bt_atc_bg_color'       => '#181818',
			'sc_footer_pd_plus_bt_atc_color'          => '#fff',
			'sc_footer_pd_plus_bt_atc_hover_bg_color' => '#ff9065',
			'sc_footer_pd_plus_bt_atc_hover_color'    => '#fff',
			'sc_footer_pd_plus_limit'                 => 5,
			'sc_footer_pd_plus_out_of_stock'          => 0,
			'sc_pd_update_cart'                       => 1,
			'sc_pd_bg_color'                          => '#fff',
			'sc_pd_img_box_shadow'                    => 0,
			'sc_pd_img_border_radius'                 => 0,
			'sc_pd_name_link'                         => 1,
			'sc_pd_name_color'                        => '#2b3e51',
			'sc_pd_name_hover_color'                  => '#ff9065',
			'sc_pd_price_color'                       => '#222',
			'sc_pd_price_style'                       => 'price',
			'sc_pd_qty_border_color'                  => '#ddd',
			'sc_pd_qty_border_radius'                 => 3,
			'sc_pd_delete_icon'                       => 1,
			'sc_pd_delete_icon_font_size'             => '15',
			'sc_pd_delete_icon_color'                 => '#e6e6e6',
			'sc_pd_delete_icon_hover_color'             => '#fe2740',
			'sc_checkout_enable'                        => 1,
			'sc_checkout_mobile_enable'                 => 0,
			'sc_checkout_keyboard_nav'                  => 1,
			'sc_checkout_bg_color'                      => '#fff',
			'sc_checkout_login'                         => 0,
			'sc_checkout_billing_title'                 => 'Billing',
			'sc_checkout_shipping_title'                => 'Shipping',
			'sc_checkout_order_review_title'            => 'Order Review',
			'sc_checkout_payment_title'                 => 'Payments',
			'sc_checkout_billing_shipping'              => 1,
			'sc_checkout_billing_shipping_title'        => 'Billing & Shipping',
			'sc_checkout_order_payment'                 => 1,
			'sc_checkout_order_payment_title'           => 'Order & Payment',
			'sc_checkout_bt_btc_enable'                 => 1,
			'sc_checkout_bt_btc_title'                  => 'Back to Cart',
			'sc_checkout_bt_btc_icon'                   => 0,
			'sc_checkout_bt_btc_bg_color'               => '#e6e6e6',
			'sc_checkout_bt_btc_color'                  => '#fff',
			'sc_checkout_bt_btc_hover_bg_color'         => '#181818',
			'sc_checkout_bt_btc_hover_color'            => '#fff',
			'sc_checkout_bt_btc_border_radius'          => 0,
			'sc_checkout_bt_next_title'                 => '{next_title}',
			'sc_checkout_bt_next_bg_color'              => '#ff9065',
			'sc_checkout_bt_next_color'                 => '#fff',
			'sc_checkout_bt_next_hover_bg_color'        => '#181818',
			'sc_checkout_bt_next_hover_color'           => '#fff',
			'sc_checkout_bt_next_border_radius'         => 0,
			'sc_checkout_bt_pre_title'                  => '{pre_title}',
			'sc_checkout_bt_pre_bg_color'               => '#e6e6e6',
			'sc_checkout_bt_pre_color'                  => '#fff',
			'sc_checkout_bt_pre_hover_bg_color'         => '#181818',
			'sc_checkout_bt_pre_hover_color'            => '#fff',
			'sc_checkout_bt_pre_border_radius'          => 0,
			'sc_checkout_bt_place_order_title'          => '{order_button_text}',
			'sc_checkout_bt_place_order_bg_color'       => '#ff9065',
			'sc_checkout_bt_place_order_color'          => '#fff',
			'sc_checkout_bt_place_order_hover_bg_color' => '#181818',
			'sc_checkout_bt_place_order_hover_color'    => '#fff',
			'sc_checkout_bt_place_order_border_radius'  => 0,
			'sc_checkout_nav_bar'                       => 1,
			'sc_checkout_nav_bar_color'                 => '#eaeaea',
			'sc_checkout_nav_bar_hover_color'           => '#dadada',
			'sc_checkout_nav_bar_selected_color'        => '#ff9065',
			'mc_nav_page'                               => 'cart',
			'mc_content'                                => 1,
			'mc_icon'                                 => 1,
			'mc_icon_color'                           => '',
			'mc_icon_hover_color'                     => '',
			'mc_display_style'                        => 'all',
			'mc_cart_total'                           => 'subtotal',
			'mc_color'                                => '',
			'mc_hover_color'                          => '',
		);
		$sticky_atc        = array(
			'sb_enable'                      => 1,
			'sb_mobile_enable'               => 1,
			'sb_use_viwpvs'                  => 1,
			'sb_pd_exclude'                  => array(),
			'sb_cats_exclude'                => array(),
			'sb_always_appear'               => '',
			'sb_select_product'              => '',
			'sb_template'                    => 2,
			'sb_position'                    => 0,
			'sb_mobile_position'             => 0,
			'sb_bg_color'                    => '#fff',
			'sb_box_shadow_color'            => '#bababa',
			'sb_padding'                     => '5px',
			'sb_border_radius'               => 0,
			'sb_pd_review'                   => 0,
			'sb_pd_review_color'             => '',
			'sb_quantity'                    => 1,
			'sb_quantity_border_radius'      => 0,
			'sb_pd_img_width'                => 60,
			'sb_pd_img_height'               => 60,
			'sb_pd_name_color'               => '',
			'sb_pd_price_color1'             => '',
			'sb_pd_price_color2'             => '',
			'sb_bt_atc_title'                => '{cart_icon}',
			'sb_bt_atc_cart_icon'            => 5,
			'sb_bt_atc_bg_color'             => '#ff9065',
			'sb_bt_atc_color'                => '#fff',
			'sb_bt_atc_border_radius'        => 0,
			'sb_bt_atc_font_size'            => 15,
			'sb_mobile_bt_atc_bg_color'      => '#ff9065',
			'sb_mobile_bt_atc_color'         => '#fff',
			'sb_mobile_bt_atc_border_radius' => 0,
			'sb_mobile_bt_atc_font_size'     => 13,
			'sb_mobile_img'                  => 1,
		);
		$viewed_products   = array(
			'vp_enable'          => 1,
			'vp_mobile_enable'   => 0,
			'vp_title'           => 'Your recently viewed items',
			'vp_single_position' => 2,
			'vp_pd_column'       => 4,
			'vp_pd_limit'        => 4,
			'vp_slider_loop'     => 1,
			'vp_slider_move'     => 4,
			'vp_slider_auto'     => 0,
			'vp_slider_speed'    => 2000,
			'vp_slider_pause'    => 1,
		);
		$this->default     = array_merge(
			array(
				'purchased_code' => '',
				'custom_css'     => '',
			),
			$cart, $sticky_atc, $viewed_products
		);
		$this->params      = apply_filters( 'woo_cart_all_in_one_params', wp_parse_args( $vi_wcaio_settings, $this->default ) );
		$this->class_icons = array(
			'back_icons'     => array(
				'vi_wcaio_back_icons-back-arrow',
				'vi_wcaio_back_icons-back-arrow-1',
				'vi_wcaio_back_icons-back',
				'vi_wcaio_back_icons-left-arrow-2',
				'vi_wcaio_back_icons-previous',
				'vi_wcaio_back_icons-go-back-arrow',
				'vi_wcaio_back_icons-x-mark',
				'vi_wcaio_back_icons-back-button',
				'vi_wcaio_back_icons-return',
				'vi_wcaio_back_icons-undo',
				'vi_wcaio_back_icons-left-arrow-1',
				'vi_wcaio_back_icons-back-1',
				'vi_wcaio_back_icons-left-arrow',
				'vi_wcaio_cart_icon-clear-button',
			),
			'cart_icons_atc' => array(
				'vi_wcaio_cart_icon_atc-plus',
				'vi_wcaio_cart_icon_atc-plus-1',
				'vi_wcaio_cart_icon_atc-add-to-basket',
				'vi_wcaio_cart_icon_atc-shopping-cart-4',
				'vi_wcaio_cart_icon_atc-shopping-basket',
				'vi_wcaio_cart_icon_atc-add-to-cart-3',
				'vi_wcaio_cart_icon_atc-shopping-cart-2',
				'vi_wcaio_cart_icon_atc-cart',
				'vi_wcaio_cart_icon_atc-shopping-cart-5',
				'vi_wcaio_cart_icon_atc-add-to-cart-4',
				'vi_wcaio_cart_icon_atc-shopping-cart-6',
				'vi_wcaio_cart_icon_atc-add-to-cart-1',
				'vi_wcaio_cart_icon_atc-add-to-cart',
				'vi_wcaio_cart_icon_atc-add-to-shopping-cart-1',
				'vi_wcaio_cart_icon_atc-add-to-basket-1',
				'vi_wcaio_cart_icon_atc-add-to-cart-2',
				'vi_wcaio_cart_icon_atc-add-to-shopping-cart',
				'vi_wcaio_cart_icon_atc-shopping-cart-1',
				'vi_wcaio_cart_icon_atc-shopping-cart-3',
				'vi_wcaio_cart_icon_atc-shopping-cart',
			),
			'cart_icons'     => array(
				'vi_wcaio_cart_icon-commerce',
				'vi_wcaio_cart_icon-shopping-cart-13',
				'vi_wcaio_cart_icon-cart-of-ecommerce',
				'vi_wcaio_cart_icon-shopping-cart-with-product-inside',
				'vi_wcaio_cart_icon-plus',
				'vi_wcaio_cart_icon-shopping-store-cart',
				'vi_wcaio_cart_icon-shopping-cart-black-shape',
				'vi_wcaio_cart_icon-shopping-cart-2',
				'vi_wcaio_cart_icon-empty-shopping-cart',
				'vi_wcaio_cart_icon-supermarket-2',
				'vi_wcaio_cart_icon-cart-6',
				'vi_wcaio_cart_icon-shopping-cart-5',
				'vi_wcaio_cart_icon-sell',
				'vi_wcaio_cart_icon-supermarket-4',
				'vi_wcaio_cart_icon-supermarket-5',
				'vi_wcaio_cart_icon-shopping-cart-of-checkered-design',
				'vi_wcaio_cart_icon-shopping-cart-9',
				'vi_wcaio_cart_icon-buy',
				'vi_wcaio_cart_icon-grocery-trolley',
				'vi_wcaio_cart_icon-supermarket-6',
				'vi_wcaio_cart_icon-shopping-cart-4',
				'vi_wcaio_cart_icon-shopping-cart-11',
				'vi_wcaio_cart_icon-shopping-cart-16',
				'vi_wcaio_cart_icon-supermarket-3',
				'vi_wcaio_cart_icon-shopping-cart-15',
				'vi_wcaio_cart_icon-cart-1',
				'vi_wcaio_cart_icon-cart-7',
				'vi_wcaio_cart_icon-commerce-and-shopping',
				'vi_wcaio_cart_icon-shopping-cart-8',
				'vi_wcaio_cart_icon-cart-5',
				'vi_wcaio_cart_icon-supermarket',
				'vi_wcaio_cart_icon-shopping-cart-1',
				'vi_wcaio_cart_icon-online-shopping-cart',
				'vi_wcaio_cart_icon-cart-4',
				'vi_wcaio_cart_icon-shopping-cart-14',
				'vi_wcaio_cart_icon-shopping-cart-3',
				'vi_wcaio_cart_icon-cart-3',
				'vi_wcaio_cart_icon-shopping-cart-6',
				'vi_wcaio_cart_icon-shopping-cart-10',
				'vi_wcaio_cart_icon-shopping-cart-12',
				'vi_wcaio_cart_icon-cart-2',
				'vi_wcaio_cart_icon-commerce-1',
				'vi_wcaio_cart_icon-shopping-cart',
				'vi_wcaio_cart_icon-shopping-cart-7',
				'vi_wcaio_cart_icon-supermarket-1',
			),
			'delete_icons'   => array(
				'vi_wcaio_cart_icon-clear-button',
				'vi_wcaio_cart_icon-rubbish-bin-delete-button',
				'vi_wcaio_cart_icon-delete-1',
				'vi_wcaio_cart_icon-waste-bin',
				'vi_wcaio_cart_icon-trash',
				'vi_wcaio_cart_icon-garbage-1',
				'vi_wcaio_cart_icon-delete-button',
				'vi_wcaio_cart_icon-delete',
				'vi_wcaio_cart_icon-rubbish-bin',
				'vi_wcaio_cart_icon-dustbin',
				'vi_wcaio_cart_icon-garbage',
			),
		);
	}

	public function get_class_icons( $type = '' ) {
		if ( ! $type ) {
			return $this->class_icons;
		}
		return $this->class_icons[ $type ] ?? array();
	}

	public function get_class_icon( $index = 0, $type = '' ) {
		if ( ! $type ) {
			return false;
		}
		$icons = $this->get_class_icons( $type ) ?? array();
		if ( empty( $icons ) ) {
			return false;
		} else {
			return $icons[ $index ] ?? $icons[0];
		}
	}

	public function enable( $prefix ) {
		if ( ! $prefix ) {
			return false;
		}
		if ( ! $this->get_params( $prefix . 'enable' ) ) {
			return false;
		}
		if ( wp_is_mobile() && ! $this->get_params( $prefix . 'mobile_enable' ) ) {
			return false;
		}
		return true;
	}

	public function get_params( $name = "", $language = '' ) {
		if ( ! $name ) {
			return $this->params;
		}
		if ( strpos( $language, '_' ) !== 0 ) {
			$language = '_' . $language;
		}
		$name_t = $name . $language;
		return apply_filters( 'woo_cart_all_in_one_params_' . $name_t, $this->params[ $name_t ] ?? $this->params[ $name ] ?? false );
	}

	public function get_default( $name = "" ) {
		if ( ! $name ) {
			return $this->default;
		} elseif ( isset( $this->default[ $name ] ) ) {
			return apply_filters( 'woo_cart_all_in_one_params_default-' . $name, $this->default[ $name ] );
		} else {
			return false;
		}
	}

	public function set( $name ) {
		if ( is_array( $name ) ) {
			return implode( ' ', array_map( array( $this, 'set' ), $name ) );
		} else {
			return esc_attr__( $this->prefix . $name );
		}
	}

	public function add_inline_style( $element, $name, $style, $suffix = '' ) {
		if ( ! $element || ! is_array( $element ) ) {
			return '';
		}
		$element = implode( ',', $element );
		$return  = $element . '{';
		if ( is_array( $name ) && count( $name ) ) {
			foreach ( $name as $key => $value ) {
				$get_value  = $this->get_params( $value );
				$get_suffix = $suffix[ $key ] ?? '';
				$return     .= $style[ $key ] . ':' . $get_value . $get_suffix . ';';
			}
		}
		$return .= '}';
		return $return;
	}

	public static function extend_post_allowed_html() {
		return array_merge( wp_kses_allowed_html( 'post' ), array(
				'input' => array(
					'type'         => 1,
					'id'           => 1,
					'name'         => 1,
					'class'        => 1,
					'placeholder'  => 1,
					'autocomplete' => 1,
					'style'        => 1,
					'value'        => 1,
					'data-*'       => 1,
					'size'         => 1,
				),
				'form'  => array(
					'type'   => 1,
					'id'     => 1,
					'name'   => 1,
					'class'  => 1,
					'style'  => 1,
					'method' => 1,
					'action' => 1,
					'data-*' => 1,
				),
				'style' => array(
					'id'    => 1,
					'class' => 1,
					'type'  => 1,
				),
			)
		);
	}
}