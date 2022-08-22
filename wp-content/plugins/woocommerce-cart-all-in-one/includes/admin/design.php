<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VIWCAIO_CART_ALL_IN_ONE_Admin_Design {
	protected $settings, $admin, $customize;
	protected $default_language, $languages, $languages_data;

	public function __construct() {
		$this->settings         = new VIWCAIO_CART_ALL_IN_ONE_DATA();
		$this->admin            = 'VIWCAIO_CART_ALL_IN_ONE_Admin_Settings';
		$this->languages        = array();
		$this->languages_data   = array();
		$this->default_language = '';
		add_action( 'customize_register', array( $this, 'design_option_customizer' ) );
		add_action( 'customize_preview_init', array( $this, 'customize_preview_init' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_controls_enqueue_scripts' ) );
		add_action( 'wp_print_styles', array( $this, 'customize_controls_print_styles' ) );
	}

	public function customize_controls_print_styles() {
		if ( ! is_customize_preview() ) {
			return;
		}
		global $wp_customize;
		$this->customize = $wp_customize;
		?>
        <style type="text/css" id="vi-wcaio-preview-sc_horizontal">
            <?php
            $sc_horizontal = $this->get_params_customize('sc_horizontal') ?: 0;
            $sc_horizontal_mobile = $sc_horizontal > 20 ? 20- $sc_horizontal : 0;
            ?>
            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_left,
            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_left {
                left: <?php echo sprintf('%spx',$sc_horizontal); ?>;
            }

            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_right,
            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_right {
                right: <?php echo sprintf('%spx',$sc_horizontal); ?>;
            }

            @media screen and (max-width: 768px) {
                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_left .vi-wcaio-sidebar-cart-content-wrap,
                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_left .vi-wcaio-sidebar-cart-content-wrap {
                    left: <?php echo sprintf('%spx', $sc_horizontal_mobile); ?>;
                }

                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_right .vi-wcaio-sidebar-cart-content-wrap,
                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_right .vi-wcaio-sidebar-cart-content-wrap {
                    right: <?php echo sprintf('%spx',$sc_horizontal_mobile); ?>;
                }
            }
        </style>
        <style type="text/css" id="vi-wcaio-preview-sc_vertical">
            <?php
            $sc_vertical = $this->get_params_customize('sc_vertical') ?: 0;
            $sc_vertical_mobile = $sc_vertical > 20 ? 20- $sc_vertical : 0;
            ?>
            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_left,
            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_right {
                top: <?php echo sprintf('%spx',$sc_vertical); ?>;
            }

            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_right,
            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_left {
                bottom: <?php echo sprintf('%spx',$sc_vertical); ?>;
            }

            @media screen and (max-width: 768px) {
                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_left .vi-wcaio-sidebar-cart-content-wrap,
                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_right .vi-wcaio-sidebar-cart-content-wrap {
                    top: <?php echo sprintf('%spx', $sc_vertical_mobile); ?>;
                }

                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_right .vi-wcaio-sidebar-cart-content-wrap,
                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_left .vi-wcaio-sidebar-cart-content-wrap {
                    bottom: <?php echo sprintf('%spx',$sc_vertical_mobile); ?>;
                }
            }
        </style>
        <style type="text/css" id="vi-wcaio-preview-sc_icon_horizontal">
            <?php
            $sc_icon_horizontal = $this->get_params_customize('sc_icon_horizontal') ?: 0;
            ?>
            .vi-wcaio-sidebar-cart-icon-wrap-top_left, .vi-wcaio-sidebar-cart-icon-wrap-bottom_left {
                left: <?php echo sprintf('%spx',$sc_icon_horizontal); ?>;
            }

            .vi-wcaio-sidebar-cart-icon-wrap-top_right, .vi-wcaio-sidebar-cart-icon-wrap-bottom_right {
                right: <?php echo sprintf('%spx',$sc_icon_horizontal); ?>;
            }
        </style>
        <style type="text/css" id="vi-wcaio-preview-sc_icon_vertical">
            <?php
            $sc_icon_vertical = $this->get_params_customize('sc_icon_vertical') ?: 0;
            ?>
            .vi-wcaio-sidebar-cart-icon-wrap-top_left, .vi-wcaio-sidebar-cart-icon-wrap-top_right {
                top: <?php echo sprintf('%spx',$sc_icon_vertical); ?>;
            }

            .vi-wcaio-sidebar-cart-icon-wrap-bottom_left, .vi-wcaio-sidebar-cart-icon-wrap-bottom_right {
                bottom: <?php echo sprintf('%spx',$sc_icon_vertical); ?>;
            }
        </style>
        <style type="text/css" id="vi-wcaio-preview-sc_icon_box_shadow">
            <?php
            if ($this->get_params_customize('sc_icon_box_shadow')){
                ?>
            .vi-wcaio-sidebar-cart-icon-wrap {
                box-shadow: inset 0 0 2px rgba(0, 0, 0, 0.03), 0 4px 10px rgba(0, 0, 0, 0.17);
            }

            <?php
            }
             ?>
        </style>
        <style type="text/css" id="vi-wcaio-preview-sc_icon_scale">
            <?php
            $sc_icon_scale = $this->get_params_customize('sc_icon_scale') ?: 1;
                ?>
            .vi-wcaio-sidebar-cart-icon-wrap {
                transform: scale(<?php echo esc_html($sc_icon_scale); ?>);
            }

            @keyframes vi-wcaio-cart-icon-slide_in_left {
                from {
                    transform: translate3d(-100%, 0, 0) scale(<?php echo esc_html($sc_icon_scale); ?>);
                    visibility: hidden;
                }
                to {
                    transform: translate3d(0, 0, 0) scale(<?php echo esc_html($sc_icon_scale); ?>);
                }
            }

            @keyframes vi-wcaio-cart-icon-slide_in_right {
                from {
                    transform: translate3d(100%, 0, 0) scale(<?php echo esc_html($sc_icon_scale); ?>);
                    visibility: hidden;
                }
                to {
                    transform: translate3d(0, 0, 0) scale(<?php echo esc_html($sc_icon_scale); ?>);
                }
            }
        </style>
        <style type="text/css" id="vi-wcaio-preview-sc_icon_hover_scale">
            <?php
            $sc_icon_hover_scale =$this->get_params_customize('sc_icon_hover_scale') ?: 1;
                ?>
            @keyframes vi-wcaio-cart-icon-mouseenter {
                from {
                    transform: translate3d(0, 0, 0) scale(<?php echo esc_html($sc_icon_scale); ?>);
                }
                to {
                    transform: translate3d(0, 0, 0) scale(<?php echo esc_html($sc_icon_hover_scale); ?>);
                }
            }

            @keyframes vi-wcaio-cart-icon-mouseleave {
                from {
                    transform: translate3d(0, 0, 0) scale(<?php echo esc_html($sc_icon_hover_scale); ?>);
                }
                to {
                    transform: translate3d(0, 0, 0) scale(<?php echo esc_html($sc_icon_scale); ?>);
                }
            }

            @keyframes vi-wcaio-cart-icon-slide_out_left {
                from {
                    transform: translate3d(0, 0, 0) scale(<?php echo esc_html($sc_icon_hover_scale); ?>);
                    visibility: visible;
                    opacity: 1;
                }
                to {
                    transform: translate3d(-100%, 0, 0) scale(<?php echo esc_html($sc_icon_hover_scale); ?>);
                    visibility: hidden;
                    opacity: 0;
                }
            }

            @keyframes vi-wcaio-cart-icon-slide_out_right {
                from {
                    transform: translate3d(0, 0, 0) scale(<?php echo esc_html($sc_icon_hover_scale); ?>);
                    visibility: visible;
                    opacity: 1;
                }
                to {
                    transform: translate3d(100%, 0, 0) scale(<?php echo esc_html($sc_icon_hover_scale); ?>);
                    visibility: hidden;
                    opacity: 0;
                }
            }
        </style>
        <style type="text/css" id="vi-wcaio-preview-sc_pd_img_box_shadow">
            <?php
            if ($this->get_params_customize('sc_pd_img_box_shadow')){
                ?>
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-img-wrap img {
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.07);
            }

            <?php
            }
             ?>
        </style>
        <style type="text/css" id="vi-wcaio-preview-sc_loading_color">
            <?php
            if ($sc_loading_color = $this->get_params_customize('sc_loading_color')){
                ?>
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-dual_ring:after {
                border-color: <?php echo esc_html($sc_loading_color); ?> transparent <?php echo esc_html($sc_loading_color); ?>  transparent;
            }

            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-ring div {
                border-color: <?php echo esc_html($sc_loading_color); ?> transparent transparent transparent;
            }

            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-ripple div {
                border: 4px solid<?php echo esc_html($sc_loading_color); ?>;
            }

            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-default div,
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-animation_face_1 div,
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-animation_face_2 div,
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-roller div:after,
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-loader_balls_1 div,
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-loader_balls_2 div,
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-loader_balls_3 div,
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-spinner div:after {
                background: <?php echo esc_html($sc_loading_color); ?>;
            }

            <?php
            }
             ?>
        </style>
        <style type="text/css" id="vi-wcaio-preview-sb_box_shadow_color">
            <?php
            if ($sb_box_shadow_color = $this->get_params_customize('sb_box_shadow_color')){
                ?>
            .vi-wcaio-sb-container {
                box-shadow: 0 1px 4px 0<?php echo esc_html($sb_box_shadow_color); ?>;
            }

            <?php
            }
             ?>
        </style>
		<?php
		$this->add_preview_style( 'sc_radius', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-content-wrap', 'border-radius', 'px' );
		$this->add_preview_style( 'sc_icon_border_radius', '.vi-wcaio-sidebar-cart-icon-wrap', 'border-radius', 'px' );
		$this->add_preview_style( 'sc_icon_bg_color', '.vi-wcaio-sidebar-cart-icon-wrap', 'background', '' );
		$this->add_preview_style( 'sc_icon_color', '.vi-wcaio-sidebar-cart-icon-wrap .vi-wcaio-sidebar-cart-icon i', 'color', '' );
		$this->add_preview_style( 'sc_icon_count_bg_color', '.vi-wcaio-sidebar-cart-icon-wrap .vi-wcaio-sidebar-cart-count-wrap', 'background', '' );
		$this->add_preview_style( 'sc_icon_count_color',
			'.vi-wcaio-sidebar-cart-icon-wrap .vi-wcaio-sidebar-cart-count-wrap, .vi-wcaio-sidebar-cart-icon-wrap .vi-wcaio-sidebar-cart-count-wrap .amount',
			'color', '' );
		$this->add_preview_style( 'sc_icon_count_border_radius', '.vi-wcaio-sidebar-cart-icon-wrap .vi-wcaio-sidebar-cart-count-wrap', 'border-radius', 'px' );
		$this->add_preview_style( 'sc_header_bg_color', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap', 'background', '' );
		$this->add_preview_style( 'sc_header_border_style', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap', 'border-style', '' );
		$this->add_preview_style( 'sc_header_border_color', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap', 'border-color', '' );
		$this->add_preview_style( 'sc_header_title_color', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-title-wrap', 'color', '' );
		$this->add_preview_style( 'sc_header_coupon_input_radius',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-coupon-wrap .vi-wcaio-coupon-code',
			'border-radius', 'px' );
		$this->add_preview_style( 'sc_header_coupon_button_bg_color',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-coupon-wrap button.vi-wcaio-bt-coupon-code',
			'background', '' );
		$this->add_preview_style( 'sc_header_coupon_button_color',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-coupon-wrap button.vi-wcaio-bt-coupon-code',
			'color', '' );
		$this->add_preview_style( 'sc_header_coupon_button_bg_color_hover',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-coupon-wrap button.vi-wcaio-bt-coupon-code:hover',
			'background', '' );
		$this->add_preview_style( 'sc_header_coupon_button_color_hover',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-coupon-wrap button.vi-wcaio-bt-coupon-code:hover',
			'color', '' );
		$this->add_preview_style( 'sc_header_coupon_button_border_radius',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-coupon-wrap button.vi-wcaio-bt-coupon-code',
			'border-radius', 'px' );
		$this->add_preview_style( 'sc_footer_bg_color', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap', 'background', '' );
		$this->add_preview_style( 'sc_footer_border_type', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap', 'border-style', '' );
		$this->add_preview_style( 'sc_footer_border_color', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap', 'border-color', '' );
		$this->add_preview_style( 'sc_footer_cart_total_color',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-footer-cart_total > div:nth-child(1)',
			'color', '' );
		$this->add_preview_style( 'sc_footer_cart_total_color1',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-footer-cart_total > div:nth-child(2)',
			'color', '' );
		$this->add_preview_style( 'sc_footer_button_bg_color',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt.vi-wcaio-sidebar-cart-bt-nav',
			'background', '' );
		$this->add_preview_style( 'sc_footer_button_color',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt.vi-wcaio-sidebar-cart-bt-nav',
			'color', '' );
		$this->add_preview_style( 'sc_footer_button_hover_bg_color',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt.vi-wcaio-sidebar-cart-bt-nav:hover',
			'background', '' );
		$this->add_preview_style( 'sc_footer_button_hover_color',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt.vi-wcaio-sidebar-cart-bt-nav:hover',
			'color', '' );
		$this->add_preview_style( 'sc_footer_button_border_radius',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt.vi-wcaio-sidebar-cart-bt-nav',
			'border-radius', 'px' );
		$this->add_preview_style( 'sc_footer_bt_update_bg_color',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap button.vi-wcaio-sidebar-cart-bt-update',
			'background', '' );
		$this->add_preview_style( 'sc_footer_bt_update_color',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap button.vi-wcaio-sidebar-cart-bt-update',
			'color', '' );
		$this->add_preview_style( 'sc_footer_bt_update_hover_bg_color',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap button.vi-wcaio-sidebar-cart-bt-update:hover',
			'background', '' );
		$this->add_preview_style( 'sc_footer_bt_update_hover_color',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap button.vi-wcaio-sidebar-cart-bt-update:hover',
			'color', '' );
		$this->add_preview_style( 'sc_footer_bt_update_border_radius',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap button.vi-wcaio-sidebar-cart-bt-update',
			'border-radius', 'px' );
		$this->add_preview_style( 'sc_footer_pd_plus_color',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-footer-pd-plus-title',
			'color', '' );
		$this->add_preview_style( 'sc_pd_bg_color', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products-wrap', 'background', '' );
		$this->add_preview_style( 'sc_pd_img_border_radius', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-img-wrap img', 'border-radius', 'px' );
		$this->add_preview_style( 'sc_pd_name_color',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-info-wrap .vi-wcaio-sidebar-cart-pd-name-wrap .vi-wcaio-sidebar-cart-pd-name, .vi-wcaio-sidebar-cart-footer-pd-name *',
			'color', '' );
		$this->add_preview_style( 'sc_pd_name_hover_color',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-info-wrap .vi-wcaio-sidebar-cart-pd-name-wrap .vi-wcaio-sidebar-cart-pd-name:hover, .vi-wcaio-sidebar-cart-footer-pd-name *:hover',
			'color', '' );
		$this->add_preview_style( 'sc_pd_price_color',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-info-wrap .vi-wcaio-sidebar-cart-pd-price *, .vi-wcaio-sidebar-cart-footer-pd-price *',
			'color', '' );
		$this->add_preview_style( 'sc_pd_delete_icon_font_size',
			'.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-remove-wrap i:before', 'font-size', 'px' );
		$this->add_preview_style( 'sc_pd_delete_icon_color', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-remove-wrap i', 'color', '' );
		$this->add_preview_style( 'sc_pd_delete_icon_hover_color', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-remove-wrap i:hover', 'color', '' );
		$this->add_preview_style( 'mc_icon_color', '.vi-wcaio-menu-cart .vi-wcaio-menu-cart-icon i', 'color', '' );
		$this->add_preview_style( 'mc_icon_hover_color', '.vi-wcaio-menu-cart .vi-wcaio-menu-cart-nav-wrap:hover .vi-wcaio-menu-cart-icon i', 'color', '' );
		$this->add_preview_style( 'mc_color', '.vi-wcaio-menu-cart .vi-wcaio-menu-cart-text-wrap *', 'color', '' );
		$this->add_preview_style( 'mc_hover_color', '.vi-wcaio-menu-cart .vi-wcaio-menu-cart-nav-wrap:hover .vi-wcaio-menu-cart-text-wrap *', 'color', '' );
		$this->add_preview_style( 'sb_bg_color', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview', 'background' );
		$this->add_preview_style( 'sb_padding', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview', 'padding' );
		$this->add_preview_style( 'sb_border_radius', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview', 'border-radius', 'px' );
		$this->add_preview_style( 'sb_quantity_border_radius', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-wrap .quantity', 'border-radius', 'px' );
		$this->add_preview_style( 'sb_pd_name_color', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-product-title', 'color' );
		$this->add_preview_style( 'sb_pd_price_color2', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-product-price-wrap .price', 'color' );
		$this->add_preview_style( 'sb_pd_price_color1', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-product-price-wrap .price del', 'color' );
		$this->add_preview_style( 'sb_pd_review_color', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-product-rating-wrap *:before', 'color' );
		$this->add_preview_style( 'sb_bt_atc_bg_color', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview button.vi-wcaio-product-bt-atc', 'background' );
		$this->add_preview_style( 'sb_bt_atc_color', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview button.vi-wcaio-product-bt-atc', 'color' );
		$this->add_preview_style( 'sb_bt_atc_border_radius', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview button.vi-wcaio-product-bt-atc', 'border-radius', 'px' );
		$this->add_preview_style( 'sb_bt_atc_font_size', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview button.vi-wcaio-product-bt-atc', 'font-size', 'px' );
		?>
        <style type="text/css" id="vi-wcaio-preview-custom_css">
            <?php
            if ($custom_css = $this->get_params_customize('custom_css')){
                echo wp_kses_post($custom_css);
            }
             ?>
        </style>
        <style type="text/css" id="vi-wcaio-preview-sb_pd_img_width">
            <?php
            if ($sb_pd_img_width = $this->get_params_customize('sb_pd_img_width')){
                ?>
            .vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-product-img-wrap,
            .vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-product-img-wrap img {
                width: <?php echo sprintf('%spx',$sb_pd_img_width); ?>;
                max-width: <?php echo sprintf('%spx',$sb_pd_img_width); ?>;
            }

            <?php
            }
             ?>
        </style>
        <style type="text/css" id="vi-wcaio-preview-sb_pd_img_height">
            <?php
            if ($sb_pd_img_height = $this->get_params_customize('sb_pd_img_height')){
                ?>
            .vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-product-img-wrap,
            .vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-product-img-wrap img {
                height: <?php echo sprintf('%spx',$sb_pd_img_height); ?>;
                max-height: <?php echo sprintf('%spx',$sb_pd_img_height); ?>;
            }

            <?php
            }
             ?>
        </style>
        <style type="text/css" id="vi-wcaio-preview-sc_pd_qty_border_color">
            <?php
            if ($sc_pd_qty_border_color = $this->get_params_customize('sc_pd_qty_border_color')){
                ?>
            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-desc .vi-wcaio-sidebar-cart-pd-quantity {
                border: 1px solid<?php echo esc_html($sc_pd_qty_border_color); ?>;
            }

            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-desc .vi_wcaio_minus {
                border-right: 1px solid<?php echo esc_html($sc_pd_qty_border_color); ?>;
            }

            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-desc .vi_wcaio_plus {
                border-left: 1px solid<?php echo esc_html($sc_pd_qty_border_color); ?>;
            }

            <?php
            }
             ?>
        </style>
		<?php
		$this->add_preview_style( 'sc_pd_qty_border_radius', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-desc .vi-wcaio-sidebar-cart-pd-quantity',
			'border-radius', 'px' );
		$this->add_preview_style( 'sc_footer_pd_plus_bt_atc_bg_color', '.vi-wcaio-sidebar-cart .button.vi-wcaio-pd_plus-product-bt-atc',
			'background', '' );
		$this->add_preview_style( 'sc_footer_pd_plus_bt_atc_color', '.vi-wcaio-sidebar-cart .button.vi-wcaio-pd_plus-product-bt-atc',
			'color', '' );
		$this->add_preview_style( 'sc_footer_pd_plus_bt_atc_hover_bg_color', '.vi-wcaio-sidebar-cart .button.vi-wcaio-pd_plus-product-bt-atc:hover',
			'background', '' );
		$this->add_preview_style( 'sc_footer_pd_plus_bt_atc_hover_color', '.vi-wcaio-sidebar-cart .button.vi-wcaio-pd_plus-product-bt-atc:hover',
			'color', '' );
		$this->add_preview_style( 'sc_checkout_bg_color', '.vi-wcaio-sidebar-cart-content-wrap1.vi-wcaio-sidebar-cart-checkout-wrap',
			'background', '' );
		$this->add_preview_style( 'sc_checkout_bt_btc_bg_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-cancel',
			'background', '' );
		$this->add_preview_style( 'sc_checkout_bt_btc_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-cancel',
			'color', '' );
		$this->add_preview_style( 'sc_checkout_bt_btc_hover_bg_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-cancel:hover',
			'background', '' );
		$this->add_preview_style( 'sc_checkout_bt_btc_hover_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-cancel:hover',
			'color', '' );
		$this->add_preview_style( 'sc_checkout_bt_btc_border_radius', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-cancel',
			'border-radius', 'px' );
		$this->add_preview_style( 'sc_checkout_bt_next_border_radius', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-next',
			'border-radius', 'px' );
		$this->add_preview_style( 'sc_checkout_bt_next_bg_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-next',
			'background', '' );
		$this->add_preview_style( 'sc_checkout_bt_next_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-next',
			'color', '' );
		$this->add_preview_style( 'sc_checkout_bt_next_hover_bg_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-next:hover',
			'background', '' );
		$this->add_preview_style( 'sc_checkout_bt_next_hover_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-next:hover',
			'color', '' );
		$this->add_preview_style( 'sc_checkout_bt_pre_border_radius', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-back',
			'border-radius', 'px' );
		$this->add_preview_style( 'sc_checkout_bt_pre_bg_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-back',
			'background', '' );
		$this->add_preview_style( 'sc_checkout_bt_pre_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-back',
			'color', '' );
		$this->add_preview_style( 'sc_checkout_bt_pre_hover_bg_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-back:hover',
			'background', '' );
		$this->add_preview_style( 'sc_checkout_bt_pre_hover_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-back:hover',
			'color', '' );
		$this->add_preview_style( 'sc_checkout_bt_place_order_border_radius', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-place_order',
			'border-radius', 'px' );
		$this->add_preview_style( 'sc_checkout_bt_place_order_bg_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-place_order',
			'background', '' );
		$this->add_preview_style( 'sc_checkout_bt_place_order_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-place_order',
			'color', '' );
		$this->add_preview_style( 'sc_checkout_bt_place_order_hover_bg_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-place_order:hover',
			'background', '' );
		$this->add_preview_style( 'sc_checkout_bt_place_order_hover_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-place_order:hover',
			'color', '' );
		$this->add_preview_style( 'sc_checkout_nav_bar_color', '.vi-wcaio-sidebar-cart-wrap .vi-wcaio-checkout-nav-step-wrap .vi-wcaio-checkout-nav-step',
			'background', '' );
		$this->add_preview_style( 'sc_checkout_nav_bar_hover_color', '.vi-wcaio-sidebar-cart-wrap .vi-wcaio-checkout-nav-step-wrap .vi-wcaio-checkout-nav-step:hover',
			'background', '' );
		$this->add_preview_style( 'sc_checkout_nav_bar_selected_color',
			'.vi-wcaio-sidebar-cart-wrap .vi-wcaio-checkout-nav-step-wrap .vi-wcaio-checkout-nav-step.vi-wcaio-checkout-step-current',
			'background', '' );
	}

	private function add_preview_style( $name, $element, $style, $suffix = '' ) {
		$id = 'vi-wcaio-preview-' . $name;
		?>
        <style type="text/css" id="<?php echo esc_attr( $id ); ?>">
            <?php
            $css = $element.'{';
            if($value = $this->get_params_customize($name)){
                $css .= $style.': '.$value.$suffix.' ;';
            }
            $css .= '}';
            echo wp_kses_post($css);
             ?>
        </style>
		<?php
	}
	protected function get_params_customize( $name = '' ) {
		if ( ! $name ) {
			return '';
		}
		return $this->customize->post_value( $this->customize->get_setting( 'woo_cart_all_in_one_params[' . $name . ']' ), $this->settings->get_params( $name ) );
	}

	public function customize_controls_enqueue_scripts() {
		$this->admin::enqueue_style(
			array( 'vi-wcaio-cart-icons', 'vi-wcaio-cart-icons-atc', 'vi-wcaio-back-icons' ),
			array( 'cart-icons.min.css', 'cart-icons-atc.min.css', 'back-icons.min.css' )
		);
		$this->admin::enqueue_style(
			array( 'vi-wcaio-customize-preview' ),
			array( 'customize-preview.css' )
		);
		$this->admin::enqueue_script(
			array( 'vi-wcaio-customize-setting' ),
			array( 'customize-setting.js' ),
			array( array( 'jquery', 'jquery-ui-button' ) ),
			'enqueue', true
		);
		$args = array(
			'languages'          => $this->languages,
			'sc_checkout_enable' => $this->settings->get_params( 'sc_checkout_enable' ) ?: '',
			'cart_url'           => esc_js( wc_get_page_permalink( 'cart' ) ),
			'checkout_url'       => esc_js( wc_get_page_permalink( 'checkout' ) ),
			'shop_url'           => esc_js( wc_get_page_permalink( 'shop' ) ),
		);
		wp_localize_script( 'vi-wcaio-customize-setting', 'vi_wcaio_preview_setting', $args );
	}

	public function customize_preview_init() {
		$this->admin::enqueue_script(
			array( 'vi-wcaio-customize-preview' ),
			array( 'customize-preview.js' ),
			array( array( 'jquery', 'customize-preview', 'flexslider' ) ),
			'enqueue', true
		);
		$args = array(
			'ajax_url'  => admin_url( 'admin-ajax.php' ),
			'languages' => $this->languages,
		);
		wp_localize_script( 'vi-wcaio-customize-preview', 'vi_wcaio_preview', $args );
	}

	public function design_option_customizer( $wp_customize ) {
		if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
			/*wpml*/
			global $sitepress;
			$this->default_language = $sitepress->get_default_language();
			$languages              = icl_get_languages( 'skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str' );
			$this->languages_data   = $languages;
			if ( count( $languages ) ) {
				foreach ( $languages as $key => $language ) {
					if ( $key != $this->default_language ) {
						$this->languages[] = $key;
					}
				}
			}
		} elseif ( class_exists( 'Polylang' ) ) {
			/*Polylang*/
			$languages              = pll_languages_list();
			$this->default_language = pll_default_language( 'slug' );
			foreach ( $languages as $language ) {
				if ( $language == $this->default_language ) {
					continue;
				}
				$this->languages[] = $language;
			}
		}
		$wp_customize->add_panel( 'vi_wcaio_design', array(
			'priority'       => 200,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Cart All In One For WooCommerce', 'woocommerce-cart-all-in-one' ),
		) );
		$this->add_section_design_sidebar_cart_general( $wp_customize );
		$this->add_section_design_sidebar_icon( $wp_customize );
		$this->add_section_design_sidebar_header( $wp_customize );
		$this->add_section_design_sidebar_products( $wp_customize );
		$this->add_section_design_sidebar_footer( $wp_customize );
		$this->add_section_design_menu_cart( $wp_customize );
		$this->add_section_design_checkout( $wp_customize );
		$this->add_section_design_sticky_atc( $wp_customize );
		$this->add_section_design_custom_css( $wp_customize );
	}

	protected function add_section_design_sidebar_cart_general( $wp_customize ) {
		$wp_customize->add_section( 'vi_wcaio_design_sidebar_cart_general', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Sidebar Cart', 'woocommerce-cart-all-in-one' ),
			'panel'          => 'vi_wcaio_design',
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_enable]',
			array(
				'default'           => $this->settings->get_default( 'sc_enable' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Checkbox_Control( $wp_customize,
				'woo_cart_all_in_one_params[sc_enable]', array(
					'label'    => esc_html__( 'Enable', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_enable]',
					'section'  => 'vi_wcaio_design_sidebar_cart_general',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_mobile_enable]',
			array(
				'default'           => $this->settings->get_default( 'sc_mobile_enable' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Checkbox_Control( $wp_customize,
				'woo_cart_all_in_one_params[sc_mobile_enable]', array(
					'label'    => esc_html__( 'Mobile Enable', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_mobile_enable]',
					'section'  => 'vi_wcaio_design_sidebar_cart_general',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_display_type]',
			array(
				'default'           => $this->settings->get_default( 'sc_display_type' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			'woo_cart_all_in_one_params[sc_display_type]',
			array(
				'label'   => esc_html__( 'Display Sidebar Content', 'woocommerce-cart-all-in-one' ),
				'section' => 'vi_wcaio_design_sidebar_cart_general',
				'type'    => 'select',
				'choices' => array(
					'1' => esc_html__( 'Style one', 'woocommerce-cart-all-in-one' ),
					'2' => esc_html__( 'Style two', 'woocommerce-cart-all-in-one' ),
				),
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_position]',
			array(
				'default'           => $this->settings->get_default( 'sc_position' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			'woo_cart_all_in_one_params[sc_position]',
			array(
				'label'   => esc_html__( 'Sidebar Cart Content Position', 'woocommerce-cart-all-in-one' ),
				'section' => 'vi_wcaio_design_sidebar_cart_general',
				'type'    => 'select',
				'choices' => array(
					'top_left'     => esc_html__( 'Top Left', 'woocommerce-cart-all-in-one' ),
					'top_right'    => esc_html__( 'Top Right', 'woocommerce-cart-all-in-one' ),
					'bottom_left'  => esc_html__( 'Bottom Left', 'woocommerce-cart-all-in-one' ),
					'bottom_right' => esc_html__( 'Bottom Right', 'woocommerce-cart-all-in-one' ),
				),
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_horizontal]',
			array(
				'default'           => $this->settings->get_default( 'sc_horizontal' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control( new VIWCAIO_Customize_Range_Control( $wp_customize,
			'woo_cart_all_in_one_params[sc_horizontal]',
			array(
				'label'       => esc_html__( 'Sidebar Cart Content Horizontal(px)', 'woocommerce-cart-all-in-one' ),
				'section'     => 'vi_wcaio_design_sidebar_cart_general',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 200,
					'step' => 1,
					'id'   => 'vi-wcaio-sc_horizontal',
				),
			)
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_vertical]',
			array(
				'default'           => $this->settings->get_default( 'sc_vertical' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control( new VIWCAIO_Customize_Range_Control( $wp_customize,
			'woo_cart_all_in_one_params[sc_vertical]',
			array(
				'label'       => esc_html__( 'Sidebar Cart Content Vertical(px)', 'woocommerce-cart-all-in-one' ),
				'section'     => 'vi_wcaio_design_sidebar_cart_general',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 200,
					'step' => 1,
					'id'   => 'vi-wcaio-sc_vertical',
				),
			)
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_radius]',
			array(
				'default'           => $this->settings->get_default( 'sc_radius' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control( new VIWCAIO_Customize_Range_Control( $wp_customize,
			'woo_cart_all_in_one_params[sc_radius]',
			array(
				'label'       => esc_html__( 'Border Radius For Sidebar Cart Content(px)', 'woocommerce-cart-all-in-one' ),
				'section'     => 'vi_wcaio_design_sidebar_cart_general',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 200,
					'step' => 1,
					'id'   => 'vi-wcaio-sc_radius',
				),
			)
		) );
		if ( $this->settings->get_params( 'sc_icon_enable' ) ) {
			$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_fly_to_cart]',
				array(
					'default'           => $this->settings->get_default( 'sc_fly_to_cart' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				)
			);
			$wp_customize->add_control( new VIWCAIO_Customize_Checkbox_Control( $wp_customize,
					'woo_cart_all_in_one_params[sc_fly_to_cart]', array(
						'label'       => esc_html__( 'Fly To Cart', 'woocommerce-cart-all-in-one' ),
						'settings'    => 'woo_cart_all_in_one_params[sc_fly_to_cart]',
						'section'     => 'vi_wcaio_design_sidebar_cart_general',
						'description' => esc_html__( 'The products will be flown to Cart after clicking on add to cart button', 'woocommerce-cart-all-in-one' ),
					) )
			);
			$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_effect_after_atc]',
				array(
					'default'           => $this->settings->get_default( 'sc_effect_after_atc' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
			$wp_customize->add_control(
				'woo_cart_all_in_one_params[sc_effect_after_atc]',
				array(
					'label'   => esc_html__( 'Cart Effect After Adding Product', 'woocommerce-cart-all-in-one' ),
					'section' => 'vi_wcaio_design_sidebar_cart_general',
					'type'    => 'select',
					'choices' => array(
						'0'                => esc_html__( 'None', 'woocommerce-cart-all-in-one' ),
						'open'             => esc_html__( 'Open cart', 'woocommerce-cart-all-in-one' ),
						'shake_horizontal' => esc_html__( 'Shake Horizontal', 'woocommerce-cart-all-in-one' ),
						'shake_vertical'   => esc_html__( 'Shake Vertical', 'woocommerce-cart-all-in-one' ),
					),
				)
			);
			$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_trigger_type]',
				array(
					'default'           => $this->settings->get_default( 'sc_trigger_type' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				)
			);
			$wp_customize->add_control(
				'woo_cart_all_in_one_params[sc_trigger_type]',
				array(
					'label'       => esc_html__( 'Sidebar Trigger Event Type', 'woocommerce-cart-all-in-one' ),
					'section'     => 'vi_wcaio_design_sidebar_cart_general',
					'type'        => 'select',
					'choices'     => array(
						'hover' => esc_html__( 'MouseOver', 'woocommerce-cart-all-in-one' ),
						'click' => esc_html__( 'Click', 'woocommerce-cart-all-in-one' ),
					),
					'description' => esc_html__( 'If choose "Click", the cart content will be shown after clicking on the cart icon', 'woocommerce-cart-all-in-one' ),
				)
			);
		} else {
			$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_effect_after_atc]',
				array(
					'default'           => $this->settings->get_default( 'sc_effect_after_atc' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
			$wp_customize->add_control(
				'woo_cart_all_in_one_params[sc_effect_after_atc]',
				array(
					'label'   => esc_html__( 'Cart Effect After Adding Product', 'woocommerce-cart-all-in-one' ),
					'section' => 'vi_wcaio_design_sidebar_cart_general',
					'type'    => 'select',
					'choices' => array(
						'0'    => esc_html__( 'None', 'woocommerce-cart-all-in-one' ),
						'open' => esc_html__( 'Open cart', 'woocommerce-cart-all-in-one' )
					),
				)
			);
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_trigger_style]',
			array(
				'default'           => $this->settings->get_default( 'sc_trigger_style' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'woo_cart_all_in_one_params[sc_trigger_style]',
			array(
				'label'   => esc_html__( 'Sidebar Trigger Event Style', 'woocommerce-cart-all-in-one' ),
				'section' => 'vi_wcaio_design_sidebar_cart_general',
				'type'    => 'radio',
				'choices' => array(
					'fade'        => esc_html__( 'Fade', 'woocommerce-cart-all-in-one' ),
					'flip'        => esc_html__( 'Flip', 'woocommerce-cart-all-in-one' ),
					'slide'       => esc_html__( 'Slide', 'woocommerce-cart-all-in-one' ),
					'roll'        => esc_html__( 'Roll', 'woocommerce-cart-all-in-one' ),
					'rotate'      => esc_html__( 'Rotate', 'woocommerce-cart-all-in-one' ),
					'rotate_down' => esc_html__( 'RotateInDown', 'woocommerce-cart-all-in-one' ),
					'rotate_up'   => esc_html__( 'RotateInUp', 'woocommerce-cart-all-in-one' ),
					'zoom'        => esc_html__( 'Zoom', 'woocommerce-cart-all-in-one' ),
				),
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_loading]',
			array(
				'default'           => $this->settings->get_default( 'sc_loading' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'woo_cart_all_in_one_params[sc_loading]',
			array(
				'label'   => esc_html__( 'Loading Type', 'woocommerce-cart-all-in-one' ),
				'section' => 'vi_wcaio_design_sidebar_cart_general',
				'type'    => 'radio',
				'choices' => array(
					'0'                => esc_html__( 'Hidden', 'woocommerce-cart-all-in-one' ),
					'default'          => esc_html__( 'Default', 'woocommerce-cart-all-in-one' ),
					'dual_ring'        => esc_html__( 'Dual Ring', 'woocommerce-cart-all-in-one' ),
					'animation_face_1' => esc_html__( 'Animation Facebook 1', 'woocommerce-cart-all-in-one' ),
					'animation_face_2' => esc_html__( 'Animation Facebook 2', 'woocommerce-cart-all-in-one' ),
					'ring'             => esc_html__( 'Ring', 'woocommerce-cart-all-in-one' ),
					'roller'           => esc_html__( 'Roller', 'woocommerce-cart-all-in-one' ),
					'loader_balls_1'   => esc_html__( 'Loader Balls 1', 'woocommerce-cart-all-in-one' ),
					'loader_balls_2'   => esc_html__( 'Loader Balls 2', 'woocommerce-cart-all-in-one' ),
					'loader_balls_3'   => esc_html__( 'Loader Balls 3', 'woocommerce-cart-all-in-one' ),
					'ripple'           => esc_html__( 'Ripple', 'woocommerce-cart-all-in-one' ),
					'spinner'          => esc_html__( 'Spinner', 'woocommerce-cart-all-in-one' ),
				),
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_loading_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_loading_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_loading_color]',
				array(
					'label'    => esc_html__( 'Loading Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_cart_general',
					'settings' => 'woo_cart_all_in_one_params[sc_loading_color]',
				)
			)
		);
	}

	protected function add_section_design_sidebar_icon( $wp_customize ) {
		$wp_customize->add_section( 'vi_wcaio_design_sidebar_cart_icon', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Sidebar Cart Icon', 'woocommerce-cart-all-in-one' ),
			'panel'          => 'vi_wcaio_design',
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_enable]',
			array(
				'default'           => $this->settings->get_default( 'sc_icon_enable' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Checkbox_Control( $wp_customize,
				'woo_cart_all_in_one_params[sc_icon_enable]', array(
					'label'    => esc_html__( 'Enable', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_icon_enable]',
					'section'  => 'vi_wcaio_design_sidebar_cart_icon',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_style]',
			array(
				'default'           => $this->settings->get_default( 'sc_icon_style' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_position]',
			array(
				'default'           => $this->settings->get_default( 'sc_icon_position' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			'woo_cart_all_in_one_params[sc_icon_position]',
			array(
				'label'   => esc_html__( 'Position', 'woocommerce-cart-all-in-one' ),
				'section' => 'vi_wcaio_design_sidebar_cart_icon',
				'type'    => 'select',
				'choices' => array(
					'top_left'     => esc_html__( 'Top Left', 'woocommerce-cart-all-in-one' ),
					'top_right'    => esc_html__( 'Top Right', 'woocommerce-cart-all-in-one' ),
					'bottom_left'  => esc_html__( 'Bottom Left', 'woocommerce-cart-all-in-one' ),
					'bottom_right' => esc_html__( 'Bottom Right', 'woocommerce-cart-all-in-one' ),
				),
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_horizontal]',
			array(
				'default'           => $this->settings->get_default( 'sc_icon_horizontal' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control( new VIWCAIO_Customize_Range_Control( $wp_customize,
			'woo_cart_all_in_one_params[sc_icon_horizontal]',
			array(
				'label'       => esc_html__( 'Cart icon Horizontal(px)', 'woocommerce-cart-all-in-one' ),
				'section'     => 'vi_wcaio_design_sidebar_cart_icon',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 200,
					'step' => 1,
					'id'   => 'vi-wcaio-sc_icon_horizontal',
				),
			)
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_vertical]',
			array(
				'default'           => $this->settings->get_default( 'sc_icon_vertical' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control( new VIWCAIO_Customize_Range_Control( $wp_customize,
			'woo_cart_all_in_one_params[sc_icon_vertical]',
			array(
				'label'       => esc_html__( 'Cart icon Vertical(px)', 'woocommerce-cart-all-in-one' ),
				'section'     => 'vi_wcaio_design_sidebar_cart_icon',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 200,
					'step' => 1,
					'id'   => 'vi-wcaio-sc_icon_vertical',
				),
			)
		) );
		$sc_icon_style   = array( 4, 3, 2, 1, 5 );
		$sc_icon_style_t = array();
		foreach ( $sc_icon_style as $style ) {
			$sc_icon_style_t[ $style ] = sprintf( '<img class="viwcaio_sc_icon_style_img viwcaio_sc_icon_style_img-%s" src="%s" >', esc_attr( $style ), esc_url( VIWCAIO_CART_ALL_IN_ONE_IMAGES . 'cart-icon/style_' . $style . '.png' ) );
		}
		$wp_customize->add_control(
			new VIWCAIO_Customize_Radio_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_icon_style]',
				array(
					'label'   => esc_html__( 'Cart icon Style', 'woocommerce-cart-all-in-one' ),
					'section' => 'vi_wcaio_design_sidebar_cart_icon',
					'choices' => $sc_icon_style_t
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_box_shadow]',
			array(
				'default'           => $this->settings->get_default( 'sc_icon_box_shadow' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			new VIWCAIO_Customize_Checkbox_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_icon_box_shadow]',
				array(
					'label'    => esc_html__( 'Enable Box Shadow', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_icon_box_shadow]',
					'section'  => 'vi_wcaio_design_sidebar_cart_icon',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_scale]',
			array(
				'default'           => $this->settings->get_default( 'sc_icon_scale' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_icon_scale]',
			array(
				'label'       => esc_html__( 'Sidebar Cart Icon Size', 'woocommerce-cart-all-in-one' ),
				'type'        => 'number',
				'section'     => 'vi_wcaio_design_sidebar_cart_icon',
				'input_attrs' => array(
					'min'  => 0.5,
					'max'  => 3,
					'step' => 0.01,
				),
				'description' => esc_html__( 'Set the sidebar cart icon size. This new size parameter need to be the a ratio compared with original icon size', 'woocommerce-cart-all-in-one' ),
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_hover_scale]',
			array(
				'default'           => $this->settings->get_default( 'sc_icon_hover_scale' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_icon_hover_scale]',
			array(
				'label'       => esc_html__( 'Sidebar Cart Icon Size When Hovering', 'woocommerce-cart-all-in-one' ),
				'type'        => 'number',
				'section'     => 'vi_wcaio_design_sidebar_cart_icon',
				'input_attrs' => array(
					'min'  => 0.5,
					'max'  => 3,
					'step' => 0.01,
				),
				'description' => esc_html__( 'Set the size of Sidebar Cart Icon when hovering', 'woocommerce-cart-all-in-one' ),
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_border_radius]',
			array(
				'default'           => $this->settings->get_default( 'sc_icon_border_radius' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control( new VIWCAIO_Customize_Range_Control( $wp_customize,
			'woo_cart_all_in_one_params[sc_icon_border_radius]',
			array(
				'label'       => esc_html__( 'Cart Icon Radius(px)', 'woocommerce-cart-all-in-one' ),
				'section'     => 'vi_wcaio_design_sidebar_cart_icon',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
					'id'   => 'vi-wcaio-sc_icon_border_radius',
				),
			)
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_use_img]',
			array(
				'default'           => $this->settings->get_default( 'sc_icon_use_img' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			new VIWCAIO_Customize_Checkbox_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_icon_use_img]',
				array(
					'label'    => esc_html__( 'Use an image for the cart icon', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_icon_use_img]',
					'section'  => 'vi_wcaio_design_sidebar_cart_icon',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_img]', array(
			'default'           => $this->settings->get_default( 'sc_icon_img' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_icon_img]',
				array(
					'section'  => 'vi_wcaio_design_sidebar_cart_icon',
					'settings' => 'woo_cart_all_in_one_params[sc_icon_img]',
				) )
		);
		$cart_icons   = $this->settings->get_class_icons( 'cart_icons' );
		$cart_icons_t = array();
		foreach ( $cart_icons as $k => $class ) {
			$cart_icons_t[ $k ] = '<i class="' . $class . '"></i>';
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_default_icon]',
			array(
				'default'    => $this->settings->get_default( 'sc_icon_default_icon' ),
				'type'       => 'option',
				'capability' => 'manage_options',
				'transport'  => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Radio_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_icon_default_icon]',
				array(
					'label'   => esc_html__( 'Cart Icon Type', 'woocommerce-cart-all-in-one' ),
					'section' => 'vi_wcaio_design_sidebar_cart_icon',
					'choices' => $cart_icons_t
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_bg_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_icon_bg_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_icon_bg_color]',
				array(
					'label'    => esc_html__( 'Cart Icon Background', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_cart_icon',
					'settings' => 'woo_cart_all_in_one_params[sc_icon_bg_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_icon_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_icon_color]',
				array(
					'label'    => esc_html__( 'Cart Icon Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_cart_icon',
					'settings' => 'woo_cart_all_in_one_params[sc_icon_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_count_type]',
			array(
				'default'           => $this->settings->get_default( 'sc_icon_count_type' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			'woo_cart_all_in_one_params[sc_icon_count_type]',
			array(
				'label'    => esc_html__( 'Product Counter type', 'woocommerce-cart-all-in-one' ),
				'type'     => 'select',
				'settings' => 'woo_cart_all_in_one_params[sc_icon_count_type]',
				'section'  => 'vi_wcaio_design_sidebar_cart_icon',
				'choices'  => array(
					'pd_count'      => esc_html__( 'Product count', 'woocommerce-cart-all-in-one' ),
					'item_count'    => esc_html__( 'Cart item count', 'woocommerce-cart-all-in-one' ),
					'cart_subtotal' => esc_html__( 'Cart subtotal', 'woocommerce-cart-all-in-one' ),
				),
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_count_bg_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_icon_count_bg_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_icon_count_bg_color]',
				array(
					'label'    => esc_html__( 'Product Counter Background Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_cart_icon',
					'settings' => 'woo_cart_all_in_one_params[sc_icon_count_bg_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_count_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_icon_count_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_icon_count_color]',
				array(
					'label'    => esc_html__( 'Product Counter Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_cart_icon',
					'settings' => 'woo_cart_all_in_one_params[sc_icon_count_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_icon_count_border_radius]',
			array(
				'default'           => $this->settings->get_default( 'sc_icon_count_border_radius' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			new VIWCAIO_Customize_Range_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_icon_count_border_radius]',
				array(
					'label'       => esc_html__( 'Product Counter Border Radius(px)', 'woocommerce-cart-all-in-one' ),
					'section'     => 'vi_wcaio_design_sidebar_cart_icon',
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 1,
						'id'   => 'vi-wcaio-sc_icon_count_border_radius',
					),
				)
			)
		);
	}

	protected function add_section_design_sidebar_header( $wp_customize ) {
		$wp_customize->add_section( 'vi_wcaio_design_sidebar_header', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Sidebar Cart Header', 'woocommerce-cart-all-in-one' ),
			'panel'          => 'vi_wcaio_design',
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_header_bg_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_header_bg_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_header_bg_color]',
				array(
					'label'    => esc_html__( 'Background Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_header',
					'settings' => 'woo_cart_all_in_one_params[sc_header_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_header_border_style]',
			array(
				'default'           => $this->settings->get_default( 'sc_header_border_style' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'woo_cart_all_in_one_params[sc_header_border_style]',
			array(
				'label'   => esc_html__( 'Header Border Style ', 'woocommerce-cart-all-in-one' ),
				'section' => 'vi_wcaio_design_sidebar_header',
				'type'    => 'select',
				'choices' => array(
					'none'   => esc_html__( 'No border', 'woocommerce-cart-all-in-one' ),
					'solid'  => esc_html__( 'Solid', 'woocommerce-cart-all-in-one' ),
					'dotted' => esc_html__( 'Dotted', 'woocommerce-cart-all-in-one' ),
					'dashed' => esc_html__( 'Dashed', 'woocommerce-cart-all-in-one' ),
				),
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_header_border_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_header_border_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_header_border_color]',
				array(
					'label'    => esc_html__( 'Header Border Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_header',
					'settings' => 'woo_cart_all_in_one_params[sc_header_border_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_header_title]',
			array(
				'default'           => $this->settings->get_default( 'sc_header_title' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_header_title]',
			array(
				'label'   => esc_html__( 'Cart Title', 'woocommerce-cart-all-in-one' ),
				'type'    => 'text',
				'section' => 'vi_wcaio_design_sidebar_header',
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sc_header_title_{$value}]", array(
					'default'           => $this->settings->get_default( 'sc_header_title' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'Cart Title', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'Cart Title', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sc_header_title_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'text',
						'section' => 'vi_wcaio_design_sidebar_header',
					)
				);
			}
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_header_title_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_header_title_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_header_title_color]',
				array(
					'label'    => esc_html__( 'Title Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_header',
					'settings' => 'woo_cart_all_in_one_params[sc_header_title_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_header_coupon_enable]',
			array(
				'default'           => $this->settings->get_default( 'sc_header_coupon_enable' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			new VIWCAIO_Customize_Checkbox_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_header_coupon_enable]',
				array(
					'label'    => esc_html__( 'Enable Coupon', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_header_coupon_enable]',
					'section'  => 'vi_wcaio_design_sidebar_header',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_header_coupon_input_radius]',
			array(
				'default'           => $this->settings->get_default( 'sc_header_coupon_input_radius' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			new VIWCAIO_Customize_Range_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_header_coupon_input_radius]',
				array(
					'label'       => esc_html__( 'Coupon Input Radius(px)', 'woocommerce-cart-all-in-one' ),
					'section'     => 'vi_wcaio_design_sidebar_header',
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
						'id'   => 'vi-wcaio-sc_header_coupon_input_radius',
					),
				)
			) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_header_coupon_button_bg_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_header_coupon_button_bg_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_header_coupon_button_bg_color]',
				array(
					'label'    => esc_html__( 'Apply Coupon Button Background', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_header',
					'settings' => 'woo_cart_all_in_one_params[sc_header_coupon_button_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_header_coupon_button_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_header_coupon_button_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_header_coupon_button_color]',
				array(
					'label'    => esc_html__( 'Apply Coupon Button Text Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_header',
					'settings' => 'woo_cart_all_in_one_params[sc_header_coupon_button_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_header_coupon_button_bg_color_hover]',
			array(
				'default'           => $this->settings->get_default( 'sc_header_coupon_button_bg_color_hover' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_header_coupon_button_bg_color_hover]',
				array(
					'label'    => esc_html__( 'Apply Coupon Button Hover Background', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_header',
					'settings' => 'woo_cart_all_in_one_params[sc_header_coupon_button_bg_color_hover]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_header_coupon_button_color_hover]',
			array(
				'default'           => $this->settings->get_default( 'sc_header_coupon_button_color_hover' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_header_coupon_button_color_hover]',
				array(
					'label'    => esc_html__( 'Apply Coupon Button Hover Text Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_header',
					'settings' => 'woo_cart_all_in_one_params[sc_header_coupon_button_color_hover]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_header_coupon_button_border_radius]',
			array(
				'default'           => $this->settings->get_default( 'sc_header_coupon_button_border_radius' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Range_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_header_coupon_button_border_radius]',
				array(
					'label'       => esc_html__( 'Apply Coupon Button Radius(px)', 'woocommerce-cart-all-in-one' ),
					'section'     => 'vi_wcaio_design_sidebar_header',
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
						'id'   => 'vi-wcaio-sc_header_coupon_button_border_radius',
					),
				)
			) );
	}

	protected function add_section_design_sidebar_footer( $wp_customize ) {
		$wp_customize->add_section( 'vi_wcaio_design_sidebar_footer', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Sidebar Cart Footer', 'woocommerce-cart-all-in-one' ),
			'panel'          => 'vi_wcaio_design',
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_bg_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_bg_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_footer_bg_color]',
				array(
					'label'    => esc_html__( 'Background Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_footer',
					'settings' => 'woo_cart_all_in_one_params[sc_footer_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_border_type]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_border_type' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'woo_cart_all_in_one_params[sc_footer_border_type]',
			array(
				'label'   => esc_html__( 'Footer Border Style', 'woocommerce-cart-all-in-one' ),
				'section' => 'vi_wcaio_design_sidebar_footer',
				'type'    => 'select',
				'choices' => array(
					'none'   => esc_html__( 'No border', 'woocommerce-cart-all-in-one' ),
					'solid'  => esc_html__( 'Solid', 'woocommerce-cart-all-in-one' ),
					'dotted' => esc_html__( 'Dotted', 'woocommerce-cart-all-in-one' ),
					'dashed' => esc_html__( 'Dashed', 'woocommerce-cart-all-in-one' ),
				),
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_border_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_border_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_footer_border_color]',
				array(
					'label'    => esc_html__( 'Footer Border Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_footer',
					'settings' => 'woo_cart_all_in_one_params[sc_footer_border_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_coupon]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_coupon' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Checkbox_Control( $wp_customize,
				'woo_cart_all_in_one_params[sc_footer_coupon]', array(
					'label'    => esc_html__( 'Enable Applied Coupons', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_footer_coupon]',
					'section'  => 'vi_wcaio_design_sidebar_footer',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_cart_total]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_cart_total' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'woo_cart_all_in_one_params[sc_footer_cart_total]',
			array(
				'label'    => esc_html__( 'Price to display', 'woocommerce-cart-all-in-one' ),
				'type'     => 'select',
				'settings' => 'woo_cart_all_in_one_params[sc_footer_cart_total]',
				'section'  => 'vi_wcaio_design_sidebar_footer',
				'choices'  => array(
					'subtotal' => esc_html__( 'Subtotal (total of products)', 'woocommerce-cart-all-in-one' ),
					'total'    => esc_html__( 'Cart total', 'woocommerce-cart-all-in-one' ),
				),
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_cart_total_text]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_cart_total_text' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_footer_cart_total_text]',
			array(
				'label'   => esc_html__( 'Total Text', 'woocommerce-cart-all-in-one' ),
				'type'    => 'text',
				'section' => 'vi_wcaio_design_sidebar_footer',
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sc_footer_cart_total_text_{$value}]", array(
					'default'           => $this->settings->get_default( 'sc_footer_cart_total_text' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'Total Text', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'Total Text', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sc_footer_cart_total_text_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'text',
						'section' => 'vi_wcaio_design_sidebar_footer',
					)
				);
			}
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_cart_total_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_cart_total_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_footer_cart_total_color]',
				array(
					'label'    => esc_html__( 'Total Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_footer',
					'settings' => 'woo_cart_all_in_one_params[sc_footer_cart_total_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_cart_total_color1]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_cart_total_color1' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_footer_cart_total_color1]',
				array(
					'label'    => esc_html__( 'Price Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_footer',
					'settings' => 'woo_cart_all_in_one_params[sc_footer_cart_total_color1]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_button]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_button' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'woo_cart_all_in_one_params[sc_footer_button]',
			array(
				'label'    => esc_html__( 'Button Enable', 'woocommerce-cart-all-in-one' ),
				'type'     => 'select',
				'settings' => 'woo_cart_all_in_one_params[sc_footer_button]',
				'section'  => 'vi_wcaio_design_sidebar_footer',
				'choices'  => array(
					'cart'     => esc_html__( 'View cart ', 'woocommerce-cart-all-in-one' ),
					'checkout' => esc_html__( 'Checkout ', 'woocommerce-cart-all-in-one' ),
				),
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_bt_cart_text]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_bt_cart_text' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_footer_bt_cart_text]',
			array(
				'label'   => esc_html__( 'View Cart Button Text', 'woocommerce-cart-all-in-one' ),
				'type'    => 'text',
				'section' => 'vi_wcaio_design_sidebar_footer',
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sc_footer_bt_cart_text_{$value}]", array(
					'default'           => $this->settings->get_default( 'sc_footer_bt_cart_text' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'View Cart Button Text', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'View Cart Button Text', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sc_footer_bt_cart_text_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'text',
						'section' => 'vi_wcaio_design_sidebar_footer',
					)
				);
			}
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_bt_checkout_text]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_bt_checkout_text' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_footer_bt_checkout_text]',
			array(
				'label'   => esc_html__( 'Checkout Button Text', 'woocommerce-cart-all-in-one' ),
				'type'    => 'text',
				'section' => 'vi_wcaio_design_sidebar_footer',
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sc_footer_bt_checkout_text_{$value}]", array(
					'default'           => $this->settings->get_default( 'sc_footer_bt_checkout_text' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'Checkout Button Text', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'Checkout Button Text', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sc_footer_bt_checkout_text_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'text',
						'section' => 'vi_wcaio_design_sidebar_footer',
					)
				);
			}
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_button_bg_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_button_bg_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_footer_button_bg_color]',
				array(
					'label'    => esc_html__( 'Button Background', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_footer',
					'settings' => 'woo_cart_all_in_one_params[sc_footer_button_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_button_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_button_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_footer_button_color]',
				array(
					'label'    => esc_html__( 'Button Text Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_footer',
					'settings' => 'woo_cart_all_in_one_params[sc_footer_button_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_button_hover_bg_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_button_hover_bg_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_footer_button_hover_bg_color]',
				array(
					'label'    => esc_html__( 'Button Hover Background', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_footer',
					'settings' => 'woo_cart_all_in_one_params[sc_footer_button_hover_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_button_hover_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_button_hover_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_footer_button_hover_color]',
				array(
					'label'    => esc_html__( 'Button Hover Text Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_footer',
					'settings' => 'woo_cart_all_in_one_params[sc_footer_button_hover_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_button_border_radius]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_button_border_radius' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Range_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_footer_button_border_radius]',
				array(
					'label'       => esc_html__( 'Button Radius(px)', 'woocommerce-cart-all-in-one' ),
					'section'     => 'vi_wcaio_design_sidebar_footer',
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
						'id'   => 'vi-wcaio-sc_footer_button_border_radius',
					),
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_bt_update_bg_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_bt_update_bg_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_footer_bt_update_bg_color]',
				array(
					'label'    => esc_html__( 'Update Button Background', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_footer',
					'settings' => 'woo_cart_all_in_one_params[sc_footer_bt_update_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_bt_update_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_bt_update_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_footer_bt_update_color]',
				array(
					'label'    => esc_html__( 'Update Button Text Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_footer',
					'settings' => 'woo_cart_all_in_one_params[sc_footer_bt_update_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_bt_update_hover_bg_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_bt_update_hover_bg_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_footer_bt_update_hover_bg_color]',
				array(
					'label'    => esc_html__( 'Update Button Hover Background', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_footer',
					'settings' => 'woo_cart_all_in_one_params[sc_footer_bt_update_hover_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_bt_update_hover_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_bt_update_hover_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_footer_bt_update_hover_color]',
				array(
					'label'    => esc_html__( 'Update Button Hover Text Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_footer',
					'settings' => 'woo_cart_all_in_one_params[sc_footer_bt_update_hover_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_bt_update_border_radius]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_bt_update_border_radius' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Range_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_footer_bt_update_border_radius]',
				array(
					'label'       => esc_html__( 'Update Button Radius(px)', 'woocommerce-cart-all-in-one' ),
					'section'     => 'vi_wcaio_design_sidebar_footer',
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
						'id'   => 'vi-wcaio-sc_footer_bt_update_border_radius',
					),
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_message]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_message' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'viwcaio_sanitize_kses',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_footer_message]',
			array(
				'type'        => 'textarea',
				'priority'    => 10,
				'section'     => 'vi_wcaio_design_sidebar_footer',
				'label'       => esc_html__( 'Custom Message', 'woocommerce-cart-all-in-one' ),
				'description' => sprintf( '{product_plus} - %s', esc_html__( 'The list of suggested products', 'woocommerce-cart-all-in-one' ) ),
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sc_footer_message_{$value}]", array(
					'default'           => $this->settings->get_default( 'sc_footer_message' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'viwcaio_sanitize_kses',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'Custom Message', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'Custom Message', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sc_footer_message_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'textarea',
						'section' => 'vi_wcaio_design_sidebar_footer',
					)
				);
			}
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_pd_plus]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_pd_plus' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$sc_footer_pd_plus = array(
			'best_selling'     => esc_html__( 'Best Selling Products', 'woocommerce-cart-all-in-one' ),
			'cross_sell'       => esc_html__( 'Cross-sells Products', 'woocommerce-cart-all-in-one' ),
			'product_featured' => esc_html__( 'Featured products', 'woocommerce-cart-all-in-one' ),
			'viewed_product'   => esc_html__( 'Recently Viewed products', 'woocommerce-cart-all-in-one' ),
			'select_cat'       => esc_html__( 'Select Categories', 'woocommerce-cart-all-in-one' ),
			'select_pd'        => esc_html__( 'Select Products', 'woocommerce-cart-all-in-one' ),
			'product_rating'   => esc_html__( 'Top Rated Products', 'woocommerce-cart-all-in-one' ),
			'product_upsell'   => esc_html__( 'Upsell Products', 'woocommerce-cart-all-in-one' ),
		);
		$wp_customize->add_control(
			'woo_cart_all_in_one_params[sc_footer_pd_plus]',
			array(
				'label'    => esc_html__( 'Show Products Plus', 'woocommerce-cart-all-in-one' ),
				'type'     => 'select',
				'settings' => 'woo_cart_all_in_one_params[sc_footer_pd_plus]',
				'section'  => 'vi_wcaio_design_sidebar_footer',
				'choices'  => $sc_footer_pd_plus,
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_pd_plus_cats]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_pd_plus_cats' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Select2_Control( $wp_customize,
				'woo_cart_all_in_one_params[sc_footer_pd_plus_cats]', array(
					'type'     => 'category',
					'label'    => esc_html__( 'Select Categories', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_footer_pd_plus_cats]',
					'section'  => 'vi_wcaio_design_sidebar_footer'
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_pd_plus_products]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_pd_plus_products' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Select2_Control( $wp_customize,
				'woo_cart_all_in_one_params[sc_footer_pd_plus_products]', array(
					'type'     => 'product',
					'label'    => esc_html__( 'Select Products', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_footer_pd_plus_products]',
					'section'  => 'vi_wcaio_design_sidebar_footer'
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_pd_plus_title]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_pd_plus_title' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_footer_pd_plus_title]',
			array(
				'type'        => 'text',
				'section'     => 'vi_wcaio_design_sidebar_footer',
				'label'       => esc_html__( 'Product Plus Title', 'woocommerce-cart-all-in-one' ),
				'description' => esc_html__( 'The title of suggested products list in footer', 'woocommerce-cart-all-in-one' ),
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sc_footer_pd_plus_title_{$value}]", array(
					'default'           => $this->settings->get_default( 'sc_footer_pd_plus_title' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'Product Plus Title', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'Product Plus Title', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sc_footer_pd_plus_title_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'text',
						'section' => 'vi_wcaio_design_sidebar_footer',
					)
				);
			}
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_pd_plus_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_pd_plus_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, 'woo_cart_all_in_one_params[sc_footer_pd_plus_color]',
				array(
					'label'    => esc_html__( 'Product Plus Title Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_footer',
					'settings' => 'woo_cart_all_in_one_params[sc_footer_pd_plus_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_pd_plus_out_of_stock]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_pd_plus_out_of_stock' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Checkbox_Control( $wp_customize,
				'woo_cart_all_in_one_params[sc_footer_pd_plus_out_of_stock]', array(
					'label'    => esc_html__( 'Show Out of Stock Products', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_footer_pd_plus_out_of_stock]',
					'section'  => 'vi_wcaio_design_sidebar_footer',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_pd_plus_limit]',
			array(
				'default'           => $this->settings->get_default( 'sc_footer_pd_plus_limit' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_footer_pd_plus_limit]',
			array(
				'label'       => esc_html__( 'Number Of Products To Show', 'woocommerce-cart-all-in-one' ),
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 15,
					'step' => 1,
				),
				'section'     => 'vi_wcaio_design_sidebar_footer',
				'description' => esc_html__( 'The maximum number of  showed products is 15', 'woocommerce-cart-all-in-one' ),
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_title]', array(
				'default'           => $this->settings->get_default( 'sc_footer_pd_plus_bt_atc_title' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'wp_kses_post',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_title]',
			array(
				'type'        => 'textarea',
				'section'     => 'vi_wcaio_design_sidebar_footer',
				'label'       => esc_html__( 'Cart button Text on Product Plus', 'woocommerce-cart-all-in-one' ),
				'description' => '{cart_icon} - ' . esc_html__( 'The cart icon', 'woocommerce-cart-all-in-one' ),
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_title_{$value}]", array(
					'default'           => $this->settings->get_default( 'sc_footer_pd_plus_bt_atc_title' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'Cart Button Text on Product Plus', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'Cart Button Text on Product Plus', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_title_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'textarea',
						'section' => 'vi_wcaio_design_sidebar_footer',
					)
				);
			}
		}
		$cart_icons   = $this->settings->get_class_icons( 'cart_icons_atc' );
		$cart_icons_t = array();
		foreach ( $cart_icons as $k => $class ) {
			$cart_icons_t[ $k ] = '<i class="' . $class . '"></i>';
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_cart_icon]',
			array(
				'default'    => $this->settings->get_default( 'sc_footer_pd_plus_bt_atc_cart_icon' ),
				'type'       => 'option',
				'capability' => 'manage_options',
				'transport'  => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Radio_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_cart_icon]',
				array(
					'label'   => esc_html__( 'Cart Icon Type on Product Plus', 'woocommerce-cart-all-in-one' ),
					'section' => 'vi_wcaio_design_sidebar_footer',
					'choices' => $cart_icons_t
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_bg_color]', array(
			'default'           => $this->settings->get_default( 'sc_footer_pd_plus_bt_atc_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_bg_color]',
				array(
					'label'    => esc_html__( 'Cart Button Background Color on Product Plus', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_footer',
					'settings' => 'woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_color]', array(
			'default'           => $this->settings->get_default( 'sc_footer_pd_plus_bt_atc_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_color]',
				array(
					'label'    => esc_html__( 'Cart Button Text Color on Product Plus', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_footer',
					'settings' => 'woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_hover_bg_color]', array(
			'default'           => $this->settings->get_default( 'sc_footer_pd_plus_bt_atc_hover_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_hover_bg_color]',
				array(
					'label'    => esc_html__( 'Cart Button Hover Background Color on Product Plus', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_footer',
					'settings' => 'woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_hover_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_hover_color]', array(
			'default'           => $this->settings->get_default( 'sc_footer_pd_plus_bt_atc_hover_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_hover_color]',
				array(
					'label'    => esc_html__( 'Cart Button Hover Text Color on Product Plus', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_footer',
					'settings' => 'woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_hover_color]',
				)
			)
		);
	}

	protected function add_section_design_sidebar_products( $wp_customize ) {
		$wp_customize->add_section( 'vi_wcaio_design_sidebar_products',
			array(
				'priority'       => 20,
				'capability'     => 'manage_options',
				'theme_supports' => '',
				'title'          => esc_html__( 'Sidebar Cart List Products', 'woocommerce-cart-all-in-one' ),
				'panel'          => 'vi_wcaio_design',
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_pd_update_cart]',
			array(
				'default'           => $this->settings->get_default( 'sc_pd_update_cart' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Checkbox_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_pd_update_cart]',
				array(
					'label'    => esc_html__( 'Update cart when changing the product quantity', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_pd_update_cart]',
					'section'  => 'vi_wcaio_design_sidebar_products',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_pd_bg_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_pd_bg_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_pd_bg_color]',
				array(
					'label'    => esc_html__( 'Background Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_products',
					'settings' => 'woo_cart_all_in_one_params[sc_pd_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_pd_img_box_shadow]',
			array(
				'default'           => $this->settings->get_default( 'sc_pd_img_box_shadow' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Checkbox_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_pd_img_box_shadow]',
				array(
					'label'    => esc_html__( 'Enable Image Box Shadow', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_pd_img_box_shadow]',
					'section'  => 'vi_wcaio_design_sidebar_products',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_pd_img_border_radius]',
			array(
				'default'           => $this->settings->get_default( 'sc_pd_img_border_radius' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Range_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_pd_img_border_radius]',
				array(
					'label'       => esc_html__( 'Product Image Border Radius(px)', 'woocommerce-cart-all-in-one' ),
					'section'     => 'vi_wcaio_design_sidebar_products',
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
						'id'   => 'vi-wcaio-sc_pd_img_border_radius',
					),
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_pd_name_link]',
			array(
				'default'           => $this->settings->get_default( 'sc_pd_name_link' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Checkbox_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_pd_name_link]',
				array(
					'label'    => esc_html__( 'Enable Product Title Link', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_pd_name_link]',
					'section'  => 'vi_wcaio_design_sidebar_products',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_pd_name_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_pd_name_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_pd_name_color]',
				array(
					'label'    => esc_html__( 'Name Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_products',
					'settings' => 'woo_cart_all_in_one_params[sc_pd_name_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_pd_name_hover_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_pd_name_hover_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_pd_name_hover_color]',
				array(
					'label'    => esc_html__( 'Name Hover Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_products',
					'settings' => 'woo_cart_all_in_one_params[sc_pd_name_hover_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_pd_price_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_pd_price_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_pd_price_color]',
				array(
					'label'    => esc_html__( 'Price Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_products',
					'settings' => 'woo_cart_all_in_one_params[sc_pd_price_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_pd_price_style]',
			array(
				'default'           => $this->settings->get_default( 'sc_pd_price_style' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'woo_cart_all_in_one_params[sc_pd_price_style]',
			array(
				'label'   => esc_html__( 'Price Style', 'woocommerce-cart-all-in-one' ),
				'section' => 'vi_wcaio_design_sidebar_products',
				'type'    => 'select',
				'choices' => array(
					'price'    => esc_html__( 'Product price', 'woocommerce-cart-all-in-one' ),
					'qty'      => esc_html__( 'Qty & price', 'woocommerce-cart-all-in-one' ),
					'subtotal' => esc_html__( 'Product subtotal', 'woocommerce-cart-all-in-one' ),
				),
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_pd_qty_border_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_pd_qty_border_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_pd_qty_border_color]',
				array(
					'label'    => esc_html__( 'Quantity Border Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_products',
					'settings' => 'woo_cart_all_in_one_params[sc_pd_qty_border_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_pd_qty_border_radius]',
			array(
				'default'           => $this->settings->get_default( 'sc_pd_qty_border_radius' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Range_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_pd_qty_border_radius]',
				array(
					'label'       => esc_html__( 'Quantity Border Radius(px)', 'woocommerce-cart-all-in-one' ),
					'section'     => 'vi_wcaio_design_sidebar_products',
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
						'id'   => 'vi-wcaio-sc_pd_qty_border_radius',
					),
				)
			)
		);
		$delete_icons   = $this->settings->get_class_icons( 'delete_icons' );
		$delete_icons_t = array();
		foreach ( $delete_icons as $k => $class ) {
			$delete_icons_t[ $k ] = '<i class="' . $class . '"></i>';
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_pd_delete_icon]',
			array(
				'default'    => $this->settings->get_default( 'sc_pd_delete_icon' ),
				'type'       => 'option',
				'capability' => 'manage_options',
				'transport'  => 'postMessage',
			) );
		$wp_customize->add_control(
			new VIWCAIO_Customize_Radio_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_pd_delete_icon]',
				array(
					'label'   => esc_html__( 'Trash Icon Style', 'woocommerce-cart-all-in-one' ),
					'section' => 'vi_wcaio_design_sidebar_products',
					'choices' => $delete_icons_t,
				)
			) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_pd_delete_icon_font_size]',
			array(
				'default'           => $this->settings->get_default( 'sc_pd_delete_icon_font_size' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Range_Control( $wp_customize,
			'woo_cart_all_in_one_params[sc_pd_delete_icon_font_size]',
			array(
				'label'       => esc_html__( 'Font Size for Trash Icon(px)', 'woocommerce-cart-all-in-one' ),
				'section'     => 'vi_wcaio_design_sidebar_products',
				'input_attrs' => array(
					'min'  => 5,
					'max'  => 30,
					'step' => 1,
					'id'   => 'vi-wcaio-sc_pd_delete_icon_font_size',
				),
			)
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_pd_delete_icon_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_pd_delete_icon_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[sc_pd_delete_icon_color]',
				array(
					'label'    => esc_html__( 'Trash Icon Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_products',
					'settings' => 'woo_cart_all_in_one_params[sc_pd_delete_icon_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_pd_delete_icon_hover_color]',
			array(
				'default'           => $this->settings->get_default( 'sc_pd_delete_icon_hover_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_pd_delete_icon_hover_color]',
				array(
					'label'    => esc_html__( 'Trash Icon Hover Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sidebar_products',
					'settings' => 'woo_cart_all_in_one_params[sc_pd_delete_icon_hover_color]',
				) )
		);
	}

	protected function add_section_design_menu_cart( $wp_customize ) {
		$wp_customize->add_section( 'vi_wcaio_design_menu_cart',
			array(
				'priority'       => 20,
				'capability'     => 'manage_options',
				'theme_supports' => '',
				'title'          => esc_html__( 'Menu Cart', 'woocommerce-cart-all-in-one' ),
				'panel'          => 'vi_wcaio_design',
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[mc_enable]',
			array(
				'default'           => $this->settings->get_default( 'mc_enable' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Checkbox_Control( $wp_customize,
				'woo_cart_all_in_one_params[mc_enable]', array(
					'label'    => esc_html__( 'Enable', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[mc_enable]',
					'section'  => 'vi_wcaio_design_menu_cart',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[mc_mobile_enable]',
			array(
				'default'           => $this->settings->get_default( 'mc_mobile_enable' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Checkbox_Control( $wp_customize,
				'woo_cart_all_in_one_params[mc_mobile_enable]', array(
					'label'    => esc_html__( 'Mobile Enable', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[mc_mobile_enable]',
					'section'  => 'vi_wcaio_design_menu_cart',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[mc_nav_page]',
			array(
				'default'           => $this->settings->get_default( 'mc_nav_page' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			'woo_cart_all_in_one_params[mc_nav_page]',
			array(
				'label'       => esc_html__( 'Navigation Page', 'woocommerce-cart-all-in-one' ),
				'type'        => 'select',
				'settings'    => 'woo_cart_all_in_one_params[mc_nav_page]',
				'section'     => 'vi_wcaio_design_menu_cart',
				'choices'     => array(
					'0'     => esc_html__( 'None', 'woocommerce-cart-all-in-one' ),
					'cart'     => esc_html__( 'Cart page', 'woocommerce-cart-all-in-one' ),
					'checkout' => esc_html__( 'Checkout page', 'woocommerce-cart-all-in-one' ),
				),
				'description' => esc_html__( 'Choose the page redirected to when clicking on Menu Cart', 'woocommerce-cart-all-in-one' ),
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[mc_content]',
			array(
				'default'           => $this->settings->get_default( 'mc_content' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Checkbox_Control( $wp_customize, 'woo_cart_all_in_one_params[mc_content]',
				array(
					'label'    => esc_html__( 'Show Content Cart', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[mc_content]',
					'section'  => 'vi_wcaio_design_menu_cart',
				)
			)
		);
		$cart_icons   = $this->settings->get_class_icons( 'cart_icons' );
		$cart_icons_t = array();
		foreach ( $cart_icons as $k => $class ) {
			$cart_icons_t[ $k ] = '<i class="' . $class . '"></i>';
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[mc_icon]',
			array(
				'default'    => $this->settings->get_default( 'mc_icon' ),
				'type'       => 'option',
				'capability' => 'manage_options',
				'transport'  => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Radio_Control( $wp_customize, 'woo_cart_all_in_one_params[mc_icon]',
				array(
					'label'   => esc_html__( 'Cart Icon Type', 'woocommerce-cart-all-in-one' ),
					'section' => 'vi_wcaio_design_menu_cart',
					'choices' => $cart_icons_t,
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[mc_icon_color]',
			array(
				'default'           => $this->settings->get_default( 'mc_icon_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[mc_icon_color]',
				array(
					'label'    => esc_html__( 'Cart Icon Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_menu_cart',
					'settings' => 'woo_cart_all_in_one_params[mc_icon_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[mc_icon_hover_color]',
			array(
				'default'           => $this->settings->get_default( 'mc_icon_hover_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[mc_icon_hover_color]',
				array(
					'label'    => esc_html__( 'Cart Icon Hover Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_menu_cart',
					'settings' => 'woo_cart_all_in_one_params[mc_icon_hover_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[mc_display_style]',
			array(
				'default'           => $this->settings->get_default( 'mc_display_style' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			'woo_cart_all_in_one_params[mc_display_style]',
			array(
				'label'    => esc_html__( 'Menu Cart Text', 'woocommerce-cart-all-in-one' ),
				'type'     => 'select',
				'settings' => 'woo_cart_all_in_one_params[mc_display_style]',
				'section'  => 'vi_wcaio_design_menu_cart',
				'choices'  => array(
					'product_counter' => esc_html__( 'Product Counter', 'woocommerce-cart-all-in-one' ),
					'price'           => esc_html__( 'Price', 'woocommerce-cart-all-in-one' ),
					'all'             => esc_html__( 'Product Counter & Price', 'woocommerce-cart-all-in-one' ),
				),
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[mc_cart_total]',
			array(
				'default'           => $this->settings->get_default( 'mc_cart_total' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control(
			'woo_cart_all_in_one_params[mc_cart_total]',
			array(
				'label'    => esc_html__( 'Menu Cart Price', 'woocommerce-cart-all-in-one' ),
				'type'     => 'select',
				'settings' => 'woo_cart_all_in_one_params[mc_cart_total]',
				'section'  => 'vi_wcaio_design_menu_cart',
				'choices'  => array(
					'total'    => esc_html__( 'Total', 'woocommerce-cart-all-in-one' ),
					'subtotal' => esc_html__( 'Subtotal', 'woocommerce-cart-all-in-one' ),
				),
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[mc_color]',
			array(
				'default'           => $this->settings->get_default( 'mc_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_cart_all_in_one_params[mc_color]',
				array(
					'label'    => esc_html__( 'Text Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_menu_cart',
					'settings' => 'woo_cart_all_in_one_params[mc_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[mc_hover_color]',
			array(
				'default'           => $this->settings->get_default( 'mc_hover_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[mc_hover_color]',
				array(
					'label'    => esc_html__( 'Text Color Hover', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_menu_cart',
					'settings' => 'woo_cart_all_in_one_params[mc_hover_color]',
				)
			)
		);
	}

	protected function add_section_design_sticky_atc( $wp_customize ) {
		$wp_customize->add_section( 'vi_wcaio_design_sticky_atc',
			array(
				'priority'       => 20,
				'capability'     => 'manage_options',
				'theme_supports' => '',
				'title'          => esc_html__( 'Sticky Add To Cart Button', 'woocommerce-cart-all-in-one' ),
				'panel'          => 'vi_wcaio_design',
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_enable]',
			array(
				'default'           => $this->settings->get_default( 'sb_enable' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Checkbox_Control( $wp_customize,
				'woo_cart_all_in_one_params[sb_enable]', array(
					'label'    => esc_html__( 'Enable', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sb_enable]',
					'section'  => 'vi_wcaio_design_sticky_atc',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_always_appear]',
			array(
				'default'           => $this->settings->get_default( 'sb_always_appear' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Checkbox_Control( $wp_customize,
				'woo_cart_all_in_one_params[sb_always_appear]', array(
					'label'    => esc_html__( 'Always Appear', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sb_always_appear]',
					'section'  => 'vi_wcaio_design_sticky_atc',
				) )
		);
		$select_product = array();
		if ( $select_product_id = $this->settings->get_params( 'sb_select_product' ) ) {
			$select_product[ $select_product_id ] = get_the_title( $select_product_id );
		}
		$args      = array(
			'post_type'      => 'product',
			'post_status'    => 'any',
			'posts_per_page' => 20,
		);
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$product_id                    = get_the_ID();
				$select_product_id             = $select_product_id ?: $product_id;
				$select_product[ $product_id ] = get_the_title( $product_id );
			}
		}
		wp_reset_postdata();
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_select_product]', array(
			'default'           => $this->settings->get_default( 'sb_select_product' ) ?: $select_product_id,
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sb_select_product]', array(
			'type'        => 'select',
			'priority'    => 10,
			'section'     => 'vi_wcaio_design_sticky_atc',
			'label'       => esc_html__( 'Select Product To Preview', 'woocommerce-cart-all-in-one' ),
			'choices'     => $select_product,
			'description' => esc_html__( 'Please save your changes before choose product', 'woocommerce-cart-all-in-one' ),
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_template]', array(
			'default'           => $this->settings->get_default( 'sb_template' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sb_template]', array(
			'type'    => 'select',
			'section' => 'vi_wcaio_design_sticky_atc',
			'label'   => esc_html__( 'Template', 'woocommerce-cart-all-in-one' ),
			'choices' => array(
				1 => 'Template One',
				2 => 'Template Two',
				3 => 'Template Three',
				4 => 'Template Four',
			),
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_position]', array(
			'default'           => $this->settings->get_default( 'sb_position' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sb_position]', array(
			'type'    => 'select',
			'section' => 'vi_wcaio_design_sticky_atc',
			'label'   => esc_html__( 'Position', 'woocommerce-cart-all-in-one' ),
			'choices' => array(
				0 => 'Bottom',
				1 => 'Top',
			),
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_box_shadow_color]', array(
			'default'           => $this->settings->get_default( 'sb_box_shadow_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sb_box_shadow_color]',
				array(
					'label'       => esc_html__( 'Box Shadow Color', 'woocommerce-cart-all-in-one' ),
					'section'     => 'vi_wcaio_design_sticky_atc',
					'settings'    => 'woo_cart_all_in_one_params[sb_box_shadow_color]',
					'description' => esc_html__( 'Choose default sticky bar box shadow color', 'woocommerce-cart-all-in-one' ),
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_bg_color]', array(
			'default'           => $this->settings->get_default( 'sb_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sb_bg_color]',
				array(
					'label'       => esc_html__( 'Sticky Bar Background Color', 'woocommerce-cart-all-in-one' ),
					'section'     => 'vi_wcaio_design_sticky_atc',
					'settings'    => 'woo_cart_all_in_one_params[sb_bg_color]',
					'description' => esc_html__( 'Choose default sticky bar background color', 'woocommerce-cart-all-in-one' ),
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_padding]', array(
				'default'           => $this->settings->get_default( 'sb_padding' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'wp_kses_post',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sb_padding]',
			array(
				'type'    => 'text',
				'section' => 'vi_wcaio_design_sticky_atc',
				'label'   => esc_html__( 'Padding', 'woocommerce-cart-all-in-one' )
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_border_radius]',
			array(
				'default'           => $this->settings->get_default( 'sb_border_radius' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control( new VIWCAIO_Customize_Range_Control( $wp_customize,
			'woo_cart_all_in_one_params[sb_border_radius]',
			array(
				'label'       => esc_html__( 'Border Radius(px)', 'woocommerce-cart-all-in-one' ),
				'section'     => 'vi_wcaio_design_sticky_atc',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 200,
					'step' => 1,
					'id'   => 'vi-wcaio-sb_border_radius',
				),
			)
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_pd_review]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'sb_pd_review' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control(
			new VIWCAIO_Customize_Checkbox_Control( $wp_customize, 'woo_cart_all_in_one_params[sb_pd_review]',
				array(
					'label'    => esc_html__( 'Product Ratings', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sb_pd_review]',
					'section'  => 'vi_wcaio_design_sticky_atc',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_pd_review_color]', array(
			'default'           => $this->settings->get_default( 'sb_pd_review_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sb_pd_review_color]',
				array(
					'label'    => esc_html__( 'Background Color for Product Ratings', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sticky_atc',
					'settings' => 'woo_cart_all_in_one_params[sb_pd_review_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_quantity]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'sb_quantity' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control(
			new VIWCAIO_Customize_Checkbox_Control( $wp_customize, 'woo_cart_all_in_one_params[sb_quantity]',
				array(
					'label'    => esc_html__( 'Product Quantity', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sb_quantity]',
					'section'  => 'vi_wcaio_design_sticky_atc',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_quantity_border_radius]',
			array(
				'default'           => $this->settings->get_default( 'sb_quantity_border_radius' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Range_Control( $wp_customize, 'woo_cart_all_in_one_params[sb_quantity_border_radius]',
				array(
					'label'       => esc_html__( 'Border Radius of Product Quantity(px)', 'woocommerce-cart-all-in-one' ),
					'section'     => 'vi_wcaio_design_sticky_atc',
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
						'id'   => 'vi-wcaio-sb_quantity_border_radius',
					),
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_pd_img_width]',
			array(
				'default'           => $this->settings->get_default( 'sb_pd_img_width' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control( new VIWCAIO_Customize_Range_Control( $wp_customize,
			'woo_cart_all_in_one_params[sb_pd_img_width]',
			array(
				'label'       => esc_html__( 'Product Image Width(px)', 'woocommerce-cart-all-in-one' ),
				'section'     => 'vi_wcaio_design_sticky_atc',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 200,
					'step' => 1,
					'id'   => 'vi-wcaio-sb_pd_img_width',
				),
			)
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_pd_img_height]',
			array(
				'default'           => $this->settings->get_default( 'sb_pd_img_height' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control( new VIWCAIO_Customize_Range_Control( $wp_customize,
			'woo_cart_all_in_one_params[sb_pd_img_height]',
			array(
				'label'       => esc_html__( 'Product Image Height(px)', 'woocommerce-cart-all-in-one' ),
				'section'     => 'vi_wcaio_design_sticky_atc',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 90,
					'step' => 1,
					'id'   => 'vi-wcaio-sb_pd_img_height',
				),
			)
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_pd_name_color]', array(
			'default'           => $this->settings->get_default( 'sb_pd_name_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sb_pd_name_color]',
				array(
					'label'    => esc_html__( 'Product Name Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sticky_atc',
					'settings' => 'woo_cart_all_in_one_params[sb_pd_name_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_pd_price_color1]', array(
			'default'           => $this->settings->get_default( 'sb_pd_price_color1' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sb_pd_price_color1]',
				array(
					'label'    => esc_html__( 'Regular Price Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sticky_atc',
					'settings' => 'woo_cart_all_in_one_params[sb_pd_price_color1]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_pd_price_color2]', array(
			'default'           => $this->settings->get_default( 'sb_pd_price_color2' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sb_pd_price_color2]',
				array(
					'label'    => esc_html__( 'Sale Price Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sticky_atc',
					'settings' => 'woo_cart_all_in_one_params[sb_pd_price_color2]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_bt_atc_title]', array(
				'default'           => $this->settings->get_default( 'sb_bt_atc_title' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'wp_kses_post',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sb_bt_atc_title]',
			array(
				'type'        => 'textarea',
				'section'     => 'vi_wcaio_design_sticky_atc',
				'label'       => esc_html__( 'Cart Button Text', 'woocommerce-cart-all-in-one' ),
				'description' => '{cart_icon} - ' . esc_html__( 'The cart icon', 'woocommerce-cart-all-in-one' ),
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sb_bt_atc_title_{$value}]", array(
					'default'           => $this->settings->get_default( 'sb_bt_atc_title' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'Cart Button Text', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'Cart Button Text', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sb_bt_atc_title_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'textarea',
						'section' => 'vi_wcaio_design_sticky_atc',
					)
				);
			}
		}
		$cart_icons   = $this->settings->get_class_icons( 'cart_icons_atc' );
		$cart_icons_t = array();
		foreach ( $cart_icons as $k => $class ) {
			$cart_icons_t[ $k ] = '<i class="' . $class . '"></i>';
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_bt_atc_cart_icon]',
			array(
				'default'    => $this->settings->get_default( 'sb_bt_atc_cart_icon' ),
				'type'       => 'option',
				'capability' => 'manage_options',
				'transport'  => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Radio_Control( $wp_customize, 'woo_cart_all_in_one_params[sb_bt_atc_cart_icon]',
				array(
					'label'   => esc_html__( 'Cart Icon Type', 'woocommerce-cart-all-in-one' ),
					'section' => 'vi_wcaio_design_sticky_atc',
					'choices' => $cart_icons_t
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_bt_atc_bg_color]', array(
			'default'           => $this->settings->get_default( 'sb_bt_atc_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sb_bt_atc_bg_color]',
				array(
					'label'    => esc_html__( 'Cart Button Background Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sticky_atc',
					'settings' => 'woo_cart_all_in_one_params[sb_bt_atc_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_bt_atc_color]', array(
				'default'           => $this->settings->get_default( 'sb_bt_atc_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sb_bt_atc_color]',
				array(
					'label'    => esc_html__( 'Cart Button Text Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sticky_atc',
					'settings' => 'woo_cart_all_in_one_params[sb_bt_atc_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_bt_atc_border_radius]',
			array(
				'default'           => $this->settings->get_default( 'sb_bt_atc_border_radius' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control( new VIWCAIO_Customize_Range_Control( $wp_customize,
			'woo_cart_all_in_one_params[sb_bt_atc_border_radius]',
			array(
				'label'       => esc_html__( 'Border Radius of Cart button(px)', 'woocommerce-cart-all-in-one' ),
				'section'     => 'vi_wcaio_design_sticky_atc',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 200,
					'step' => 1,
					'id'   => 'vi-wcaio-sb_bt_atc_border_radius',
				),
			)
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_bt_atc_font_size]',
			array(
				'default'           => $this->settings->get_default( 'sb_bt_atc_font_size' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Range_Control( $wp_customize,
			'woo_cart_all_in_one_params[sb_bt_atc_font_size]',
			array(
				'label'       => esc_html__( 'Font Size of Cart button(px)', 'woocommerce-cart-all-in-one' ),
				'section'     => 'vi_wcaio_design_sticky_atc',
				'input_attrs' => array(
					'min'  => 5,
					'max'  => 30,
					'step' => 1,
					'id'   => 'vi-wcaio-sb_bt_atc_font_size',
				),
			)
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_mobile_enable]',
			array(
				'default'           => $this->settings->get_default( 'sb_mobile_enable' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Checkbox_Control( $wp_customize,
				'woo_cart_all_in_one_params[sb_mobile_enable]', array(
					'label'    => esc_html__( 'Mobile Enable', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sb_mobile_enable]',
					'section'  => 'vi_wcaio_design_sticky_atc',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_mobile_position]', array(
			'default'           => $this->settings->get_default( 'sb_mobile_position' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sb_mobile_position]', array(
			'type'    => 'select',
			'section' => 'vi_wcaio_design_sticky_atc',
			'label'   => esc_html__( 'Position on Mobile', 'woocommerce-cart-all-in-one' ),
			'choices' => array(
				0 => 'Bottom',
				1 => 'Top',
			),
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_mobile_img]',
			array(
				'default'           => $this->settings->get_default( 'sb_mobile_img' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Checkbox_Control( $wp_customize,
				'woo_cart_all_in_one_params[sb_mobile_img]', array(
					'label'    => esc_html__( 'Enable Product Image on Mobile', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sb_mobile_img]',
					'section'  => 'vi_wcaio_design_sticky_atc',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_mobile_bt_atc_bg_color]', array(
			'default'           => $this->settings->get_default( 'sb_mobile_bt_atc_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sb_mobile_bt_atc_bg_color]',
				array(
					'label'    => esc_html__( 'Cart Button Background Color on Mobile', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sticky_atc',
					'settings' => 'woo_cart_all_in_one_params[sb_mobile_bt_atc_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_mobile_bt_atc_color]', array(
				'default'           => $this->settings->get_default( 'sb_mobile_bt_atc_color' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sb_mobile_bt_atc_color]',
				array(
					'label'    => esc_html__( 'Cart Button Text Color on Mobile', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_sticky_atc',
					'settings' => 'woo_cart_all_in_one_params[sb_mobile_bt_atc_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_mobile_bt_atc_border_radius]',
			array(
				'default'           => $this->settings->get_default( 'sb_mobile_bt_atc_border_radius' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );
		$wp_customize->add_control( new VIWCAIO_Customize_Range_Control( $wp_customize,
			'woo_cart_all_in_one_params[sb_mobile_bt_atc_border_radius]',
			array(
				'label'       => esc_html__( 'Border Radius of Cart Button on Mobile(px)', 'woocommerce-cart-all-in-one' ),
				'section'     => 'vi_wcaio_design_sticky_atc',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 200,
					'step' => 1,
					'id'   => 'vi-wcaio-sb_mobile_bt_atc_border_radius',
				),
			)
		) );
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sb_mobile_bt_atc_font_size]',
			array(
				'default'           => $this->settings->get_default( 'sb_mobile_bt_atc_font_size' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Range_Control( $wp_customize,
			'woo_cart_all_in_one_params[sb_mobile_bt_atc_font_size]',
			array(
				'label'       => esc_html__( 'Font Size of Cart Button on Mobile(px)', 'woocommerce-cart-all-in-one' ),
				'section'     => 'vi_wcaio_design_sticky_atc',
				'input_attrs' => array(
					'min'  => 5,
					'max'  => 30,
					'step' => 1,
					'id'   => 'vi-wcaio-sb_mobile_bt_atc_font_size',
				),
			)
		) );
	}

	protected function add_section_design_checkout( $wp_customize ) {
		$wp_customize->add_section( 'vi_wcaio_design_checkout', array(
				'priority'       => 20,
				'capability'     => 'manage_options',
				'theme_supports' => '',
				'title'          => esc_html__( 'Checkout', 'woocommerce-cart-all-in-one' ),
				'panel'          => 'vi_wcaio_design',
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_enable]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_enable' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Checkbox_Control( $wp_customize,
				'woo_cart_all_in_one_params[sc_checkout_enable]', array(
					'label'    => esc_html__( 'Enable', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_enable]',
					'section'  => 'vi_wcaio_design_checkout',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_mobile_enable]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_mobile_enable' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( new VIWCAIO_Customize_Checkbox_Control( $wp_customize,
				'woo_cart_all_in_one_params[sc_checkout_mobile_enable]', array(
					'label'    => esc_html__( 'Mobile Enable', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_mobile_enable]',
					'section'  => 'vi_wcaio_design_checkout',
				) )
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bg_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bg_color]',
				array(
					'label'    => esc_html__( 'Background', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_keyboard_nav]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_keyboard_nav' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Checkbox_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_keyboard_nav]',
				array(
					'label'       => esc_html__( 'Keyboard Navigation', 'woocommerce-cart-all-in-one' ),
					'settings'    => 'woo_cart_all_in_one_params[sc_checkout_keyboard_nav]',
					'section'     => 'vi_wcaio_design_checkout',
					'description' => esc_html__( 'Use the arrow keys on keyboard to move steps in checkout process on Cart', 'woocommerce-cart-all-in-one' ),
				)
			)
		);
		if ( 'no' !== get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
			$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_login]',
				array(
					'default'           => $this->settings->get_default( 'sc_checkout_login' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new VIWCAIO_Customize_Checkbox_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_login]',
					array(
						'label'       => esc_html__( 'Enable Login Step', 'woocommerce-cart-all-in-one' ),
						'settings'    => 'woo_cart_all_in_one_params[sc_checkout_login]',
						'section'     => 'vi_wcaio_design_checkout',
						'description' => esc_html__( 'Allow customers to log into an existing account during checkout', 'woocommerce-cart-all-in-one' ),
					)
				)
			);
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_billing_title]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_billing_title' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_checkout_billing_title]',
			array(
				'label'   => esc_html__( 'Billing Step Title', 'woocommerce-cart-all-in-one' ),
				'type'    => 'text',
				'section' => 'vi_wcaio_design_checkout',
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sc_checkout_billing_title_{$value}]", array(
					'default'           => $this->settings->get_default( 'sc_checkout_billing_title' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'Billing Step Title', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'Billing Step Title', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sc_checkout_billing_title_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'text',
						'section' => 'vi_wcaio_design_checkout',
					)
				);
			}
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_shipping_title]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_shipping_title' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_checkout_shipping_title]',
			array(
				'label'   => esc_html__( 'Shipping Step Title', 'woocommerce-cart-all-in-one' ),
				'type'    => 'text',
				'section' => 'vi_wcaio_design_checkout',
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sc_checkout_shipping_title_{$value}]", array(
					'default'           => $this->settings->get_default( 'sc_checkout_shipping_title' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'Shipping Step Title', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'Shipping Step Title', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sc_checkout_shipping_title_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'text',
						'section' => 'vi_wcaio_design_checkout',
					)
				);
			}
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_order_review_title]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_order_review_title' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_checkout_order_review_title]',
			array(
				'label'   => esc_html__( 'Order Review Step Title', 'woocommerce-cart-all-in-one' ),
				'type'    => 'text',
				'section' => 'vi_wcaio_design_checkout',
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sc_checkout_order_review_title_{$value}]", array(
					'default'           => $this->settings->get_default( 'sc_checkout_order_review_title' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'Order Review Step Title', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'Order Review Step Title', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sc_checkout_order_review_title_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'text',
						'section' => 'vi_wcaio_design_checkout',
					)
				);
			}
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_payment_title]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_payment_title' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_checkout_payment_title]',
			array(
				'label'   => esc_html__( 'Payment Step Title', 'woocommerce-cart-all-in-one' ),
				'type'    => 'text',
				'section' => 'vi_wcaio_design_checkout',
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sc_checkout_payment_title_{$value}]", array(
					'default'           => $this->settings->get_default( 'sc_checkout_payment_title' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'Payment Step Title', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'Payment Step Title', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sc_checkout_payment_title_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'text',
						'section' => 'vi_wcaio_design_checkout',
					)
				);
			}
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_billing_shipping]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_billing_shipping' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Checkbox_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_billing_shipping]',
				array(
					'label'    => esc_html__( 'Display Both "Billing" And "Shipping" in One Step', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_billing_shipping]',
					'section'  => 'vi_wcaio_design_checkout',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_billing_shipping_title]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_billing_shipping_title' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_checkout_billing_shipping_title]',
			array(
				'label'   => esc_html__( 'The Title of Billing and Shipping Step', 'woocommerce-cart-all-in-one' ),
				'type'    => 'text',
				'section' => 'vi_wcaio_design_checkout',
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sc_checkout_billing_shipping_title_{$value}]", array(
					'default'           => $this->settings->get_default( 'sc_checkout_billing_shipping_title' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'The Title of Billing and Shipping Step', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'The Title of Billing and Shipping Step', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sc_checkout_billing_shipping_title_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'text',
						'section' => 'vi_wcaio_design_checkout',
					)
				);
			}
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_order_payment]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_order_payment' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Checkbox_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_order_payment]',
				array(
					'label'    => esc_html__( 'Display Both "Order" And "Payment" in One Step', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_order_payment]',
					'section'  => 'vi_wcaio_design_checkout',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_order_payment_title]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_order_payment_title' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_checkout_order_payment_title]',
			array(
				'label'   => esc_html__( 'The Title of Order and Payment Step', 'woocommerce-cart-all-in-one' ),
				'type'    => 'text',
				'section' => 'vi_wcaio_design_checkout',
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sc_checkout_order_payment_title_{$value}]", array(
					'default'           => $this->settings->get_default( 'sc_checkout_order_payment_title' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'The Title of Order and Payment Step', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'The Title of Order and Payment Step', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sc_checkout_order_payment_title_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'text',
						'section' => 'vi_wcaio_design_checkout',
					)
				);
			}
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_btc_enable]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_bt_btc_enable' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Checkbox_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_btc_enable]',
				array(
					'label'       => esc_html__( 'Back To Cart Button Enable', 'woocommerce-cart-all-in-one' ),
					'settings'    => 'woo_cart_all_in_one_params[sc_checkout_bt_btc_enable]',
					'section'     => 'vi_wcaio_design_checkout',
					'description' => esc_html__( 'Display the back to cart button in checkout steps', 'woocommerce-cart-all-in-one' )
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_btc_title]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_bt_btc_title' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_checkout_bt_btc_title]',
			array(
				'label'       => esc_html__( 'Back To Cart Button Text', 'woocommerce-cart-all-in-one' ),
				'type'        => 'text',
				'section'     => 'vi_wcaio_design_checkout',
				'description' => '{back_icon} - ' . esc_html__( 'The back icon', 'woocommerce-cart-all-in-one' ),
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sc_checkout_bt_btc_title_{$value}]", array(
					'default'           => $this->settings->get_default( 'sc_checkout_bt_btc_title' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'Back To Cart Button Text', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'Back To Cart Button Text', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sc_checkout_bt_btc_title_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'text',
						'section' => 'vi_wcaio_design_checkout',
					)
				);
			}
		}
		$back_icons   = $this->settings->get_class_icons( 'back_icons' );
		$back_icons_t = array();
		foreach ( $back_icons as $k => $class ) {
			$back_icons_t[ $k ] = '<i class="' . $class . '"></i>';
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_btc_icon]',
			array(
				'default'    => $this->settings->get_default( 'sc_checkout_bt_btc_icon' ),
				'type'       => 'option',
				'capability' => 'manage_options',
				'transport'  => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Radio_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_btc_icon]',
				array(
					'label'   => esc_html__( 'Back Icon Type', 'woocommerce-cart-all-in-one' ),
					'section' => 'vi_wcaio_design_checkout',
					'choices' => $back_icons_t
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_btc_bg_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_bt_btc_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_btc_bg_color]',
				array(
					'label'    => esc_html__( 'Back To Cart Button Background', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_bt_btc_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_btc_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_bt_btc_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_btc_color]',
				array(
					'label'    => esc_html__( 'Back to Cart Button Text Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_bt_btc_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_btc_hover_bg_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_bt_btc_hover_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_btc_hover_bg_color]',
				array(
					'label'    => esc_html__( 'Back To Cart Button Hover Background', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_bt_btc_hover_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_btc_hover_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_bt_btc_hover_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_btc_hover_color]',
				array(
					'label'    => esc_html__( 'Back to Cart Button Hover Text Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_bt_btc_hover_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_btc_border_radius]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_bt_btc_border_radius' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Range_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_btc_border_radius]',
				array(
					'label'       => esc_html__( 'Back To Cart Button Radius(px)', 'woocommerce-cart-all-in-one' ),
					'section'     => 'vi_wcaio_design_checkout',
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
						'id'   => 'vi-wcaio-sc_checkout_bt_btc_border_radius',
					),
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_next_title]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_bt_next_title' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_checkout_bt_next_title]',
			array(
				'label'       => esc_html__( 'Next Button Text', 'woocommerce-cart-all-in-one' ),
				'type'        => 'text',
				'section'     => 'vi_wcaio_design_checkout',
				'description' => '{next_title} - ' . esc_html__( 'The title of the next step', 'woocommerce-cart-all-in-one' ),
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sc_checkout_bt_next_title_{$value}]", array(
					'default'           => $this->settings->get_default( 'sc_checkout_bt_next_title' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'Next Button Text', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'Next Button Text', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sc_checkout_bt_next_title_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'text',
						'section' => 'vi_wcaio_design_checkout',
					)
				);
			}
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_next_bg_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_bt_next_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_next_bg_color]',
				array(
					'label'    => esc_html__( 'Next Button Background', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_bt_next_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_next_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_bt_next_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_next_color]',
				array(
					'label'    => esc_html__( 'Next Button Text Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_bt_next_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_next_hover_bg_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_bt_next_hover_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_next_hover_bg_color]',
				array(
					'label'    => esc_html__( 'Next Button Hover Background', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_bt_next_hover_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_next_hover_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_bt_next_hover_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_next_hover_color]',
				array(
					'label'    => esc_html__( 'Next Button Hover Text Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_bt_next_hover_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_next_border_radius]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_bt_next_border_radius' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Range_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_next_border_radius]',
				array(
					'label'       => esc_html__( 'Next Button Radius(px)', 'woocommerce-cart-all-in-one' ),
					'section'     => 'vi_wcaio_design_checkout',
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
						'id'   => 'vi-wcaio-sc_checkout_bt_next_border_radius',
					),
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_pre_title]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_bt_pre_title' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_checkout_bt_pre_title]',
			array(
				'label'       => esc_html__( 'Previous Button Text', 'woocommerce-cart-all-in-one' ),
				'type'        => 'text',
				'section'     => 'vi_wcaio_design_checkout',
				'description' => '{pre_title} - ' . esc_html__( 'The title of the previous steps', 'woocommerce-cart-all-in-one' ),
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sc_checkout_bt_pre_title_{$value}]", array(
					'default'           => $this->settings->get_default( 'sc_checkout_bt_pre_title' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'Previous Button Text', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'Previous Button Text', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sc_checkout_bt_pre_title_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'text',
						'section' => 'vi_wcaio_design_checkout',
					)
				);
			}
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_pre_bg_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_bt_pre_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_pre_bg_color]',
				array(
					'label'    => esc_html__( 'Previous Button Background', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_bt_pre_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_pre_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_bt_pre_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_pre_color]',
				array(
					'label'    => esc_html__( 'Previous Button Text Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_bt_pre_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_pre_hover_bg_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_bt_pre_hover_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_pre_hover_bg_color]',
				array(
					'label'    => esc_html__( 'Previous Button Hover Background', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_bt_pre_hover_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_pre_hover_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_bt_pre_hover_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_pre_hover_color]',
				array(
					'label'    => esc_html__( 'Previous Button Hover Text Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_bt_pre_hover_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_pre_border_radius]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_bt_pre_border_radius' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Range_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_pre_border_radius]',
				array(
					'label'       => esc_html__( 'Previous Button Radius(px)', 'woocommerce-cart-all-in-one' ),
					'section'     => 'vi_wcaio_design_checkout',
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
						'id'   => 'vi-wcaio-sc_checkout_bt_pre_border_radius',
					),
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_place_order_title]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_bt_place_order_title' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[sc_checkout_bt_place_order_title]',
			array(
				'label'       => esc_html__( 'Place Order Button Text', 'woocommerce-cart-all-in-one' ),
				'type'        => 'text',
				'section'     => 'vi_wcaio_design_checkout',
				'description' => '{order_button_text} - ' . esc_html__( 'Use the title of Place order button', 'woocommerce-cart-all-in-one' ),
			)
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$wp_customize->add_setting( "woo_cart_all_in_one_params[sc_checkout_bt_place_order_title_{$value}]", array(
					'default'           => $this->settings->get_default( 'sc_checkout_bt_place_order_title' ),
					'type'              => 'option',
					'capability'        => 'manage_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				) );
				$label = esc_html__( 'Place Order Button Text', 'woocommerce-cart-all-in-one' ) . "( {$value} )";
				if ( ! empty( $this->languages_data[ $value ]['translated_name'] ) ) {
					$label = esc_html__( 'Place Order Button Text', 'woocommerce-cart-all-in-one' ) . "( {$value} - {$this->languages_data[ $value ]['translated_name']} )";
				}
				$wp_customize->add_control( "woo_cart_all_in_one_params[sc_checkout_bt_place_order_title_{$value}]",
					array(
						'label'   => $label,
						'type'    => 'text',
						'section' => 'vi_wcaio_design_checkout',
					)
				);
			}
		}
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_place_order_bg_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_bt_place_order_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_place_order_bg_color]',
				array(
					'label'    => esc_html__( 'Place Order Button Background', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_bt_place_order_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_place_order_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_bt_place_order_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_place_order_color]',
				array(
					'label'    => esc_html__( 'Place Order Button Text Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_bt_place_order_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_place_order_hover_bg_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_bt_place_order_hover_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_place_order_hover_bg_color]',
				array(
					'label'    => esc_html__( 'Place Order Button Hover Background', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_bt_place_order_hover_bg_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_place_order_hover_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_bt_place_order_hover_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_place_order_hover_color]',
				array(
					'label'    => esc_html__( 'Place Order Button Hover Text Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_bt_place_order_hover_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_bt_place_order_border_radius]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_bt_place_order_border_radius' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Range_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_bt_place_order_border_radius]',
				array(
					'label'       => esc_html__( 'Place Order Button Radius(px)', 'woocommerce-cart-all-in-one' ),
					'section'     => 'vi_wcaio_design_checkout',
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
						'id'   => 'vi-wcaio-sc_checkout_bt_place_order_border_radius',
					),
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_nav_bar]',
			array(
				'default'           => $this->settings->get_default( 'sc_checkout_nav_bar' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new VIWCAIO_Customize_Checkbox_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_nav_bar]',
				array(
					'label'    => esc_html__( 'Display The Bar For Moving Between Steps', 'woocommerce-cart-all-in-one' ),
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_nav_bar]',
					'section'  => 'vi_wcaio_design_checkout',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_nav_bar_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_nav_bar_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_nav_bar_color]',
				array(
					'label'    => esc_html__( 'Moving Bar Color', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_nav_bar_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_nav_bar_hover_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_nav_bar_hover_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_nav_bar_hover_color]',
				array(
					'label'    => esc_html__( 'Moving Bar Color When Hovered Mouse In', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_nav_bar_hover_color]',
				)
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[sc_checkout_nav_bar_selected_color]', array(
			'default'           => $this->settings->get_default( 'sc_checkout_nav_bar_selected_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'woo_cart_all_in_one_params[sc_checkout_nav_bar_selected_color]',
				array(
					'label'    => esc_html__( 'Moving Bar Color When Selected ', 'woocommerce-cart-all-in-one' ),
					'section'  => 'vi_wcaio_design_checkout',
					'settings' => 'woo_cart_all_in_one_params[sc_checkout_nav_bar_selected_color]',
				)
			)
		);
	}
	protected function add_section_design_custom_css( $wp_customize ) {
		$wp_customize->add_section( 'vi_wcaio_design_custom_css', array(
				'priority'       => 20,
				'capability'     => 'manage_options',
				'theme_supports' => '',
				'title'          => esc_html__( 'Custom CSS', 'woocommerce-cart-all-in-one' ),
				'panel'          => 'vi_wcaio_design',
			)
		);
		$wp_customize->add_setting( 'woo_cart_all_in_one_params[custom_css]', array(
				'default'           => $this->settings->get_default( 'custom_css' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'wp_kses_post',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 'woo_cart_all_in_one_params[custom_css]',
			array(
				'type'     => 'textarea',
				'priority' => 10,
				'section'  => 'vi_wcaio_design_custom_css',
				'label'    => esc_html__( 'Custom CSS', 'woocommerce-cart-all-in-one' )
			)
		);
	}
}