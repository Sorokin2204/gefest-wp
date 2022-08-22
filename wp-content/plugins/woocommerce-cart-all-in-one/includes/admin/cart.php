<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VIWCAIO_CART_ALL_IN_ONE_Admin_Cart {
	protected $settings, $error;
	protected $default_language, $languages, $languages_data;

	public function __construct() {
		$this->settings         = new VIWCAIO_CART_ALL_IN_ONE_DATA();
		$this->languages        = array();
		$this->languages_data   = array();
		$this->default_language = '';
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 10 );
		add_action( 'admin_init', array( $this, 'save_settings' ), 99 );
		add_action( 'admin_init', array( $this, 'check_update' ), 100 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), PHP_INT_MAX );
	}

	public function admin_menu() {
		add_menu_page(
			esc_html__( 'Cart All In One For WooCommerce', 'woocommerce-cart-all-in-one' ),
			esc_html__( 'Cart All In One', 'woocommerce-cart-all-in-one' ),
			'manage_options',
			'woocommerce-cart-all-in-one',
			array( $this, 'settings_callback' ),
			'dashicons-cart',
			2 );
	}
	public function check_update() {
		/**
		 * Check update
		 */
		if ( class_exists( 'VillaTheme_Plugin_Check_Update' ) ) {
			$setting_url = admin_url( 'admin.php?page=woocommerce-cart-all-in-one' );
			$key         = $this->settings->get_params( 'purchased_code' );
			new VillaTheme_Plugin_Check_Update (
				VIWCAIO_CART_ALL_IN_ONE_VERSION,                    // current version
				'https://villatheme.com/wp-json/downloads/v3',  // update path
				'woocommerce-cart-all-in-one/woocommerce-cart-all-in-one.php',                  // plugin file slug
				'woocommerce-cart-all-in-one', '27570', $key, $setting_url
			);
			new VillaTheme_Plugin_Updater( 'woocommerce-cart-all-in-one/woocommerce-cart-all-in-one.php', 'woocommerce-cart-all-in-one', $setting_url );
		}
	}

	public function save_settings() {
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		if ( $page !== 'woocommerce-cart-all-in-one' ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
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
		if ( ! isset( $_POST['_vi_wcaio_setting_cart'] ) || ! wp_verify_nonce( $_POST['_vi_wcaio_setting_cart'], '_vi_wcaio_setting_cart_action' ) ) {
			return;
		}
		if ( ! isset( $_POST['vi-wcaio-save'] ) && ! isset( $_POST['vi-wcaio-check_key'] ) ) {
			return;
		}
		if ( isset( $_POST['vi-wcaio-check_key'] ) ) {
			delete_transient( '_site_transient_update_plugins' );
			delete_transient( 'villatheme_item_27570' );
			delete_option( 'woocommerce-cart-all-in-one_messages' );
		}
		global $vi_wcaio_settings;
		$map_args_1 = array(
			'sc_enable',
			'sc_mobile_enable',
			'sc_empty_enable',
			'sc_icon_enable',
			'sc_assign_page',
			'sc_content_class_open',
			'sc_checkout_enable',
			'sc_checkout_mobile_enable',
			'mc_enable',
			'mc_mobile_enable',
			'mc_empty_enable',
			'mc_display_style',
			'mc_cart_total',
			'ajax_atc',
			'ajax_atc_notice',
			'ajax_atc_pd_variable',
			'pd_variable_bt_atc_text_enable',
			'sb_enable',
			'sb_mobile_enable',
			'sb_use_viwpvs',
			'vp_enable',
			'vp_mobile_enable',
			'vp_single_position',
			'vp_pd_column',
			'vp_pd_limit',
			'vp_slider_loop',
			'vp_slider_move',
			'vp_slider_auto',
			'vp_slider_speed',
			'vp_slider_pause',
			'purchased_code',
		);
		$map_args_2 = array(
			'pd_variable_bt_atc_text',
			'vp_title'
		);
		if ( count( $this->languages ) ) {
			foreach ( $this->languages as $key => $value ) {
				$map_args_2[] = 'pd_variable_bt_atc_text_' . $value;
				$map_args_2[] = 'vp_title_' . $value;
			}
		}
		$map_args_3 = array(
			'mc_menu_display',
			'ajax_atc_pd_exclude',
			'sb_pd_exclude',
			'sb_cats_exclude',
		);
		$args       = array();
		foreach ( $map_args_1 as $item ) {
			$args[ $item ] = isset( $_POST[ $item ] ) ? sanitize_text_field( wp_unslash( $_POST[ $item ] ) ) : '';
		}
		foreach ( $map_args_2 as $item ) {
			$args[ $item ] = isset( $_POST[ $item ] ) ? wp_kses_post( wp_unslash( $_POST[ $item ] ) ) : '';
		}
		foreach ( $map_args_3 as $item ) {
			$args[ $item ] = isset( $_POST[ $item ] ) ? viwcaio_sanitize_fields( $_POST[ $item ] ) : array();
		}
		$args = wp_parse_args( $args, get_option( 'woo_cart_all_in_one_params', $vi_wcaio_settings ) );
		if ( is_plugin_active( 'wp-fastest-cache/wpFastestCache.php' ) ) {
			$cache = new WpFastestCache();
			$cache->deleteCache( true );
		}
		$vi_wcaio_settings = $args;
		update_option( 'woo_cart_all_in_one_params', $args );
	}

	public function settings_callback() {
		$this->settings = new VIWCAIO_CART_ALL_IN_ONE_DATA();
		$admin          = 'VIWCAIO_CART_ALL_IN_ONE_Admin_Settings';
		?>
        <div class="wrap">
            <h2><?php esc_html_e( 'WooCommerce Cart All in One', 'woocommerce-cart-all-in-one' ); ?></h2>
            <div id="vi-wcaio-wrap-message" class="error <?php echo $this->error ? '' : esc_attr( 'vi-wcaio-disabled' ); ?>">
                <p><?php echo esc_html( $this->error ); ?></p>
            </div>
            <div class="vi-ui raised">
                <form class="vi-ui form" method="post">
					<?php
					wp_nonce_field( '_vi_wcaio_setting_cart_action', '_vi_wcaio_setting_cart' );
					?>
                    <div class="vi-ui top tabular vi-ui-main attached menu">
                        <a class="item active" data-tab="sidebar_cart"><?php esc_html_e( 'Sidebar Cart', 'woocommerce-cart-all-in-one' ); ?></a>
                        <a class="item" data-tab="menu_cart"><?php esc_html_e( 'Menu Cart', 'woocommerce-cart-all-in-one' ); ?></a>
                        <a class="item" data-tab="atc_button"><?php esc_html_e( 'Add To Cart Button', 'woocommerce-cart-all-in-one' ); ?></a>
                        <a class="item" data-tab="recently_viewed"><?php esc_html_e( 'Recently Viewed Products', 'woocommerce-cart-all-in-one' ); ?></a>
                        <a class="item" data-tab="update"><?php esc_html_e( 'Update', 'woocommerce-cart-all-in-one' ); ?></a>
                    </div>
                    <div class="vi-ui bottom attached tab segment active" data-tab="sidebar_cart">
						<?php
						$sc_enable                 = $this->settings->get_params( 'sc_enable' );
						$sc_mobile_enable          = $this->settings->get_params( 'sc_mobile_enable' );
						$sc_empty_enable           = $this->settings->get_params( 'sc_empty_enable' );
						$sc_icon_enable            = $this->settings->get_params( 'sc_icon_enable' );
						$sc_assign_page            = $this->settings->get_params( 'sc_assign_page' );
						$sc_content_class_open     = $this->settings->get_params( 'sc_content_class_open' );
						$sc_checkout_enable        = $this->settings->get_params( 'sc_checkout_enable' );
						$sc_checkout_mobile_enable = $this->settings->get_params( 'sc_checkout_mobile_enable' );
						?>
                        <table class="form-table">
                            <tr>
                                <th>
                                    <label for="vi-wcaio-sc_enable-checkbox"><?php esc_html_e( 'Enable', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui checkbox toggle">
                                        <input type="hidden" name="sc_enable" id="vi-wcaio-sc_enable" value="<?php echo esc_attr( $sc_enable ); ?>">
                                        <input type="checkbox" id="vi-wcaio-sc_enable-checkbox" class="vi-wcaio-sc_enable-checkbox"
											<?php checked( $sc_enable, 1 ); ?>><label></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-sc_mobile_enable-checkbox"><?php esc_html_e( 'Mobile enable', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui checkbox toggle">
                                        <input type="hidden" name="sc_mobile_enable" id="vi-wcaio-sc_mobile_enable" value="<?php echo esc_attr( $sc_mobile_enable ); ?>">
                                        <input type="checkbox" id="vi-wcaio-sc_mobile_enable-checkbox" class="vi-wcaio-sc_mobile_enable-checkbox"
											<?php checked( $sc_mobile_enable, 1 ); ?>><label></label>
                                    </div>
                                    <p class="description"><?php esc_html_e( 'Display Sidebar Cart on Mobile', 'woocommerce-cart-all-in-one' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-sc_icon_enable-checkbox"><?php esc_html_e( 'Enable sidebar cart icon', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui checkbox toggle">
                                        <input type="hidden" name="sc_icon_enable" id="vi-wcaio-sc_icon_enable" value="<?php echo esc_attr( $sc_icon_enable ); ?>">
                                        <input type="checkbox" id="vi-wcaio-sc_icon_enable-checkbox" class="vi-wcaio-sc_icon_enable-checkbox"
	                                        <?php checked( $sc_icon_enable, 1 ); ?>><label></label>
                                    </div>
                                    <p class="description">
	                                    <?php esc_html_e( 'Show Sidebar Cart icon on your site', 'woocommerce-cart-all-in-one' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr class="vi-wcaio-sc_icon_enable-enable <?php echo $sc_icon_enable ? '' : esc_attr( 'vi-wcaio-disabled' ); ?>">
                                <th>
                                    <label for="vi-wcaio-sc_empty_enable-checkbox"><?php esc_html_e( 'Visible empty sidebar cart icon', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui checkbox toggle">
                                        <input type="hidden" name="sc_empty_enable" id="vi-wcaio-sc_empty_enable" value="<?php echo esc_attr( $sc_empty_enable ); ?>">
                                        <input type="checkbox" id="vi-wcaio-sc_empty_enable-checkbox" class="vi-wcaio-sc_empty_enable-checkbox"
											<?php checked( $sc_empty_enable, 1 ); ?>><label></label>
                                    </div>
                                    <p class="description">
										<?php esc_html_e( 'Show Sidebar cart even when it is empty', 'woocommerce-cart-all-in-one' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-sc_assign_page">
										<?php esc_html_e( 'Assign page', 'woocommerce-cart-all-in-one' ); ?>
                                    </label>
                                </th>
                                <td>
                                    <input type="text" name="sc_assign_page" id="vi-wcaio-sc_assign_page" placeholder="<?php echo esc_attr( 'Ex: !is_page(array(123,41,20))' ); ?>"
                                           value="<?php echo esc_attr( $sc_assign_page ); ?>">
                                    <p class="description"><?php esc_html_e( 'Set pages to display the sidebar cart using', 'woocommerce-cart-all-in-one' ) ?>
                                        <a href="http://codex.wordpress.org/Conditional_Tags"><?php esc_html_e( 'WP\'s conditional tags.', 'woocommerce-cart-all-in-one' ) ?></a>
	                                    <?php esc_html_e( 'The sidebar cart will not work on cart page and checkout page', 'woocommerce-cart-all-in-one' ) ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-sc_content_class_open">
										<?php esc_html_e( 'Class/Id to open sidebar cart content', 'woocommerce-cart-all-in-one' ); ?>
                                    </label>
                                </th>
                                <td>
                                    <input type="text" name="sc_content_class_open" id="vi-wcaio-sc_content_class_open" placeholder="<?php echo esc_attr( 'Ex: .yourclass, #yourid' ); ?>"
                                           value="<?php echo esc_attr( $sc_content_class_open ); ?>">
                                    <p class="description"><?php esc_html_e( 'The sidebar cart content will open when clicking on above class/id.', 'woocommerce-cart-all-in-one' ) ?></p>
                                </td>
                            </tr>
                        </table>
                        <h4><?php esc_html_e( 'Checkout on Sidebar Cart', 'woocommerce-cart-all-in-one' ); ?></h4>
                        <table class="form-table">
                            <tr>
                                <th>
                                    <label for="vi-wcaio-sc_checkout_enable-checkbox"><?php esc_html_e( 'Enable', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui checkbox toggle">
                                        <input type="hidden" name="sc_checkout_enable" id="vi-wcaio-sc_checkout_enable" value="<?php echo esc_attr( $sc_checkout_enable ); ?>">
                                        <input type="checkbox" id="vi-wcaio-sc_checkout_enable-checkbox" class="vi-wcaio-sc_checkout_enable-checkbox"
	                                        <?php checked( $sc_checkout_enable, 1 ); ?>><label></label>
                                    </div>
                                    <p class="description">
	                                    <?php
	                                    esc_html_e( 'Allow checkout directly on Sidebar Cart without going to checkout page', 'woocommerce-cart-all-in-one' );
	                                    ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-sc_checkout_mobile_enable-checkbox"><?php esc_html_e( 'Mobile enable', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui checkbox toggle">
                                        <input type="hidden" name="sc_checkout_mobile_enable" id="vi-wcaio-sc_checkout_mobile_enable" value="<?php echo esc_attr( $sc_checkout_mobile_enable ); ?>">
                                        <input type="checkbox" id="vi-wcaio-sc_checkout_mobile_enable-checkbox" class="vi-wcaio-sc_checkout_mobile_enable-checkbox"
	                                        <?php checked( $sc_checkout_mobile_enable, 1 ); ?>><label></label>
                                    </div>
                                    <p class="description">
	                                    <?php
	                                    esc_html_e( 'Allow checkout directly on Sidebar Cart without going to checkout page on Mobile', 'woocommerce-cart-all-in-one' );
	                                    ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label><?php esc_html_e( 'Design', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
	                                <?php
	                                $url = admin_url( 'customize.php' ) . '?autofocus[panel]=vi_wcaio_design';
	                                ?>
                                    <a target="_blank" class="vi-wcaio-customize-url" href="<?php echo esc_attr( esc_url( $url ) ) ?>"><?php esc_html_e( 'Go to design', 'woocommerce-cart-all-in-one' ) ?></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="menu_cart">
						<?php
						$mc_enable        = $this->settings->get_params( 'mc_enable' );
						$mc_mobile_enable = $this->settings->get_params( 'mc_mobile_enable' );
						$mc_empty_enable  = $this->settings->get_params( 'mc_empty_enable' );
						$mc_menu_display  = $this->settings->get_params( 'mc_menu_display' );
						?>
                        <table class="form-table">
                            <tr>
                                <th>
                                    <label for="vi-wcaio-mc_enable-checkbox"><?php esc_html_e( 'Enable', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui checkbox toggle">
                                        <input type="hidden" name="mc_enable" id="vi-wcaio-mc_enable" value="<?php echo esc_attr( $mc_enable ); ?>">
                                        <input type="checkbox" id="vi-wcaio-mc_enable-checkbox" <?php checked( $mc_enable, 1 ); ?>><label></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-mc_mobile_enable-checkbox"><?php esc_html_e( 'Mobile enable', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui checkbox toggle">
                                        <input type="hidden" name="mc_mobile_enable" id="vi-wcaio-mc_mobile_enable" value="<?php echo esc_attr( $mc_mobile_enable ); ?>">
                                        <input type="checkbox" id="vi-wcaio-mc_mobile_enable-checkbox" <?php checked( $mc_mobile_enable, 1 ); ?>><label></label>
                                    </div>
                                    <p class="description"><?php esc_html_e( 'Display menu cart on Mobile mode', 'woocommerce-cart-all-in-one' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-mc_empty_enable-checkbox"><?php esc_html_e( 'Visible empty menu cart', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui checkbox toggle">
                                        <input type="hidden" name="mc_empty_enable" id="vi-wcaio-mc_empty_enable" value="<?php echo esc_attr( $mc_empty_enable ); ?>">
                                        <input type="checkbox" id="vi-wcaio-mc_empty_enable-checkbox" <?php checked( $mc_empty_enable, 1 ); ?>><label></label>
                                    </div>
                                    <p class="description">
										<?php esc_html_e( 'Show Menu Cart cart even when it is empty', 'woocommerce-cart-all-in-one' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-mc_display_style"><?php esc_html_e( 'Menu Cart Text', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
	                                <?php $mc_display_style = $this->settings->get_params( 'mc_display_style' ); ?>
                                    <select class="vi-ui fluid dropdown viwcaio-mc_display_style" name="mc_display_style" id="vi-wcaio-mc_display_style">
                                        <option value="product_counter" <?php selected( 'product_counter', $mc_display_style ) ?>>
	                                        <?php esc_html_e( 'Product Counter', 'woocommerce-cart-all-in-one' ); ?>
                                        </option>
                                        <option value="price" <?php selected( 'price', $mc_display_style ) ?>>
	                                        <?php esc_html_e( 'Price', 'woocommerce-cart-all-in-one' ); ?>
                                        </option>
                                        <option value="all" <?php selected( 'all', $mc_display_style ) ?>>
	                                        <?php esc_html_e( 'Product Counter & Price', 'woocommerce-cart-all-in-one' ); ?>
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-mc_cart_total"><?php esc_html_e( 'Menu Cart Price', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
	                                <?php $mc_cart_total = $this->settings->get_params( 'mc_cart_total' ); ?>
                                    <select class="vi-ui fluid dropdown viwcaio-mc_cart_total" name="mc_cart_total" id="vi-wcaio-mc_cart_total">
                                        <option value="total" <?php selected( 'total', $mc_cart_total ) ?>>
	                                        <?php esc_html_e( 'Total', 'woocommerce-cart-all-in-one' ); ?>
                                        </option>
                                        <option value="subtotal" <?php selected( 'subtotal', $mc_cart_total ) ?>>
	                                        <?php esc_html_e( 'Subtotal', 'woocommerce-cart-all-in-one' ); ?>
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-mc_menu_display"><?php esc_html_e( 'Menus', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <select name="mc_menu_display[]" id="vi-wcaio-mc_menu_display" class="vi-ui fluid dropdown vi-wcaio-mc_menu_display" multiple>
										<?php
										$menus = wp_get_nav_menus();
										foreach ( $menus as $menu ) {
											$selected = in_array( $menu->term_id, $mc_menu_display ) ? 'selected="selected"' : '';
											echo sprintf( '<option value="%s" %s>%s</option>', $menu->term_id, $selected, $menu->name );
										}
										?>
                                    </select>
                                    <p class="description"><?php esc_html_e( 'Select menus to display the menu Cart.  Clicking on save button before "Go to Design"', 'woocommerce-cart-all-in-one' ) ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label><?php esc_html_e( 'Design', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
									<?php
									$url = admin_url( 'customize.php' ) . '?autofocus[section]=vi_wcaio_design_menu_cart';
									?>
                                    <a target="_blank" class="vi-wcaio-customize-url" href="<?php echo esc_attr( esc_url( $url ) ) ?>"><?php esc_html_e( 'Go to design', 'woocommerce-cart-all-in-one' ) ?></a>
                                    <p class="description">
										<?php esc_html_e( 'Go to design Menu Cart', 'woocommerce-cart-all-in-one' ); ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="atc_button">
						<?php
						$ajax_atc                       = $this->settings->get_params( 'ajax_atc' );
						$ajax_atc_pd_exclude            = $this->settings->get_params( 'ajax_atc_pd_exclude' );
						$ajax_atc_notice                = $this->settings->get_params( 'ajax_atc_notice' );
						$ajax_atc_pd_variable           = $this->settings->get_params( 'ajax_atc_pd_variable' );
						$pd_variable_bt_atc_text_enable = $this->settings->get_params( 'pd_variable_bt_atc_text_enable' );
						$pd_variable_bt_atc_text        = $this->settings->get_params( 'pd_variable_bt_atc_text' );
						$sb_enable                      = $this->settings->get_params( 'sb_enable' );
						$sb_mobile_enable               = $this->settings->get_params( 'sb_mobile_enable' );
						$sb_use_viwpvs                  = $this->settings->get_params( 'sb_use_viwpvs' );
						$sb_pd_exclude                  = $this->settings->get_params( 'sb_pd_exclude' );
						$sb_cats_exclude                = $this->settings->get_params( 'sb_cats_exclude' );
						?>
                        <h4><?php esc_html_e( 'AJAX Add to Cart', 'woocommerce-cart-all-in-one' ); ?></h4>
                        <table class="form-table">
                            <tr>
                                <th>
                                    <label for="vi-wcaio-ajax_atc-checkbox"><?php esc_html_e( 'Enable', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui checkbox toggle">
                                        <input type="hidden" name="ajax_atc" id="vi-wcaio-ajax_atc" value="<?php echo esc_attr( $ajax_atc ); ?>">
                                        <input type="checkbox" id="vi-wcaio-ajax_atc-checkbox" class="vi-wcaio-ajax_atc-checkbox"
											<?php checked( $ajax_atc, 1 ); ?>><label></label>
                                    </div>
                                    <p class="description"><?php esc_html_e( 'Add product to cart without reloading on single product pages and Quick View popup.', 'woocommerce-cart-all-in-one' ); ?></p>
                                </td>
                            </tr>
                            <tr class="vi-wcaio-ajax_atc-enable <?php echo $ajax_atc ? '' : esc_attr( 'vi-wcaio-disabled' ); ?>">
                                <th>
                                    <label for="vi-wcaio-ajax_atc_pd_exclude"><?php esc_html_e( 'Exclude products', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <select name="ajax_atc_pd_exclude[]" id="vi-wcaio-ajax_atc_pd_exclude" data-type_select2="product"
                                            class="vi-wcaio-search-select2 vi-wcaio-search-product vi-wcaio-ajax_atc_pd_exclude" multiple>
										<?php
										if ( $ajax_atc_pd_exclude && is_array( $ajax_atc_pd_exclude ) && count( $ajax_atc_pd_exclude ) ) {
											foreach ( $ajax_atc_pd_exclude as $product_id ) {
												$product = wc_get_product( $product_id );
												if ( $product ) {
													echo sprintf( '<option value="%s" selected>%s</option>', $product_id, $product->get_name() );
												}
											}
										}
										?>
                                    </select>
                                    <p class="description">
										<?php esc_html_e( 'Add the products which are not applied ajax add to cart', 'woocommerce-cart-all-in-one' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-ajax_atc_notice-checkbox"><?php esc_html_e( 'Notification', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui checkbox toggle">
                                        <input type="hidden" name="ajax_atc_notice" id="vi-wcaio-ajax_atc_notice" value="<?php echo esc_attr( $ajax_atc_notice ); ?>">
                                        <input type="checkbox" id="vi-wcaio-ajax_atc_notice-checkbox" class="vi-wcaio-ajax_atc_notice-checkbox"
	                                        <?php checked( $ajax_atc_notice, 1 ); ?>><label></label>
                                    </div>
                                    <p class="description">
	                                    <?php esc_html_e( 'Display the notification of adding products to cart successfully after adding to cart by Ajax and reloading page', 'woocommerce-cart-all-in-one' ); ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <h4><?php esc_html_e( 'Add to Cart for variable products', 'woocommerce-cart-all-in-one' ); ?></h4>
                        <table class="form-table">
                            <tr>
                                <th>
                                    <label for="vi-wcaio-ajax_atc_pd_variable-checkbox"><?php esc_html_e( 'Select variation pop-up', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui checkbox toggle">
                                        <input type="hidden" name="ajax_atc_pd_variable" id="vi-wcaio-ajax_atc_pd_variable" value="<?php echo esc_attr( $ajax_atc_pd_variable ); ?>">
                                        <input type="checkbox" id="vi-wcaio-ajax_atc_pd_variable-checkbox" class="vi-wcaio-ajax_atc_pd_variable-checkbox"
											<?php checked( $ajax_atc_pd_variable, 1 ); ?>><label></label>
                                    </div>
                                    <p class="description">
										<?php esc_html_e( 'After click add to cart button, a pop-up will appear allowing select variations and add to cart without redirect to the single product page.', 'woocommerce-cart-all-in-one' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-pd_variable_bt_atc_text_enable-checkbox"><?php esc_html_e( 'Add to Cart button label', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui checkbox toggle">
                                        <input type="hidden" name="pd_variable_bt_atc_text_enable" id="vi-wcaio-pd_variable_bt_atc_text_enable"
                                               value="<?php echo esc_attr( $pd_variable_bt_atc_text_enable ); ?>">
                                        <input type="checkbox" id="vi-wcaio-pd_variable_bt_atc_text_enable-checkbox" class="vi-wcaio-pd_variable_bt_atc_text_enable-checkbox"
											<?php checked( $pd_variable_bt_atc_text_enable, 1 ); ?>><label></label>
                                    </div>
                                    <p class="description">
										<?php esc_html_e( 'Change the label of the add to cart button with variable products', 'woocommerce-cart-all-in-one' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr class="vi-wcaio-pd_variable_bt_atc_text_enable-enable <?php echo $pd_variable_bt_atc_text_enable ? '' : esc_attr( 'vi-wcaio-disabled' ); ?>">
                                <th>
                                    <label for="vi-wcaio-pd_variable_bt_atc_text">
										<?php esc_html_e( 'Add to Cart button label', 'woocommerce-cart-all-in-one' ); ?>
                                    </label>
                                </th>
                                <td>
									<?php $admin::get_language_flag_html( $this->default_language, $this->languages_data ); ?>
                                    <input type="text" name="pd_variable_bt_atc_text" id="vi-wcaio-pd_variable_bt_atc_text" class="vi-wcaio-pd_variable_bt_atc_text"
                                           placeholder="<?php esc_attr_e( 'Add To Cart', 'woocommerce-cart-all-in-one' ); ?>"
                                           value="<?php echo esc_attr( $pd_variable_bt_atc_text ); ?>">
                                    <p class="description"><?php esc_html_e( 'Enter you own label for the add to cart button of variable products', 'woocommerce-cart-all-in-one' ); ?></p>
									<?php
									if ( count( $this->languages ) ) {
										foreach ( $this->languages as $key => $value ) {
											$admin::get_language_flag_html( $value, $this->languages_data );
											echo sprintf(
												'<input type="text" name="pd_variable_bt_atc_text_%s" class="vi-wcaio-pd_variable_bt_atc_text" placeholder="%s" value="%s">',
												$value, esc_html__( 'Add To Cart', 'woocommerce-cart-all-in-one' ),
												$this->settings->get_params( 'pd_variable_bt_atc_text_' . $value ) ?: $pd_variable_bt_atc_text );
										}
									}
									?>
                                </td>
                            </tr>
                        </table>
                        <h4><?php esc_html_e( 'Sticky Add To Cart on single product page', 'woocommerce-cart-all-in-one' ); ?></h4>
                        <table class="form-table">
                            <tr>
                                <th>
                                    <label for="vi-wcaio-sb_enable-checkbox"><?php esc_html_e( 'Enable', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="hidden" id="vi-wcaio-sb_enable" name="sb_enable" value="<?php echo esc_attr( $sb_enable ); ?>">
                                        <input type="checkbox" id="vi-wcaio-sb_enable-checkbox" class="vi-wcaio-sb_enable-checkbox" <?php checked( $sb_enable, '1' ) ?>><label></label>
                                    </div>
                                    <p class="description">
										<?php esc_html_e( 'Display sticky add to cart on single product pages', 'woocommerce-cart-all-in-one' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr class="vi-wcaio-sb_enable-enable <?php echo $sb_enable ? '' : esc_attr( 'vi-wcaio-disabled' ); ?>">
                                <th>
                                    <label for="vi-wcaio-sb_mobile_enable-checkbox"><?php esc_html_e( 'Mobile enable', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="hidden" id="vi-wcaio-sb_mobile_enable" name="sb_mobile_enable" value="<?php echo esc_attr( $sb_mobile_enable ); ?>">
                                        <input type="checkbox" id="vi-wcaio-sb_mobile_enable-checkbox" <?php checked( $sb_mobile_enable, '1' ) ?>><label></label>
                                    </div>
                                    <p class="description"><?php esc_html_e( 'Display sticky add to cart on mobile', 'woocommerce-cart-all-in-one' ); ?></p>
                                </td>
                            </tr>
	                        <?php
	                        if ( class_exists( 'VIWPVS_WOOCOMMERCE_PRODUCT_VARIATIONS_SWATCHES' ) ) {
		                        ?>
                                <tr>
                                    <th>
                                        <label for="vi-wcaio-sb_use_viwpvs-checkbox"><?php esc_html_e( 'Use variation swatches', 'woocommerce-cart-all-in-one' ); ?></label>
                                    </th>
                                    <td>
                                        <div class="vi-ui checkbox toggle">
                                            <input type="hidden" name="sb_use_viwpvs" id="vi-wcaio-sb_use_viwpvs" value="<?php echo esc_attr( $sb_use_viwpvs ); ?>">
                                            <input type="checkbox" id="vi-wcaio-sb_use_viwpvs-checkbox" class="vi-wcaio-sb_use_viwpvs-checkbox"
	                                            <?php checked( $sb_use_viwpvs, 1 ); ?>><label></label>
                                        </div>
                                    </td>
                                </tr>
		                        <?php
	                        }
	                        ?>
                            <tr class="vi-wcaio-sb_enable-enable <?php echo $sb_enable ? '' : esc_attr( 'vi-wcaio-disabled' ); ?>">
                                <th>
                                    <label for="vi-wcaio-sb_pd_exclude"><?php esc_html_e( 'Exclude products', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <select name="sb_pd_exclude[]" id="vi-wsatc-sb_pd_exclude" class="vi-wcaio-search-select2 vi-wcaio-search-product vi-wcaio-sb_pd_exclude"
                                            data-type_select2="product" multiple>
										<?php
										if ( $sb_pd_exclude && is_array( $sb_pd_exclude ) && count( $sb_pd_exclude ) ) {
											foreach ( $sb_pd_exclude as $pd_id ) {
												$product = wc_get_product( $pd_id );
												if ( $product ) {
													echo sprintf( '<option value="%s" selected>%s</option>', $pd_id, $product->get_formatted_name() );
												}
											}
										}
										?>
                                    </select>
                                    <p class="description">
										<?php esc_html_e( 'Add the products which are not displayed Sticky Add to Cart', 'woocommerce-cart-all-in-one' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr class="vi-wcaio-sb_enable-enable <?php echo $sb_enable ? '' : esc_attr( 'vi-wcaio-disabled' ); ?>">
                                <th>
                                    <label for="vi-wcaio-sb_cats_exclude"><?php esc_html_e( 'Exclude Categories', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <select name="sb_cats_exclude[]" id="vi-wcaio-sb_cats_exclude" class="vi-wcaio-search-select2 vi-wcaio-search-category vi-wcaio-sb_cats_exclude"
                                            data-type_select2="category" multiple>
										<?php
										if ( $sb_cats_exclude && is_array( $sb_cats_exclude ) && count( $sb_cats_exclude ) ) {
											foreach ( $sb_cats_exclude as $cats_id ) {
												$term = get_term( $cats_id );
												if ( $term ) {
													echo sprintf( '<option value="%s" selected>%s</option>', $cats_id, $term->name );
												}
											}
										}
										?>
                                    </select>
                                    <p class="description">
										<?php esc_html_e( 'Add the Categories which are not displayed Sticky Add to Cart', 'woocommerce-cart-all-in-one' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label><?php esc_html_e( 'Design', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
									<?php
									$url = admin_url( 'customize.php' ) . '?autofocus[section]=vi_wcaio_design_sticky_atc';
									?>
                                    <a target="_blank" class="vi-wcaio-customize-url" href="<?php echo esc_attr( esc_url( $url ) ) ?>"><?php esc_html_e( 'Go to design', 'woocommerce-cart-all-in-one' ) ?></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="recently_viewed">
						<?php
						$vp_enable          = $this->settings->get_params( 'vp_enable' );
						$vp_mobile_enable   = $this->settings->get_params( 'vp_mobile_enable' );
						$vp_single_position = $this->settings->get_params( 'vp_single_position' );
						$vp_title           = $this->settings->get_params( 'vp_title' );
						$vp_pd_column       = $this->settings->get_params( 'vp_pd_column' );
						$vp_pd_limit        = $this->settings->get_params( 'vp_pd_limit' );
						$vp_slider_loop     = $this->settings->get_params( 'vp_slider_loop' );
						$vp_slider_move     = $this->settings->get_params( 'vp_slider_move' );
						$vp_slider_auto     = $this->settings->get_params( 'vp_slider_auto' );
						$vp_slider_speed    = $this->settings->get_params( 'vp_slider_speed' );
						$vp_slider_pause    = $this->settings->get_params( 'vp_slider_pause' );
						?>
                        <div class="vi-ui message">
                            <div class="vi-wcaio-vp-shortcode-wrap">
                                <span><?php esc_html_e( 'Shortcode:', 'woocommerce-cart-all-in-one' ); ?></span>
                                <div class="vi-wcaio-vp-shortcode">
									<?php
									$shortcode = '[vi_wcaio_viewed_product class="" title="' . $vp_title . '"  display="' . $vp_pd_limit . '"  columns="' . $vp_pd_column . '" loop="' . $vp_slider_loop . '" move="' . $vp_slider_move . '" auto_play="' . $vp_slider_auto . '" speed="' . $vp_slider_speed . '" pause="' . $vp_slider_pause . '" ]';
									echo wp_kses_post( $shortcode );
									?>
                                </div>
                                <i class="clone icon vi-wcaio-vp-shortcode-bt-copy"></i>
                            </div>
                            <p class="description vi-wcaio-vp-shortcode-copied vi-wcaio-disabled">
								<?php esc_html_e( 'Shortcode copied to clipboard!', 'woocommerce-cart-all-in-one' ); ?>
                            </p>
                        </div>
                        <table class="form-table">
                            <tr>
                                <th>
                                    <label for="vi-wcaio-vp_enable-checkbox"><?php esc_html_e( 'Enable', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="hidden" id="vi-wcaio-vp_enable" name="vp_enable" value="<?php echo esc_attr( $vp_enable ); ?>">
                                        <input type="checkbox" id="vi-wcaio-vp_enable-checkbox" <?php checked( $vp_enable, '1' ) ?>><label></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-vp_mobile_enable-checkbox"><?php esc_html_e( 'Mobile enable', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="hidden" id="vi-wcaio-vp_mobile_enable" name="vp_mobile_enable" value="<?php echo esc_attr( $vp_mobile_enable ); ?>">
                                        <input type="checkbox" id="vi-wcaio-vp_mobile_enable-checkbox" <?php checked( $vp_mobile_enable, '1' ) ?>><label></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-vp_single_position"><?php esc_html_e( 'Position on single product page', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <select name="vp_single_position" id="vi-wcaio-vp_single_position" class="vi-ui fluid dropdown vi-wcaio-vp_single_position">
                                        <option value="0" <?php selected( $vp_single_position, 0 ) ?>>
											<?php esc_html_e( 'None', 'woocommerce-cart-all-in-one' ); ?>
                                        </option>
                                        <option value="1" <?php selected( $vp_single_position, 1 ) ?>>
											<?php esc_html_e( 'Before single product summary', 'woocommerce-cart-all-in-one' ); ?>
                                        </option>
                                        <option value="2" <?php selected( $vp_single_position, 2 ) ?>>
											<?php esc_html_e( 'After single product summary', 'woocommerce-cart-all-in-one' ); ?>
                                        </option>
                                        <option value="3" <?php selected( $vp_single_position, 3 ) ?>>
											<?php esc_html_e( 'After single product content', 'woocommerce-cart-all-in-one' ); ?>
                                        </option>
                                    </select>
                                    <p class="description">
										<?php esc_html_e( 'Choose the position for recently viewed products on single product pages', 'woocommerce-cart-all-in-one' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-vp_title"><?php esc_html_e( 'Title', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
									<?php $admin::get_language_flag_html( $this->default_language, $this->languages_data ); ?>
                                    <input type="text" name="vp_title" id="vi-wcaio-vp_title"
                                           placeholder="<?php esc_attr_e( 'Your recently viewed items', 'woocommerce-cart-all-in-one' ); ?>" value="<?php echo esc_attr( $vp_title ); ?>">
									<?php
									if ( count( $this->languages ) ) {
										foreach ( $this->languages as $key => $value ) {
											$admin::get_language_flag_html( $value, $this->languages_data );
											echo sprintf(
												'<input type="text" name="vp_title_%s" class="vi-wcaio-vp_title" placeholder="%s" value="%s">',
												$value, esc_html__( 'Your recently viewed items', 'woocommerce-cart-all-in-one' ),
												$this->settings->get_params( 'vp_title_' . $value ) ?: $vp_title );
										}
									}
									?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-vp_pd_limit"><?php esc_html_e( 'Products limit', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <input type="number" min="1" step="1" max="15" name="vp_pd_limit" id="vi-wcaio-vp_pd_limit" value="<?php echo esc_attr( $vp_pd_limit ); ?>">
                                    <p class="description">
										<?php esc_html_e( 'The maximum number of recently viewed products displayed is 15', 'woocommerce-cart-all-in-one' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-vp_pd_column"><?php esc_html_e( 'Number of columns', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <input type="number" min="1" step="1" name="vp_pd_column" id="vi-wcaio-vp_pd_column" value="<?php echo esc_attr( $vp_pd_column ); ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-vp_slider_loop-checkbox"><?php esc_html_e( 'Infinite loop', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="hidden" id="vi-wcaio-vp_slider_loop" name="vp_slider_loop" value="<?php echo esc_attr( $vp_slider_loop ); ?>">
                                        <input type="checkbox" id="vi-wcaio-vp_slider_loop-checkbox" class="vi-wcaio-vp_slider_loop-checkbox" <?php checked( $vp_slider_loop, '1' ) ?>><label></label>
                                    </div>
                                    <p class="description"><?php esc_html_e( 'Start over when reaching the end of slide', 'woocommerce-cart-all-in-one' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-vp_slider_move"><?php esc_html_e( 'Number of carousel items that should move on animation', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <input type="number" min="1" step="1" name="vp_slider_move" id="vi-wcaio-vp_slider_move" value="<?php echo esc_attr( $vp_slider_move ); ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-vp_slider_auto-checkbox"><?php esc_html_e( 'Autoplay', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="hidden" id="vi-wcaio-vp_slider_auto" name="vp_slider_auto" value="<?php echo esc_attr( $vp_slider_auto ); ?>">
                                        <input type="checkbox" id="vi-wcaio-vp_slider_auto-checkbox" class="vi-wcaio-vp_slider_auto-checkbox" <?php checked( $vp_slider_auto, '1' ) ?>><label></label>
                                    </div>
                                    <p class="description"><?php esc_html_e( 'Auto play slideshow with settings below', 'woocommerce-cart-all-in-one' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-vp_slider_speed"><?php esc_html_e( 'Slideshow speed(milliseconds)', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <input type="number" min="0" step="1" name="vp_slider_speed" id="vi-wcaio-vp_slider_speed" value="<?php echo esc_attr( $vp_slider_speed ); ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcaio-vp_slider_pause-checkbox"><?php esc_html_e( 'Pause on hover', 'woocommerce-cart-all-in-one' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="hidden" id="vi-wcaio-vp_slider_pause" name="vp_slider_pause" value="<?php echo esc_attr( $vp_slider_pause ); ?>">
                                        <input type="checkbox" id="vi-wcaio-vp_slider_pause-checkbox" class="vi-wcaio-vp_slider_pause-checkbox" <?php checked( $vp_slider_pause, '1' ) ?>><label></label>
                                    </div>
                                    <p class="description"><?php esc_html_e( 'Pause the slideshow when hovering and resume when no longer hovering', 'woocommerce-cart-all-in-one' ); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="update">
                        <table class="form-table">
                            <tr>
                                <th>
                                    <label for="auto-update-key"><?php esc_html_e( 'Auto Update Key', 'woocommerce-cart-all-in-one' ) ?></label>
                                </th>
                                <td>
                                    <div class="fields">
                                        <div class="ten wide field">
                                            <input type="text" name="purchased_code" id="auto-update-key"
                                                   class="villatheme-autoupdate-key-field"
                                                   value="<?php echo esc_attr( htmlentities( $this->settings->get_params( 'purchased_code' ) ) ); ?>">
                                        </div>
                                        <div class="six wide field">
                                        <span class="vi-ui button green small villatheme-get-key-button"
                                              data-href="https://api.envato.com/authorization?response_type=code&client_id=villatheme-download-keys-6wzzaeue&redirect_uri=https://villatheme.com/update-key"
                                              data-id="30184317"><?php echo esc_html__( 'Get Key', 'woocommerce-cart-all-in-one' ) ?></span>
                                        </div>
                                    </div>
									<?php do_action( 'woocommerce-cart-all-in-one_key' ) ?>
                                    <p class="description"><?php echo wp_kses_post( __( 'Please fill your key what you get from <a target="_blank" href="https://villatheme.com/my-download">https://villatheme.com/my-download</a>. You can auto update WooCommerce Cart All in One plugin. See <a target="_blank" href="https://villatheme.com/knowledge-base/how-to-use-auto-update-feature/">guide</a>', 'woocommerce-cart-all-in-one' ) ); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <p class="vi-wcuf-save-wrap">
                        <button type="submit" class="vi-wcuf-save vi-ui primary button" name="vi-wcaio-save">
							<?php esc_html_e( 'Save', 'woocommerce-cart-all-in-one' ); ?>
                        </button>
                        <button type="submit" class="vi-ui button labeled icon vi-wcaio-save"
                                name="vi-wcaio-check_key">
                            <i class="send icon"></i> <?php esc_html_e( 'Save & Check Key', 'woocommerce-cart-all-in-one' ) ?>
                        </button>
                    </p>
                </form>
				<?php do_action( 'villatheme_support_woocommerce-cart-all-in-one' ); ?>
            </div>
        </div>
		<?php
	}

	public function admin_enqueue_scripts() {
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
		if ( $page === 'woocommerce-cart-all-in-one' ) {
			$admin = 'VIWCAIO_CART_ALL_IN_ONE_Admin_Settings';
			$admin::remove_other_script();
			$admin::enqueue_style(
				array( 'semantic-ui-button', 'semantic-ui-checkbox', 'semantic-ui-dropdown', 'semantic-ui-form', 'semantic-ui-icon', 'semantic-ui-menu', 'semantic-ui-segment', 'semantic-ui-tab' ),
				array( 'button.min.css', 'checkbox.min.css', 'dropdown.min.css', 'form.min.css', 'icon.min.css', 'menu.min.css', 'segment.min.css', 'tab.min.css' )
			);
			$admin::enqueue_style(
				array( 'vi-wcaio-admin-settings', 'select2', 'transition', 'semantic-ui-message' ),
				array( WP_DEBUG ? 'admin-settings.css' : 'admin-settings.min.css', 'select2.min.css', 'transition.min.css', 'message.min.css' )
			);
			$admin::enqueue_script(
				array( 'semantic-ui-address', 'semantic-ui-checkbox', 'semantic-ui-dropdown', 'semantic-ui-form', 'semantic-ui-tab' ),
				array( 'address.min.js', 'checkbox.min.js', 'dropdown.min.js', 'form.min.js', 'tab.js' )
			);
			$admin::enqueue_script(
				array( 'vi-wcaio-admin-settings', 'select2', 'transition' ),
				array( 'admin-settings.js', 'select2.js', 'transition.min.js' )
			);
		}
	}
}