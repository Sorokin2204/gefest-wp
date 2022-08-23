<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');
global $wp;

function getCategories()
{
    $list = [];
    $all_cats = get_terms(
        array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => false
        )
    );
    foreach ($all_cats as $category) {
        if ($category->slug !== 'uncategorized') {
            array_push($list, $category);
        }
    }
    return $list;
}


$category_list = getCategories();


?> <main class="page">
    <section class="products products-page"
             data-sticky
             data-watch
             data-watch-once>
        <div class="products__wrapper grid_layout gutter">
            <div class="products__line line-01 line-horizontal">
            </div>
            <div class="products__filters"
                 data-sticky-item>
                <h2 class="products__title title"> товары </h2>
                <form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php"
                      method="POST"
                      id="filter">
                    <input type="hidden"
                           name="action"
                           value="myfilter">
                    <input type="hidden"
                           style="display:none;"
                           value="true"
                           name="changeCategory">
                    <div class="products__filters-filter">
                        <div data-spollers
                             data-one-spoller
                             class="products__spollers spollers">
                            <div class="spollers__item ">
                                <button type="button"
                                        data-spoller
                                        class="spollers__title">категория товара</button>
                                <div class="spollers__body"> <?php foreach ($category_list as $category) { ?> <input
                                           type="radio"
                                           class='category-radio'
                                           style="display:none"
                                           value='<?php echo $category->term_id ?>'
                                           name='category'
                                           id='<?php echo $category->slug ?>'>
                                    <label for='<?php echo $category->slug ?>'
                                           class="spollers__button-option"> <?php echo $category->name ?> </label>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="spollers__item">
                                <button type="button"
                                        data-spoller
                                        class="spollers__title "
                                        id='filter_forma_options_btn'>Форма</button>
                                <div class="spollers__body ">
                                    <div class="body-forms"
                                         id='filter_forma_options'> </div>
                                </div>
                            </div>
                            <div class="spollers__item">
                                <button type="button"
                                        data-spoller
                                        class="spollers__title"
                                        id="filter_thickness_options_btn">толщина</button>
                                <div class=" spollers__body"
                                     id="filter_thickness_options"> </div>
                            </div>
                            <div class="spollers__item">
                                <button type="button"
                                        data-spoller
                                        class="spollers__title"
                                        id="filter_paint_options_btn">окрас</button>
                                <div class=" spollers__body"
                                     id="filter_paint_options"> </div>
                            </div>
                            <button type='button'
                                    class="filter-reset">очистить фильтр</but>
                        </div>
                    </div>
                    <div id="response"></div>
                </form>
            </div>
            <div class="products__list">
                <div class="products__list-wrapper"
                     id="products">
                </div>
                <div class="products-not-found">Товаров не найдено </div>
                <div class="lds-spinner">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <div class="products__line line-02 line-vertical">
                </div>
            </div>
        </div>
    </section>
    <!-- /.products -->
</main> <?php
        get_footer('shop');