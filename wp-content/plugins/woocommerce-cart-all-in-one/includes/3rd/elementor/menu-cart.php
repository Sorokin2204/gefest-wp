<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
class VIWCAIO_Elementor_Menu_Cart extends Elementor\Widget_Base {
	public static $slug = 'viwcaio-elementor-menu-cart';
	public function get_name() {
		return 'woocommerce-cart-all-in-one';
	}
	public function get_title() {
		return esc_html__( 'Menu Cart( VillaTheme)', 'woocommerce-cart-all-in-one' );
	}
	public function get_icon() {
		return 'eicon-woo-cart villatheme-elementor-icon';
	}
	public function get_categories() {
		return [ 'woocommerce-elements' ];
	}
	protected function _register_controls() {
		$settings = new  VIWCAIO_CART_ALL_IN_ONE_DATA();
		$this->start_controls_section(
			'general',
			[
				'label' => esc_html__( 'General', 'woocommerce-cart-all-in-one' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'mobile_enable',
			[
				'label'        => esc_html__( 'Display On Mobile', 'woocommerce-cart-all-in-one' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'woocommerce-cart-all-in-one' ),
				'label_off'    => esc_html__( 'No', 'woocommerce-cart-all-in-one' ),
				'return_value' => '1',
			]
		);
		$this->add_control(
			'empty_enable',
			[
				'label'        => esc_html__( 'Visible empty menu cart', 'woocommerce-cart-all-in-one' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '',
				'description'  => esc_html__( 'Show Menu Cart cart even when it is empty', 'woocommerce-cart-all-in-one' ),
				'label_on'     => esc_html__( 'Yes', 'woocommerce-cart-all-in-one' ),
				'label_off'    => esc_html__( 'No', 'woocommerce-cart-all-in-one' ),
				'return_value' => '1',
			]
		);
		$this->add_control(
			'nav_page',
			[
				'label'       => esc_html__( 'Navigation Page', 'woocommerce-cart-all-in-one' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'cart',
				'description' => esc_html__( 'Choose the page redirected to when clicking on Menu Cart', 'woocommerce-cart-all-in-one' ),
				'options'     => [
					'0'     => esc_html__( 'None', 'woocommerce-cart-all-in-one' ),
					'cart'     => esc_html__( 'Cart page', 'woocommerce-cart-all-in-one' ),
					'checkout' => esc_html__( 'Checkout page', 'woocommerce-cart-all-in-one' ),
				],
			]
		);
		$this->add_control(
			'content_enable',
			[
				'label'        => esc_html__( 'Show Content Cart', 'woocommerce-cart-all-in-one' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'woocommerce-cart-all-in-one' ),
				'label_off'    => esc_html__( 'No', 'woocommerce-cart-all-in-one' ),
				'return_value' => '1',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'design',
			[
				'label' => esc_html__( 'Design', 'woocommerce-cart-all-in-one' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$cart_icons   = $settings->get_class_icons( 'cart_icons' );
		$cart_icons_t = array();
		foreach ( $cart_icons as $k => $class ) {
			$cart_icons_t[ $k ] = array(
				'title' => $class,
				'icon'  => $class,
			);
		}
		$this->add_control(
			'icon_type',
			[
				'label'       => esc_html__( 'Cart Icon Type', 'woocommerce-cart-all-in-one' ),
				'type'        => \Elementor\Controls_Manager::CHOOSE,
				'label_block' => true,
				'default'     => $settings->get_params( 'mc_icon' ),
				'options'     => $cart_icons_t,
			]
		);
		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Cart Icon Color', 'woocommerce-cart-all-in-one' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => $settings->get_params( 'mc_icon_color' ),
				'selectors' => [
					"{{WRAPPER}} .vi-wcaio-menu-cart .vi-wcaio-menu-cart-icon i" => 'color: {{VALUE}};',
				],
				'dynamic'   => [
					'active' => false,
				],
			]
		);
		$this->add_control(
			'icon_color_hover',
			[
				'label'     => esc_html__( 'Cart Icon Hover Color', 'woocommerce-cart-all-in-one' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => $settings->get_params( 'mc_icon_hover_color' ),
				'selectors' => [
					"{{WRAPPER}} .vi-wcaio-menu-cart .vi-wcaio-menu-cart-nav-wrap:hover .vi-wcaio-menu-cart-icon i" => 'color: {{VALUE}};',
				],
				'dynamic'   => [
					'active' => false,
				],
			]
		);
		$this->add_control(
			'display_style',
			[
				'label'   => esc_html__( 'Menu Cart Text', 'woocommerce-cart-all-in-one' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => $settings->get_params( 'mc_display_style' ),
				'options' => [
					'product_counter' => esc_html__( 'Product Counter', 'woocommerce-cart-all-in-one' ),
					'price'           => esc_html__( 'Price', 'woocommerce-cart-all-in-one' ),
					'all'             => esc_html__( 'Product Counter & Price', 'woocommerce-cart-all-in-one' ),
				],
			]
		);
		$this->add_control(
			'cart_total',
			[
				'label'   => esc_html__( 'Menu Cart Price', 'woocommerce-cart-all-in-one' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => $settings->get_params( 'mc_cart_total' ),
				'options' => [
					'total'    => esc_html__( 'Total', 'woocommerce-cart-all-in-one' ),
					'subtotal' => esc_html__( 'Subtotal', 'woocommerce-cart-all-in-one' ),
				],
			]
		);
		$this->add_control(
			'color',
			[
				'label'     => esc_html__( 'Text Color', 'woocommerce-cart-all-in-one' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => $settings->get_params( 'mc_color' ),
				'selectors' => [
					"{{WRAPPER}} .vi-wcaio-menu-cart .vi-wcaio-menu-cart-text-wrap *" => 'color: {{VALUE}};',
				],
				'dynamic'   => [
					'active' => false,
				],
			]
		);
		$this->add_control(
			'color_hover',
			[
				'label'     => esc_html__( 'Text Color Hover', 'woocommerce-cart-all-in-one' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => $settings->get_params( 'mc_hover_color' ),
				'selectors' => [
					"{{WRAPPER}} .vi-wcaio-menu-cart .vi-wcaio-menu-cart-nav-wrap:hover .vi-wcaio-menu-cart-text-wrap *" => 'color: {{VALUE}};',
				],
				'dynamic'   => [
					'active' => false,
				],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->get_menu_cart_html( $settings );
	}

	public function render_plain_content() {
		$settings = $this->get_settings_for_display();
		$this->get_menu_cart_html( $settings );
	}
	private function get_menu_cart_html( $settings ) {
		if ( ! wp_style_is( 'vi-wcaio-cart-icons' ) ) {
			wp_enqueue_style( 'vi-wcaio-cart-icons' );
			wp_enqueue_style( 'vi-wcaio-menu-cart' );
		}
		if ( ! wp_script_is( 'vi-wcaio-menu-cart' ) ) {
			wp_enqueue_script( 'vi-wcaio-menu-cart' );
		}
		$settings_data      = new  VIWCAIO_CART_ALL_IN_ONE_DATA();
		$class              = array(
			'vi-wcaio-menu-cart',
		);
		$class[]            = is_rtl() ? 'vi-wcaio-menu-cart-rtl' : '';
		$content_enable     = $settings['content_enable'] ?? '';
		$class[]            = $content_enable ? 'vi-wcaio-menu-cart-show' : '';
		$wc_cart            = WC()->cart ?? '';
		$class[]            = ( ! is_admin() && ! $wc_cart ) || ( ! $settings['empty_enable'] && $wc_cart && $wc_cart->is_empty() ) ? 'vi-wcaio-disabled' : '';
		$class              = trim( implode( ' ', $class ) );
		$mc_nav_page = $settings['nav_page'] ?? '' ;
		$nav_url            = $mc_nav_page ? get_permalink( wc_get_page_id( $mc_nav_page ) ) : '#';
		$nav_title='';
		switch ($mc_nav_page){
            case 'cart':
	            $nav_title=esc_html__( 'View your shopping cart', 'woocommerce-cart-all-in-one' );
                break;
            case 'checkout':
	            $nav_title=esc_html__( 'Quick checkout', 'woocommerce-cart-all-in-one' );
                break;
        }
		$cart_content_count = is_admin() || ! $wc_cart ? 1 : $wc_cart->get_cart_contents_count();
		$mc_display_style   = $settings_data->get_params( 'mc_display_style' );
		$mc_cart_total      = $settings_data->get_params( 'mc_cart_total' );
		if ( $mc_cart_total === 'total' ) {
			$cart_total = is_admin() || ! $wc_cart ? wc_price( 0 ) : $wc_cart->get_total();
		} else {
			$cart_total = is_admin() || ! $wc_cart ? wc_price( 0 ) : $wc_cart->get_cart_subtotal();
		}
		?>
        <div class="<?php echo esc_attr( $class ); ?>">
            <a href="<?php echo esc_url( $nav_url ); ?>" title="<?php echo apply_filters( 'vi_wcaio_menu_nav_title', esc_attr( $nav_title ) ); ?>" class="vi-wcaio-menu-cart-nav-wrap">
				<span class="vi-wcaio-menu-cart-icon">
					<i class="<?php echo esc_attr( $settings_data->get_class_icon( $settings['icon_type'] ?? '1', 'cart_icons' ) ); ?>"></i>
				</span>
                <span class="vi-wcaio-menu-cart-text-wrap">
					<?php
					VIWCAIO_CART_ALL_IN_ONE_Frontend_Menu_Cart::get_menu_cart_text( $mc_display_style, $cart_total, $cart_content_count );
					?>
				</span>
            </a>
			<?php
			if ( $wc_cart && $content_enable && ! is_cart() && ! is_checkout() ) {
				?>
                <div class="vi-wcaio-menu-cart-content-wrap">
                    <div class="widget woocommerce widget_shopping_cart">
                        <div class="widget_shopping_cart_content">
							<?php woocommerce_mini_cart(); ?>
                        </div>
                    </div>
                </div>
				<?php
			}
			?>
        </div>
		<?php
	}
}