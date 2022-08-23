<?php

/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.9.0
 */

if (!defined('ABSPATH')) {
	exit;
}








if ($related_products) : ?> <div class="similar__wrapper">
    <div class="similar__header grid_layout"
         data-watch
         data-watch-once>
        <h2 class="similar__title title title-animation">Похожие товары</h2>
        <div class="similar__button">
            <a href="<?php echo home_url($wp->request) . '/shop' ?>"
               class="button-border"> СМОТРЕТЬ ВСЕ <span>ТОВАРЫ</span>
                <span class="button-border__arrow">
                    <svg width="18"
                         height="8"
                         viewBox="0 0 18 8"
                         fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.8 0.599976L16.1 3.99998L12.8 7.29998"
                              stroke="#3A3A1F" />
                        <path d="M0 4H16.1"
                              stroke="#3A3A1F" />
                    </svg>
                </span>
            </a>
        </div>
        <div class="similar__line line-horizontal similar__line_01"></div>
    </div>
    <div class="similar__list projects__list grid_layout grid_list"
         data-watch
         data-watch-once> <?php woocommerce_product_loop_start(); ?>
        <?php foreach ($related_products as $related_product) : ?> <?php
																		$post_object = get_post($related_product->get_id());

																		setup_postdata($GLOBALS['post'] = &$post_object); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
																		global $product;
																		$product_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full')[0];

																		?> <a href="<?php echo get_permalink() ?>"
           class="projects__link link-grid-item grid__item">
            <div class="grid__item-image link-grid-item__image">
                <img src="<?php echo $product_image ?>"
                     alt="01">
            </div>
            <div class="grid__item-content link-grid-item__content">
                <h3 class="grid__item-title link-grid-item__title"><?php the_title(); ?></h3>
                <div class="grid__item-desc"><?php echo $product->get_short_description()  ?></div>
                <!-- <div class="grid__item-meta link-grid-item__meta_2">
                   
                    <div class="grid__item-meta-col">
                        <span class="link-grid-item__caption_01 caption_01">категория товара</span>
                        <p class="text_01">Тротуарная плитка</p>
                    </div>
                    <div class="grid__item-meta-col">
                        <span class="link-grid-item__caption_01 caption_01">Форма</span>
                        <p class="text_01">S-форма</p>
                    </div>
                    <div class="grid__item-meta-col">
                        <span class="link-grid-item__caption_01 caption_01">Локация</span>
                        <p class="text_01">Тюмень</p>
                    </div>
                </div> -->
            </div>
            <div class="link-grid-item__line line-vertical"></div>
            <div class="link-grid-item__line_horizontal line-horizontal"></div>
        </a> <?php endforeach; ?> <?php woocommerce_product_loop_end(); ?> </div>
</div>
</section> <?php
			endif;

			wp_reset_postdata();