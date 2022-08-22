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
function getAttributesBySlug($tax_name, $cat_term_id = null, $acf_other = [])
{
	$list = [];
	$all_list = 	 get_terms(array(
		'taxonomy' => $tax_name, 'hide_empty' => false,
	));
	if (!is_null($cat_term_id)) {
		foreach ($all_list as $item) {
			$from_category_id = get_field('cat_attr', $tax_name . '_' . $item->term_id);

			if ($from_category_id == $cat_term_id) {
				$item_find = $item;
				foreach ($acf_other as $acf_name) {
					$item_find->{$acf_name} =
						get_field($acf_name, $tax_name . '_' . $item->term_id);
				}


				array_push($list, $item_find);
			}
		}
		return $list;
	} else {
		return $all_list;
	}
}

function findTermBySlug($slug, $list = [])
{
	if (isset($slug) && !empty($slug)) {
		foreach ($list as $item) {
			if ($item->slug === $slug) {
				return $item;
			}
		}
	}
	return;
}
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
$params_category = $_GET['cat'];
$params_form = $_GET['form'];
$params_thickness = $_GET['thickness'];
$params_collection = $_GET['collection'];
$category_list = [];
$form_list = [];
$thickness_list = [];
$collection_list = [];
$category_active;
$form_active;
$thickness_active;
$collection_active;
$query_forma = [];
$query_thickness = [];
$query_collection = [];

$category_list = getCategories();
$category_active = findTermBySlug($params_category, $category_list);

if (isset($category_active) && !empty($category_active)) {
	$form_list = getAttributesBySlug('pa_forma', $category_active->term_id, ['cat_photo_filter']);
	$form_active = findTermBySlug($params_form, $form_list);

	$thickness_list = getAttributesBySlug('pa_tolshhina-mm', null);
	$thickness_active = findTermBySlug($params_thickness, $thickness_list);

	$collection_list = getAttributesBySlug('pa_kollekcija', $category_active->term_id, ['cat_photo_filter']);
	$collection_active = findTermBySlug($params_collection, $collection_list);
}


if (isset($form_active) && !empty($form_active)) {
	$query_forma = [
		'key' => 'custom_forma',
		'value' =>  $form_active->term_id
	];
}

if (isset($collection_active) && !empty($collection_active)) {
	$query_collection = [
		'key' => 'custom_collections',
		'value' =>  $collection_active->term_id,
		'compare' => 'LIKE',
	];
}
if (isset($thickness_active) && !empty($thickness_active)) {
	$query_thickness = [
		'key' => 'custom_thickness',
		'value' =>  $thickness_active->term_id
	];
}

$query = array(
	'post_status' => 'publish',
	'post_type' => array('product'),
	'meta_query' => [
		'relation' => 'AND',
		$query_forma,
		$query_collection,
		$query_thickness
	]
);

if (isset($category_active) && !empty($category_active)) {
	$query['product_cat'] = $category_active->slug;
}

wp_reset_query();
$wc_query = new WP_Query($query);





// var_dump(the_field('cat_attr', $form_list[0]));
// var_dump($form_active);

// var_dump($category_list)
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
                <div class="products__filters-filter">
                    <div data-spollers
                         data-one-spoller
                         class="products__spollers spollers">
                        <div class="spollers__item ">
                            <button type="button"
                                    data-spoller
                                    class="spollers__title">категория товара</button>
                            <div class="spollers__body"> <?php foreach ($category_list as $category) { ?> <a href="<?php echo home_url($wp->request) . '?cat=' . $category->slug
																													?>"
                                   class="spollers__button-option <?php if ($category->slug === $params_category) echo 'spollers__button-option--active'; ?>"><?php echo $category->name ?></a>
                                <?php } ?> </div>
                        </div>
                        <div class="spollers__item">
                            <button type="button"
                                    data-spoller
                                    class="spollers__title <?php if (empty($form_list)) echo 'spollers__title--disable' ?>">Форма</button>
                            <div class="spollers__body ">
                                <div class="body-forms"> <?php foreach ($form_list as $form) { ?> <a href="<?php echo home_url($wp->request) . '?cat=' . $category_active->slug . '&form=' .  $form->slug . ($collection_active ? '&collection=' . $collection_active->slug : '') . ($thickness_active ? '&thickness=' . $thickness_active->slug : '')
																											?>"
                                       class="spollers__button-option button-option__forms <?php if ($form->slug === $params_form) echo 'button-option__forms--active '; ?>">
                                        <div class="button-option__forms-image">
                                            <img src="<?php echo $form->cat_photo_filter ?>"
                                                 alt="">
                                        </div> <?php echo $form->name ?>
                                    </a> <?php } ?> </div>
                            </div>
                        </div>
                        <div class="spollers__item">
                            <button type="button"
                                    data-spoller
                                    class="spollers__title <?php if (empty($collection_list)) echo 'spollers__title--disable' ?>">Коллекция</button>
                            <div class="spollers__body ">
                                <div class="body-forms"> <?php foreach ($collection_list as $collection) { ?> <a href="<?php echo home_url($wp->request) . '?cat=' . $category_active->slug . ($form_active ? '&form=' . $form_active->slug : '') . '&collection=' . $collection->slug . ($thickness_active ? '&thickness=' . $thickness_active->slug : '')
																														?>"
                                       class="spollers__button-option button-option__forms <?php if ($collection->slug === $params_collection) echo 'button-option__forms--active '; ?>">
                                        <div class="button-option__forms-image">
                                            <img src="<?php echo $collection->cat_photo_filter ?>"
                                                 alt="">
                                        </div> <?php echo $collection->name ?>
                                    </a> <?php } ?> </div>
                            </div>
                        </div>
                        <div class="spollers__item">
                            <button type="button"
                                    data-spoller
                                    class="spollers__title <?php if (empty($thickness_list) || $category_active->slug == 'bordjurnyj-kamen') echo 'spollers__title--disable' ?>">толщина</button>
                            <div class=" spollers__body"> <?php foreach ($thickness_list as $thickness) { ?> <a href="<?php echo home_url($wp->request) . '?cat=' . $category_active->slug . ($form_active ? '&form=' . $form_active->slug : '') . ($collection_active ? '&collection=' . $collection_active->slug : '') .  '&thickness=' . $thickness->slug
																														?>"
                                   class="spollers__button-option button-option__forms <?php if ($thickness->slug === $params_thickness) echo 'spollers__button-option--active'; ?>"><?php echo $thickness->name ?></a>
                                <?php } ?> </div>
                        </div>
                        <a href='<?php echo home_url($wp->request)  ?>'
                           class="filter-reset">очистить фильтр</a>
                    </div>
                </div>
            </div>
            <div class="products__list">
                <div class="products__list-wrapper"> <?php
														if ($wc_query->have_posts()) {
															while ($wc_query->have_posts()) {
																$wc_query->the_post();
																$product_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full')[0];
																$product_category = get_the_terms(get_the_ID(), 'product_cat')[0];
																$product_complect =
																	get_field('custom_complect',  get_the_ID());
																$product_thickness =
																	get_field('custom_thickness',  get_the_ID());




														?> <a href="<?php echo get_permalink() ?>"
                       class="products__item item">
                        <div class="item__image">
                            <img src="<?php echo $product_image ?>"
                                 alt="01">
                        </div>
                        <div class="item__info">
                            <div>
                                <h4 class="item__title"><?php the_title(); ?></h4>
                                <span class="caption_01"><?php echo $product_category->name ?></span>
                            </div>
                            <span
                                  class="item__metadata caption_01"><?php echo $product_complect->name . ($category_active->slug == 'trotuarnaja-plitka' ? (' | ' . $product_thickness->name . ' ММ') : "")  ?></span>
                        </div>
                    </a> <?php	}
														} else { ?> <div class="products-not-found">Товаро не найдено</div>
                    <?	}
					?>
                </div>
                <div class="products__line line-02 line-vertical">
                </div>
            </div>
        </div>
    </section>
    <!-- /.products -->
</main> <?php
		get_footer('shop');