<?php

/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', ' KB', ' MB', ' GB', ' TB');

    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}

get_header('shop'); ?> <?php while (have_posts()) : ?> <?php the_post();
                                                        $product_gallery_products = [];
                                                        global $product;
                                                        // var_dump($product);
                                                        $product_complect;
                                                        $product_paint;
                                                        $attachment_ids = $product->get_gallery_image_ids();

                                                        foreach ($attachment_ids as $attachment_id) {
                                                            array_push($product_gallery_products, wp_get_attachment_url($attachment_id));
                                                        }
                                                        $product_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full')[0];
                                                        $product_category = get_the_terms(get_the_ID(), 'product_cat')[0];

                                                        if ($product_category->slug == 'trotuarnaja-plitka') {
                                                            $product_complect =
                                                                get_field('custom_complect',  get_the_ID());
                                                        }
                                                        if ($product_category->slug == 'bordjurnyj-kamen') {
                                                            $product_paint =
                                                                get_field('custom_paint',  get_the_ID());
                                                        }
                                                        $product_collection =
                                                            get_field('custom_collections',  get_the_ID());
                                                        $product_thickness_id =
                                                            get_field('custom_thickness',  get_the_ID())->term_id;

                                                        $product_thickness =
                                                            get_field('attr_full_name', 'pa_tolshhina-mm_' . $product_thickness_id);

                                                        $product_size =
                                                            get_field('custom_size',  get_the_ID());
                                                        $product_per_pallet =
                                                            get_field('custom_per_pallet',  get_the_ID());


                                                        $product_forma_id =
                                                            get_field('custom_forma',  get_the_ID())->term_id;

                                                        $product_forma =
                                                            get_field('attr_full_name', 'pa_forma_' . $product_forma_id);


                                                        $product_weight =
                                                            get_field('custom_weight',  get_the_ID());
                                                        $product_weight_full =
                                                            get_field('custom_weight_full',  get_the_ID());
                                                        $product_quantity_pallet =
                                                            get_field('custom_quantity_pallet',  get_the_ID());
                                                        $product_gallery_sample =
                                                            get_field('custom_gallery_sample',  get_the_ID());
                                                        $product_documents =
                                                            get_field('custom_documents',  get_the_ID());
                                                        $product_styling =
                                                            get_field('custom_styling',  get_the_ID());

                                                        ?> <main class="page">
    <section class="product-hero"
             data-watch=""
             data-watch-once="">
        <div class="product-hero__wrapper grid_layout gutter">
            <div class="product-hero__line_01">
                <div class="line_01 line-vertical">
                </div>
            </div>
            <div class="product-hero__line_02">
                <div class="line_02 line-vertical">
                </div>
            </div>
            <div class="product-hero__line_03">
                <div class="line_03 line-horizontal">
                </div>
            </div>
            <div class="product-hero__line_04">
                <div class="line_04 line-horizontal">
                </div>
            </div>
            <div class="product-hero__line_05">
                <div class="line_05 line-horizontal">
                </div>
            </div>
            <div class="line_06 line-horizontal">
            </div>
            <div class="product-hero__meta">
                <span class="caption_01"> <?php echo $product_category->name ?> </span>
            </div>
            <div class="product__details">
                <div class="product__details_01 details_01">
                    <div class="details_01-cell full">
                        <div class="details_01-content">
                            <p class="caption_01"> ?????????????????? ???????????? </p>
                            <span class="text_01"> <?php echo $product_category->name ?> </span>
                        </div>
                    </div>
                    <div class="details_01-cell">
                        <div class="details_01-content">
                            <p class="caption_01"> ?????????? </p>
                            <span class="text_01"> <?php echo $product_forma ?> </span>
                        </div>
                    </div>
                    <div class="details_01-cell">
                        <div class="details_01-content"> <?php if ($product_paint) { ?> <p class="caption_01"> ????????????
                            </p>
                            <span class="text_01"> <?php echo $product_paint->name ?> </span>
                            <?php } elseif ($product_complect) {  ?> <p class="caption_01"> ?????????????????????????? </p>
                            <span class="text_01"> <?php echo $product_complect->name ?> </span> <?php }  ?>
                        </div>
                    </div>
                    <div class="details_01-cell">
                        <div class="details_01-content">
                            <p class="caption_01"> ??????????????, ???? </p>
                            <span class="text_01"> <?php echo $product_thickness ?> </span>
                        </div>
                    </div>
                    <div class="details_01-cell">
                        <div class="details_01-content">
                            <p class="caption_01"> ??????????????, ???? </p> <?php foreach ($product_size as $size) { ?> <span
                                  class="text_01"> <?php echo $size->name ?> <br /></span> <?php } ?>
                        </div>
                    </div>
                    <div class="details_01-cell">
                        <div class="details_01-content">
                            <p class="caption_01">
                                <?php echo  $product_category->slug == 'bordjurnyj-kamen' ?  '?? ??????????????, ????' :  '?????????????????????? ?? ??????????????, ??2' ?>
                            </p>
                            <span class="text_01"> <?php echo $product_per_pallet->name ?> </span>
                        </div>
                    </div>
                    <div class="details_01-cell">
                        <div class="details_01-content">
                            <p class="caption_01"> ?????? ??????????????, ???? </p>
                            <span class="text_01"> <?php echo $product_weight ?> </span>
                        </div>
                    </div>
                </div>
                <div class="product__details_02 details_01">
                    <div class="details_01-cell full">
                        <div class="details_01-content">
                            <p class="caption_01">
                                <?php echo  $product_category->slug == 'bordjurnyj-kamen' ?  '???????????????????? ????????????????' :  '?????????????? ????????????????????, ??2' ?>
                            </p>
                            <div class="table-counter">
                                <input type="number"
                                       id="cart_step"
                                       value='1'
                                       style="display:none;">
                                <input type="number"
                                       id="cart_price"
                                       value='<?php echo  floatval(str_replace(',', '.', $product->get_price()))    ?>'
                                       style="display:none;">
                                <input type="number"
                                       id='cart_value'
                                       value='<?php echo floatval(str_replace(',', '.', $product_per_pallet->name))  ?>'
                                       style="display:none;">
                                <button class="table-counter__minus"
                                        id='btn_minus_step'>-</button>
                                <div class="table-counter__content">
                                    <div id='cart_show_pallet'></div> &nbsp;
                                    <span><?php echo $product_category->slug == 'bordjurnyj-kamen' ?  ' ????' :  '??<sup>2</sup>' ?></span>
                                </div>
                                <button class="table-counter__plus"
                                        id='btn_plus_step'>+</button>
                            </div>
                        </div>
                    </div>
                    <div class="details_01-cell">
                        <div class="details_01-content">
                            <p class="caption_01"> ?????????????????????? ???????????????? </p>
                            <span class="text_01"> <?php echo $product_quantity_pallet->name ?> </span>
                        </div>
                    </div>
                    <div class="details_01-cell">
                        <div class="details_01-content">
                            <p class="caption_01"> ?????????? ?????? </p>
                            <span class="text_01"> <?php echo $product_weight_full ?> </span>
                        </div>
                    </div>
                    <div class="details_01-cell full">
                        <div class="details_01-content">
                            <p class="caption_01"> ?????????????????????????????? ???????? </p>
                            <span class="text_01">
                                <div class="table-total"
                                     id='cart_show_total'></div>
                            </span>
                        </div>
                    </div>
                </div>
                <span class="caption_01"> ???????? ?????????????? ?????? ?????????? ????????????????, <br> ?????????????????? ???????????????? ?????????????????? ?? ??????????????????
                </span>
            </div>
            <h2 class="product__title title title-animation"><?php the_title() ?></h2>
            <!-- ???????????????? ???????????????? -->
            <div class="product-modal__slider">
                <!-- ?????????????????????? ?????????? ???????????????? -->
                <div class="product-modal__slider-wrapper swiper">
                    <div class="product-modal__wrapper swiper-wrapper">
                        <!-- ?????????? --> <?php foreach ($product_gallery_products as $gallery_item) { ?> <div
                             class="product-modal__slide swiper-slide">
                            <img src="<?php echo $gallery_item ?>"
                                 alt="01">
                        </div> <?php } ?>
                    </div>
                </div>
                <dic class="caption_01 slider__pagination slider__pagination--count_image"></dic>
                <div class="slider__navigation">
                    <button type="button"
                            class="button-border slider-images-01 swiper-button-prev swiper-button-disabled"
                            disabled="">
                        <svg width="18"
                             height="8"
                             viewBox="0 0 18 8"
                             fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.2 0.600098L1.9 4.0001L5.2 7.3001"
                                  stroke="#3A3A1F"></path>
                            <path d="M18 4H1.9"
                                  stroke="#3A3A1F"></path>
                        </svg>
                    </button>
                    <button type="button"
                            class="button-border slider-images-01 swiper-button-next">
                        <svg width="18"
                             height="8"
                             viewBox="0 0 18 8"
                             fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.8 0.600098L16.1 4.0001L12.8 7.3001"
                                  stroke="#3A3A1F"></path>
                            <path d="M0 4H16.1"
                                  stroke="#3A3A1F"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="product__copy"> <?php echo  $product->description ?> </div>
        </div>
    </section>
    <!-- /.product-hero -->
    <section class="product-slider margin-bottom">
        <div class="product-slider__wrapper gutter">
            <!-- ???????????????? ???????????????? -->
            <div class="product-slider__slider swiper">
                <!-- ?????????????????????? ?????????? ???????????????? -->
                <div class="product-slider__wrapper swiper-wrapper">
                    <!-- ?????????? --> <?php foreach ($product_gallery_sample as $gallery_item) { ?> <div
                         class="product-slider__slide swiper-slide">
                        <img srcset="<?php echo $gallery_item['full_image_url'] ?>"
                             alt="01">
                    </div> <?php } ?>
                </div>
            </div>
            <dic class="caption_01 slider__pagination slider__pagination--count_image"></dic>
            <div class="slider__navigation">
                <button type="button"
                        class="button-border swiper-button-prev swiper-button-prev-2">
                    <svg width="18"
                         height="8"
                         viewBox="0 0 18 8"
                         fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.2 0.600098L1.9 4.0001L5.2 7.3001"
                              stroke="#3A3A1F" />
                        <path d="M18 4H1.9"
                              stroke="#3A3A1F" />
                    </svg>
                </button>
                <button type="button"
                        class="button-border swiper-button-next swiper-button-next-2">
                    <svg width="18"
                         height="8"
                         viewBox="0 0 18 8"
                         fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.8 0.600098L16.1 4.0001L12.8 7.3001"
                              stroke="#3A3A1F" />
                        <path d="M0 4H16.1"
                              stroke="#3A3A1F" />
                    </svg>
                </button>
            </div>
        </div>
    </section>
    <!-- /.product-slider -->
    <section class="product-collection margin-bottom "
             data-watch=""
             data-watch-once="">
        <div class="product-collection__wrapper gutter padding-bottom">
            <h2 class="product-collection__title title title-animation"> ?????????????????? ?? ?????????? </h2>
            <div class="product-collection__tabs">
                <div data-tabs
                     class="tabs">
                    <nav data-tabs-titles
                         class="tabs__navigation"> <?php foreach ($product_collection as $key => $collection_item) {
                                $collection_name =
                                    get_field('attr_full_name', 'pa_kollekcija_' . $collection_item->term_id); ?>
                        <button type="button"
                                class="tabs__title  <?php if ($key == 0) echo '_tab-active'; ?> button-border no-radius"><?php echo $collection_name ?></button>
                        <?php } ?> </nav>
                    <div data-tabs-body
                         class="tabs__content"> <?php foreach ($product_collection as $key => $collection_item) {
                                                                        $collection_variants =
                                                                            get_field('collection_variants', 'pa_kollekcija_' . $collection_item->term_id);
                                                                    ?> <div class="tabs__body ">
                            <div class="tabs__body-wrapper grid_layout">
                                <?php foreach ($collection_variants as $variant) { ?> <a
                                   href="<?php echo $variant['collection_variant_image'] ?>">
                                    <img src="<?php echo $variant['collection_variant_image']  ?>"
                                         alt="01">
                                    <span class="caption_01"> <?php echo $variant['collection_variant_name']  ?> </span>
                                </a> <?php } ?> </div>
                        </div> <?php } ?> </div>
                </div>
            </div>
            <div class="product-collection__line line-horizontal">
            </div>
    </section>
    <!-- /.product-collection -->
    <section class="product-sizes margin-bottom padding-bottom"
             data-watch=""
             data-watch-once="">
        <div class="product-sizes__wrapper gutter">
            <h2 class="product-sizes__title title title-animation"> ?????????????? </h2>
            <div class="product-sizes__list grid_layout"> <?php foreach ($product_size as $size) {
                                                                    $size_image =
                                                                        get_field('size_image', 'pa_razmery-mm_' . $size->term_id);

                                                                ?> <div class="product-sizes__item item">
                    <div class="item__body">
                        <a href="<?php echo $size_image ?>"
                           download>
                            <img src="<?php echo $size_image ?>"
                                 alt="01">
                        </a>
                        <span class="caption_01"><?php echo $size->name ?></span>
                    </div>
                </div> <?php } ?> </div>
            <div class="product-sizes__line line-horizontal">
            </div>
        </div>
    </section>
    <!-- /.product-sizes -->
    <section class="product-variants margin-bottom padding-bottom"
             data-watch=""
             data-watch-once="">
        <div class="product-variants__wrapper gutter">
            <h2 class="product-variants__title title title-animation"> ???????????????? ?????????? ?????????????? </h2>
            <div class="product-variants__list grid_layout"> <?php foreach ($product_styling as $styling) { ?> <div
                     class="product-variants__item item">
                    <div class="item__body">
                        <a href="<?php echo $styling['custom_styling_item'] ?>"
                           download>
                            <img src="<?php echo $styling['custom_styling_item'] ?>"
                                 alt="01">
                        </a>
                    </div>
                </div> <?php } ?> </div>
            <div class="product-variants__line line-horizontal">
            </div>
        </div>
    </section>
    <!-- /.product-variants -->
    <section class="documents"
             data-watch=""
             data-watch-once="">
        <div class="documents__container">
            <h2 class="documents__title title title-animation"> ???????????????????????? </h2>
            <div class="documents__list grid_layout">
                <ul> <?php
                            $monthes = array(
                                1 => '????????????', 2 => '??????????????', 3 => '??????????', 4 => '????????????',
                                5 => '??????', 6 => '????????', 7 => '????????', 8 => '??????????????',
                                9 => '????????????????', 10 => '??????????????', 11 => '????????????', 12 => '??????????????'
                            );
                            foreach ($product_documents as $document) {
                            ?> <li class="documents-item">
                        <a href="<?php echo $document['custom_document_item']['url'] ?>"
                           download>
                            <div class="documents-item__body">
                                <svg class="icon-0-3-383"
                                     width="38"
                                     height="38"
                                     viewBox="0 0 38 38"
                                     fill="none">
                                    <rect width="38"
                                          height="38"
                                          rx="2"
                                          fill="currentColor"></rect>
                                    <path d="M28.1892 14.625C28.1893 14.5665 28.1775 14.5086 28.1546 14.4547C28.1317 14.4009 28.0982 14.3522 28.0561 14.3115L21.9352 8.19058L21.9313 8.1873C21.9122 8.16853 21.8915 8.15154 21.8693 8.13655C21.8619 8.13163 21.8537 8.12845 21.846 8.12397C21.8294 8.11358 21.8121 8.10434 21.7942 8.0963C21.7844 8.09225 21.774 8.09028 21.764 8.08689C21.7472 8.0806 21.73 8.07538 21.7125 8.07125C21.684 8.06546 21.655 8.06253 21.6259 8.0625H11.125C10.777 8.06291 10.4434 8.20132 10.1974 8.44737C9.95132 8.69342 9.81291 9.02703 9.8125 9.375V28.625C9.81288 28.973 9.95128 29.3066 10.1973 29.5527C10.4434 29.7987 10.777 29.9371 11.125 29.9375H26.8759C27.2239 29.9371 27.5575 29.7987 27.8035 29.5527C28.0496 29.3066 28.188 28.973 28.1884 28.625V14.6337L28.1892 14.625ZM22.0634 9.55623L26.6946 14.1875H22.0634V9.55623ZM26.8759 29.0625H11.125C11.009 29.0624 10.8978 29.0163 10.8158 28.9342C10.7337 28.8522 10.6876 28.741 10.6875 28.625V9.375C10.6876 9.25901 10.7338 9.14782 10.8158 9.0658C10.8978 8.98378 11.009 8.93764 11.125 8.9375H21.1884V14.625C21.1884 14.741 21.2345 14.8523 21.3165 14.9344C21.3986 15.0164 21.5098 15.0625 21.6259 15.0625H27.3134V28.625C27.3133 28.741 27.2671 28.8522 27.1851 28.9342C27.1031 29.0163 26.9919 29.0624 26.8759 29.0625Z"
                                          fill="white"></path>
                                </svg>
                                <div class="documents-item__content">
                                    <h4> <?php echo $document['custom_document_item']['title']; ?></h4>
                                    <span class="caption_01">
                                        <?php echo date('d', strtotime($document['custom_document_item']['date'])) . ' ' . $monthes[(date('n', strtotime($document['custom_document_item']['date'])))]  .  ' ' . date('Y', strtotime($document['custom_document_item']['date'])) . ' | ????????????????/' . pathinfo($document['custom_document_item']['url'])['extension'] . ' ' . formatBytes($document['custom_document_item']['filesize']) ?>
                                    </span>
                                </div>
                                <svg width="14"
                                     height="12"
                                     class="downloadIcon-0-3-384"
                                     viewBox="0 0 16 14"
                                     fill="none">
                                    <path d="M0.5 8.89978C0.632608 8.89978 0.759785 8.95246 0.853553 9.04623C0.947322 9.14 1 9.26717 1 9.39978V11.8998C1 12.165 1.10536 12.4194 1.29289 12.6069C1.48043 12.7944 1.73478 12.8998 2 12.8998H14C14.2652 12.8998 14.5196 12.7944 14.7071 12.6069C14.8946 12.4194 15 12.165 15 11.8998V9.39978C15 9.26717 15.0527 9.14 15.1464 9.04623C15.2402 8.95246 15.3674 8.89978 15.5 8.89978C15.6326 8.89978 15.7598 8.95246 15.8536 9.04623C15.9473 9.14 16 9.26717 16 9.39978V11.8998C16 12.4302 15.7893 12.9389 15.4142 13.314C15.0391 13.6891 14.5304 13.8998 14 13.8998H2C1.46957 13.8998 0.960859 13.6891 0.585786 13.314C0.210714 12.9389 0 12.4302 0 11.8998V9.39978C0 9.26717 0.0526784 9.14 0.146447 9.04623C0.240215 8.95246 0.367392 8.89978 0.5 8.89978Z"
                                          fill="currentColor"></path>
                                    <path d="M7.64566 10.854C7.6921 10.9006 7.74728 10.9375 7.80802 10.9627C7.86877 10.9879 7.93389 11.0009 7.99966 11.0009C8.06542 11.0009 8.13054 10.9879 8.19129 10.9627C8.25203 10.9375 8.30721 10.9006 8.35366 10.854L11.3537 7.854C11.4475 7.76011 11.5003 7.63278 11.5003 7.5C11.5003 7.36722 11.4475 7.23989 11.3537 7.146C11.2598 7.05211 11.1324 6.99937 10.9997 6.99937C10.8669 6.99937 10.7395 7.05211 10.6457 7.146L8.49966 9.293V0.5C8.49966 0.367392 8.44698 0.240215 8.35321 0.146447C8.25944 0.0526784 8.13226 0 7.99966 0C7.86705 0 7.73987 0.0526784 7.6461 0.146447C7.55233 0.240215 7.49966 0.367392 7.49966 0.5V9.293L5.35366 7.146C5.25977 7.05211 5.13243 6.99937 4.99966 6.99937C4.86688 6.99937 4.73954 7.05211 4.64566 7.146C4.55177 7.23989 4.49902 7.36722 4.49902 7.5C4.49902 7.63278 4.55177 7.76011 4.64566 7.854L7.64566 10.854Z"
                                          fill="currentColor"></path>
                                </svg>
                            </div>
                        </a>
                    </li> <?php } ?> </ul>
            </div>
            <div class="documents__line line-horizontal">
            </div>
        </div>
    </section>
    <!-- /.documents -->
    <section class="consultation padding-bottom margin-bottom"
             data-watch
             data-watch-once>
        <div class="consultation__content grid_layout gutter">
            <h2 class="consultation__title title title-animation"> ???????????????????????? <br> ?????????????????????? </h2>
            <div class="consultation__block">
                <p class="consultation__text"> ?????????????? ??????????? <br> ???????? ????????????????-???????????????????????? ???????????? ??????????????????????????. </p>
                <a href="tel:+79068759597"
                   class="button-border"> ???????????? <span> +7 906 875 95 97</span>
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
                <a href="mailto:mail@gefest-plitka.ru"
                   class="button-border"> ???????????????? <span>mail@gefest-plitka.ru</span>
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
            <div class="consultation__line line-horizontal"></div>
        </div>
    </section>
    <!-- /.consultation --> <?php woocommerce_related_products(array(
                                    'posts_per_page' => 3,
                                    'columns'        => 3,
                                    'orderby'        => 'rand'
                                )) ?> <section class="similar">
        <!-- /.similar -->
</main> <?php endwhile; // end of the loop. 
            ?> <?php
                get_footer('shop');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */