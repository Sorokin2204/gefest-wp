jQuery(document).ready(function () {
    'use strict';
    viwcaio_design_init();
    viwcaio_customize_init();
    viwcaio_sc_design();
    viwcaio_sc_icon_design();
    viwcaio_sc_header_design();
    viwcaio_sc_footer_design();
    viwcaio_sc_checkout_design();
    viwcaio_sb_design();
});
function viwcaio_design_init() {
    jQuery('.vi-wcaio-customize-checkbox').each(function () {
        jQuery(this).checkbox();
        jQuery(this).on('change', function () {
            let input = jQuery(this).parent().find('input[type="hidden"]');
            if (jQuery(this).prop('checked')) {
                input.val('1');
            }else {
                input.val('');
            }
            let setting = input.attr('data-customize-setting-link');
            wp.customize(setting).set(input.val());
        });
    });
    jQuery('.vi-wcaio-customize-range').each(function () {
        let range_wrap = jQuery(this),
            range = jQuery(this).find('.vi-wcaio-customize-range1');
        let min = range.attr('min') || 0,
            max = range.attr('max') || 0,
            start = range.data('start');
        range.range({
            min: min,
            max: max,
            start: start,
            input: range_wrap.find('.vi-wcaio-customize-range-value'),
            onChange: function (val) {
                let setting = range_wrap.find('.vi-wcaio-customize-range-value').attr('data-customize-setting-link');
                wp.customize(setting, function (e) {
                    e.set(val);
                });
            }
        });
        range_wrap.next('.vi-wcaio-customize-range-min-max').find('.vi-wcaio-customize-range-min').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            range.range('set value', min);
            let setting = range_wrap.find('.vi-wcaio-customize-range-value').attr('data-customize-setting-link');
            wp.customize(setting, function (e) {
                e.set(min);
            });
        });
        range_wrap.next('.vi-wcaio-customize-range-min-max').find('.vi-wcaio-customize-range-max').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            range.range('set value', max);
            let setting = range_wrap.find('.vi-wcaio-customize-range-value').attr('data-customize-setting-link');
            wp.customize(setting, function (e) {
                e.set(max);
            });
        });
        range_wrap.find('.vi-wcaio-customize-range-value').on('change', function () {
            let setting = jQuery(this).attr('data-customize-setting-link'),
                val = parseInt(jQuery(this).val() || 0);
            if (val > parseInt(max)) {
                val = max
            } else if (val < parseInt(min)) {
                val = min;
            }
            range.range('set value', val);
            wp.customize(setting, function (e) {
                e.set(val);
            });
        });
    });
    jQuery('.vi-wcaio-customize-radio').each(function () {
        jQuery(this).buttonset();
        jQuery(this).find('input:radio').on('change', function () {
            let setting = jQuery(this).attr('data-customize-setting-link'),
                val = parseInt(jQuery(this).val() || 0);
            wp.customize(setting, function (e) {
                e.set(val);
            });
        });
    });
    jQuery('.vi-wcaio-customize-search-select2').each(function () {
        let select = jQuery(this);
        let close_on_select = false, min_input = 2, placeholder = '', action = '', type_select2 = select.data('type_select2');
        switch (type_select2) {
            case 'product':
                placeholder = 'Please fill in your product title';
                action = 'viwcaio_search_product';
                break;
            case 'category':
                placeholder = 'Please fill in your category title';
                action = 'viwcaio_search_cats';
                break;
        }
        select.select2(viwcaio_select2_params(placeholder, action, close_on_select, min_input)).on('change',function () {
            let setting = select.attr('data-customize-setting-link');
            let val =select.val();
            wp.customize(setting).set(val ? val.join():'');
            select.val(val);
        }).val( select.find('option').map(function () {
            return jQuery(this).val();
        })).trigger('change');
    });
}

function viwcaio_select2_params(placeholder='', action='', close_on_select=true, min_input=3,multiple=true) {
    let result = {
        closeOnSelect: close_on_select,
        placeholder: placeholder,
        cache: true
    };
    if (action) {
        result['minimumInputLength'] = min_input;
        result['escapeMarkup'] = function (markup) {
            return markup;
        };
        result['ajax'] = {
            url: "admin-ajax.php?action=" + action,
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: false
        };
    }
    return result;
}
function viwcaio_customize_init() {
    let sc_show = [
        'vi_wcaio_design_sidebar_cart_general',
        'vi_wcaio_design_sidebar_header',
        'vi_wcaio_design_sidebar_footer',
        'vi_wcaio_design_sidebar_products',
    ],
    sc_hide = [
        'vi_wcaio_design_sidebar_cart_icon',
        'vi_wcaio_design_menu_cart',
    ];
    jQuery.each(sc_show, function (k, v) {
        wp.customize.section(v, function (section) {
            section.expanded.bind(function (isExpanded) {
                if (isExpanded && wp.customize('woo_cart_all_in_one_params[sc_enable]').get()) {
                    wp.customize.previewer.send('vi_wcaio_sc_toggle', 'show', '');
                }
            });
        });
    });
    jQuery.each(sc_hide, function (k, v) {
        wp.customize.section(v, function (section) {
            section.expanded.bind(function (isExpanded) {
                if (isExpanded) {
                    wp.customize.previewer.send('vi_wcaio_sc_toggle', 'hide', '');
                }
            });
        });
    });
    wp.customize.section('vi_wcaio_design_checkout', function (section) {
        section.expanded.bind(function (isExpanded) {
            if (isExpanded && wp.customize('woo_cart_all_in_one_params[sc_enable]').get() && wp.customize('woo_cart_all_in_one_params[sc_checkout_enable]').get() ) {
                wp.customize.previewer.send('vi_wcaio_sc_toggle', 'show-checkout', '');
            }
        });
    });
    wp.customize.section('vi_wcaio_design_sticky_atc', function (section) {
        section.expanded.bind(function (isExpanded) {
            if (isExpanded && wp.customize('woo_cart_all_in_one_params[sb_enable]').get()) {
                if (jQuery('select[id="_customize-input-woo_cart_all_in_one_params[sb_select_product]"]') !== wp.customize('woo_cart_all_in_one_params[sb_select_product]').get()){
                    wp.customize.previewer.send('vi_wcaio_sb_update_url',jQuery('select[id="_customize-input-woo_cart_all_in_one_params[sb_select_product]"]').find('option').eq(0).val());
                }else {
                    wp.customize.previewer.send('vi_wcaio_sb_update_url', wp.customize('woo_cart_all_in_one_params[sb_select_product]').get());
                }
            }
        });
    });
    wp.customize.previewer.bind('vi_wcaio_update_url', function (url) {
        wp.customize.previewer.previewUrl.set(url);
    });
    wp.customize.panel('vi_wcaio_design', function (section) {
        section.expanded.bind(function (isExpanded) {
            if (isExpanded) {
                let current_url = wp.customize.previewer.previewUrl.get(),
                    cart_url = vi_wcaio_preview_setting.cart_url,
                    checkout_url = vi_wcaio_preview_setting.checkout_url;
                if (current_url.indexOf(cart_url) > -1 || current_url.indexOf(checkout_url) > -1) {
                    wp.customize.previewer.send('vi_wcaio_update_url',vi_wcaio_preview_setting.shop_url);
                }
            }
        });
    });
}
function viwcaio_sb_design() {
    if (jQuery('input:checkbox[name="woo_cart_all_in_one_params[sb_pd_review]"]').prop('checked')) {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sb_pd_review_color"]').removeClass('vi-wcaio-disabled');
    } else {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sb_pd_review_color"]').addClass('vi-wcaio-disabled');
    }
    jQuery('input:checkbox[name="woo_cart_all_in_one_params[sb_pd_review]"]').on('change', function () {
        if (jQuery(this).prop('checked')) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sb_pd_review_color"]').removeClass('vi-wcaio-disabled');
        } else {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sb_pd_review_color"]').addClass('vi-wcaio-disabled');
        }
    });
    if (jQuery('input:checkbox[name="woo_cart_all_in_one_params[sb_quantity]"]').prop('checked')) {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sb_quantity_border_radius"]').removeClass('vi-wcaio-disabled');
    } else {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sb_quantity_border_radius"]').addClass('vi-wcaio-disabled');
    }
    jQuery('input:checkbox[name="woo_cart_all_in_one_params[sb_quantity]"]').on('change', function () {
        if (jQuery(this).prop('checked')) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sb_quantity_border_radius"]').removeClass('vi-wcaio-disabled');
        } else {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sb_quantity_border_radius"]').addClass('vi-wcaio-disabled');
        }
    });
    if (!jQuery('input:checkbox[name="woo_cart_all_in_one_params[sb_enable]"]').prop('checked')) {
        jQuery('#sub-accordion-section-vi_wcaio_design_sticky_atc > li.vi-wcaio-disabled').addClass('vi-wcaio-disabled-1');
        jQuery('#sub-accordion-section-vi_wcaio_design_sticky_atc > li:gt(1)').addClass('vi-wcaio-disabled');
    }
    jQuery('input:checkbox[name="woo_cart_all_in_one_params[sb_enable]"]').on('change', function () {
        if (jQuery(this).prop('checked')) {
            jQuery('#sub-accordion-section-vi_wcaio_design_sticky_atc > li:gt(1)').removeClass('vi-wcaio-disabled');
            jQuery('#sub-accordion-section-vi_wcaio_design_sticky_atc > li.vi-wcaio-disabled-1').removeClass('vi-wcaio-disabled-1').addClass('vi-wcaio-disabled');
            if (jQuery('select[id="_customize-input-woo_cart_all_in_one_params[sb_select_product]"]') !== wp.customize('woo_cart_all_in_one_params[sb_select_product]').get()){
                wp.customize.previewer.send('vi_wcaio_sb_update_url',jQuery('select[id="_customize-input-woo_cart_all_in_one_params[sb_select_product]"]').find('option').eq(0).val());
            }else {
                wp.customize.previewer.send('vi_wcaio_sb_update_url', wp.customize('woo_cart_all_in_one_params[sb_select_product]').get());
            }
        }else {
            jQuery('#sub-accordion-section-vi_wcaio_design_sticky_atc > li.vi-wcaio-disabled').addClass('vi-wcaio-disabled-1');
            jQuery('#sub-accordion-section-vi_wcaio_design_sticky_atc > li:gt(1)').addClass('vi-wcaio-disabled');
        }
    });
}

function viwcaio_sc_design() {
    if (wp.customize('woo_cart_all_in_one_params[sc_display_type]').get() === '2') {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_horizontal"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_vertical"]').addClass('vi-wcaio-disabled');
    }
    jQuery('select[id="_customize-input-woo_cart_all_in_one_params[sc_display_type]"]').on('change', function () {
        if (jQuery(this).val() === '2') {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_horizontal"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_vertical"]').addClass('vi-wcaio-disabled');
        } else {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_horizontal"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_vertical"]').removeClass('vi-wcaio-disabled');
        }
    });
}
function viwcaio_sc_icon_design() {
    if (wp.customize('woo_cart_all_in_one_params[sc_icon_use_img]').get()) {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_default_icon"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_color"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_img"]').removeClass('vi-wcaio-disabled');
    }
    jQuery('input[name="woo_cart_all_in_one_params[sc_icon_use_img]"]').on('change', function () {
        if (!jQuery(this).prop('checked')) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_default_icon"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_color"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_img"]').addClass('vi-wcaio-disabled');
        } else {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_default_icon"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_color"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_img"]').removeClass('vi-wcaio-disabled');
        }
    });
    let sc_icon_style = wp.customize('woo_cart_all_in_one_params[sc_icon_style]').get();
    if (sc_icon_style === '4') {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_count_type"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_count_bg_color"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_count_color"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_count_border_radius"]').addClass('vi-wcaio-disabled');
    } else {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_count_type"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_count_bg_color"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_count_color"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_count_border_radius"]').removeClass('vi-wcaio-disabled');
        if (sc_icon_style === '3') {
            jQuery('select[id="_customize-input-woo_cart_all_in_one_params[sc_icon_count_type]"] option[value="cart_subtotal"]').addClass('vi-wcaio-disabled');
            if (wp.customize('woo_cart_all_in_one_params[sc_icon_count_type]').get() === 'cart_subtotal') {
                wp.customize('woo_cart_all_in_one_params[sc_icon_count_type]').set('pd_count');
            }
        } else {
            jQuery('select[id="_customize-input-woo_cart_all_in_one_params[sc_icon_count_type]"] option[value="cart_subtotal"]').removeClass('vi-wcaio-disabled');
        }
    }
    jQuery('[name="_customize-text-woo_cart_all_in_one_params[sc_icon_style]"]').on('change', function () {
        if (jQuery(this).val() === '4') {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_count_type"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_count_bg_color"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_count_color"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_count_border_radius"]').addClass('vi-wcaio-disabled');
        } else {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_count_type"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_count_bg_color"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_count_color"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_icon_count_border_radius"]').removeClass('vi-wcaio-disabled');
            if (jQuery(this).val() === '3') {
                jQuery('select[id="_customize-input-woo_cart_all_in_one_params[sc_icon_count_type]"] option[value="cart_subtotal"]').addClass('vi-wcaio-disabled');
                if (wp.customize('woo_cart_all_in_one_params[sc_icon_count_type]').get() === 'cart_subtotal') {
                    wp.customize('woo_cart_all_in_one_params[sc_icon_count_type]').set('pd_count');
                }
            } else {
                jQuery('select[id="_customize-input-woo_cart_all_in_one_params[sc_icon_count_type]"] option[value="cart_subtotal"]').removeClass('vi-wcaio-disabled');
            }
        }
    });
}

function viwcaio_sc_header_design() {
    if (jQuery('input:checkbox[name="woo_cart_all_in_one_params[sc_header_coupon_enable]"]').prop('checked')) {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_input_radius"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_bg_color"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_color"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_bg_color_hover"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_color_hover"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_border_radius"]').removeClass('vi-wcaio-disabled');
    } else {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_input_radius"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_bg_color"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_color"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_bg_color_hover"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_color_hover"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_border_radius"]').addClass('vi-wcaio-disabled');
    }
    jQuery('input:checkbox[name="woo_cart_all_in_one_params[sc_header_coupon_enable]"]').on('change', function () {
        if (jQuery(this).prop('checked')) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_input_radius"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_bg_color"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_color"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_bg_color_hover"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_color_hover"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_border_radius"]').removeClass('vi-wcaio-disabled');
        } else {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_input_radius"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_bg_color"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_color"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_bg_color_hover"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_color_hover"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_header_coupon_button_border_radius"]').addClass('vi-wcaio-disabled');
        }
    });
}

function viwcaio_sc_footer_design() {
    let languages = vi_wcaio_preview_setting.languages;
    switch (wp.customize('woo_cart_all_in_one_params[sc_footer_pd_plus]').get()) {
        case 'select_cat':
            jQuery('li#customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_cats').removeClass('vi-wcaio-disabled');
            jQuery('li#customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_products').addClass('vi-wcaio-disabled');
            break;
        case 'select_pd':
            jQuery('li#customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_cats').addClass('vi-wcaio-disabled');
            jQuery('li#customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_products').removeClass('vi-wcaio-disabled');
            break;
        default:
            jQuery('li#customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_cats').addClass('vi-wcaio-disabled');
            jQuery('li#customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_products').addClass('vi-wcaio-disabled');
    }
    jQuery('select[id="_customize-input-woo_cart_all_in_one_params[sc_footer_pd_plus]"]').on('change', function () {
        switch (jQuery(this).val()) {
            case 'select_cat':
                jQuery('li#customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_cats').removeClass('vi-wcaio-disabled');
                jQuery('li#customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_products').addClass('vi-wcaio-disabled');
                break;
            case 'select_pd':
                jQuery('li#customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_cats').addClass('vi-wcaio-disabled');
                jQuery('li#customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_products').removeClass('vi-wcaio-disabled');
                break;
            default:
                jQuery('li#customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_cats').addClass('vi-wcaio-disabled');
                jQuery('li#customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_products').addClass('vi-wcaio-disabled');
        }
    });
    if (jQuery('input:checkbox[name="woo_cart_all_in_one_params[sc_pd_update_cart]"]').prop('checked')) {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_bg_color"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_color"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_hover_bg_color"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_hover_color"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_border_radius"]').addClass('vi-wcaio-disabled');
    }else {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_bg_color"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_color"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_hover_bg_color"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_hover_color"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_border_radius"]').removeClass('vi-wcaio-disabled');
    }
    jQuery('input:checkbox[name="woo_cart_all_in_one_params[sc_pd_update_cart]"]').on('change', function () {
        if (jQuery(this).prop('checked')) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_bg_color"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_color"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_hover_bg_color"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_hover_color"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_border_radius"]').addClass('vi-wcaio-disabled');
        }else {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_bg_color"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_color"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_hover_bg_color"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_hover_color"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_update_border_radius"]').removeClass('vi-wcaio-disabled');
        }
    });
    if (jQuery('select[id="_customize-input-woo_cart_all_in_one_params[sc_footer_button]"]').val() === 'cart') {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_cart_text"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_checkout_text"]').addClass('vi-wcaio-disabled');
        jQuery.each(languages, function (k, v) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_cart_text_' + v + '"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_checkout_text_' + v + '"]').addClass('vi-wcaio-disabled');
        });
    } else {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_cart_text"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_checkout_text"]').removeClass('vi-wcaio-disabled');
        jQuery.each(languages, function (k, v) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_cart_text_' + v + '"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_checkout_text_' + v + '"]').removeClass('vi-wcaio-disabled');
        });
    }
    jQuery('select[id="_customize-input-woo_cart_all_in_one_params[sc_footer_button]"]').on('change', function () {
        if (jQuery(this).val() === 'cart') {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_cart_text"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_checkout_text"]').addClass('vi-wcaio-disabled');
            jQuery.each(languages, function (k, v) {
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_cart_text_' + v + '"]').removeClass('vi-wcaio-disabled');
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_checkout_text_' + v + '"]').addClass('vi-wcaio-disabled');
            });
        } else {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_cart_text"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_checkout_text"]').removeClass('vi-wcaio-disabled');
            jQuery.each(languages, function (k, v) {
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_cart_text_' + v + '"]').addClass('vi-wcaio-disabled');
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_bt_checkout_text_' + v + '"]').removeClass('vi-wcaio-disabled');
            });
        }
    });
    let sc_footer_message = jQuery('textarea[id="_customize-input-woo_cart_all_in_one_params[sc_footer_message]"]').val();
    if (sc_footer_message.indexOf('{product_plus}') !== -1){
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_title"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_color"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_out_of_stock"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_limit"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_title"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_cart_icon"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_bg_color"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_color"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_hover_bg_color"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_hover_color"]').removeClass('vi-wcaio-disabled');
        jQuery.each(languages, function (k, v) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_title_' + v + '"]').removeClass('vi-wcaio-disabled');
        });
    }else {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_title"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_color"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_out_of_stock"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_limit"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_title"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_cart_icon"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_bg_color"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_color"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_hover_bg_color"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_hover_color"]').addClass('vi-wcaio-disabled');
        jQuery.each(languages, function (k, v) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_title_' + v + '"]').addClass('vi-wcaio-disabled');
        });
    }
    jQuery('textarea[id="_customize-input-woo_cart_all_in_one_params[sc_footer_message]"]').on('keyup',function () {
        let val = jQuery(this).val();
        if (val.indexOf('{product_plus}') !== -1){
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_title"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_color"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_out_of_stock"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_limit"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_title"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_cart_icon"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_bg_color"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_color"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_hover_bg_color"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_hover_color"]').removeClass('vi-wcaio-disabled');
            jQuery.each(languages, function (k, v) {
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_title_' + v + '"]').removeClass('vi-wcaio-disabled');
            });
        }else {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_title"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_color"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_out_of_stock"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_limit"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_title"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_cart_icon"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_bg_color"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_color"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_hover_bg_color"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_bt_atc_hover_color"]').addClass('vi-wcaio-disabled');
            jQuery.each(languages, function (k, v) {
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_footer_pd_plus_title_' + v + '"]').addClass('vi-wcaio-disabled');
            });
        }
    });
}

function viwcaio_sc_checkout_design() {
    let languages = vi_wcaio_preview_setting.languages;
    if (jQuery('input:checkbox[name="woo_cart_all_in_one_params[sc_checkout_billing_shipping]"]').prop('checked')) {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_billing_shipping_title"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_billing_title"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_shipping_title"]').addClass('vi-wcaio-disabled');
        jQuery.each(languages, function (k, v) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_billing_title_' + v + '"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_shipping_title_' + v + '"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_billing_shipping_title_' + v + '"]').removeClass('vi-wcaio-disabled');
        });
    } else {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_billing_title"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_shipping_title"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_billing_shipping_title"]').addClass('vi-wcaio-disabled');
        jQuery.each(languages, function (k, v) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_billing_title_' + v + '"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_shipping_title_' + v + '"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_billing_shipping_title_' + v + '"]').addClass('vi-wcaio-disabled');
        });
    }
    jQuery('input:checkbox[name="woo_cart_all_in_one_params[sc_checkout_billing_shipping]"]').on('change', function () {
        if (jQuery(this).prop('checked')) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_billing_shipping_title"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_billing_title"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_shipping_title"]').addClass('vi-wcaio-disabled');
            jQuery.each(languages, function (k, v) {
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_billing_title_' + v + '"]').addClass('vi-wcaio-disabled');
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_shipping_title_' + v + '"]').addClass('vi-wcaio-disabled');
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_billing_shipping_title_' + v + '"]').removeClass('vi-wcaio-disabled');
            });
        } else {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_billing_title"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_shipping_title"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_billing_shipping_title"]').addClass('vi-wcaio-disabled');
            jQuery.each(languages, function (k, v) {
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_billing_title_' + v + '"]').removeClass('vi-wcaio-disabled');
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_shipping_title_' + v + '"]').removeClass('vi-wcaio-disabled');
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_billing_shipping_title_' + v + '"]').addClass('vi-wcaio-disabled');
            });
        }
    });
    if (jQuery('input:checkbox[name="woo_cart_all_in_one_params[sc_checkout_order_payment]"]').prop('checked')) {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_order_payment_title"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_order_review_title"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_payment_title"]').addClass('vi-wcaio-disabled');
        jQuery.each(languages, function (k, v) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_order_review_title_' + v + '"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_payment_title_' + v + '"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_order_payment_title_' + v + '"]').removeClass('vi-wcaio-disabled');
        });
    } else {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_order_payment_title"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_order_review_title"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_payment_title"]').removeClass('vi-wcaio-disabled');
        jQuery.each(languages, function (k, v) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_order_review_title_' + v + '"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_payment_title_' + v + '"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_order_payment_title_' + v + '"]').addClass('vi-wcaio-disabled');
        });
    }
    jQuery('input:checkbox[name="woo_cart_all_in_one_params[sc_checkout_order_payment]"]').on('change', function () {
        if (jQuery(this).prop('checked')) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_order_payment_title"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_order_review_title"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_payment_title"]').addClass('vi-wcaio-disabled');
            jQuery.each(languages, function (k, v) {
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_order_review_title_' + v + '"]').addClass('vi-wcaio-disabled');
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_payment_title_' + v + '"]').addClass('vi-wcaio-disabled');
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_order_payment_title_' + v + '"]').removeClass('vi-wcaio-disabled');
            });
        } else {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_order_payment_title"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_order_review_title"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_payment_title"]').removeClass('vi-wcaio-disabled');
            jQuery.each(languages, function (k, v) {
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_order_review_title_' + v + '"]').removeClass('vi-wcaio-disabled');
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_payment_title_' + v + '"]').removeClass('vi-wcaio-disabled');
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_order_payment_title_' + v + '"]').addClass('vi-wcaio-disabled');
            });
        }
    });
    if (jQuery('input:checkbox[name="woo_cart_all_in_one_params[sc_checkout_bt_btc_enable]"]').prop('checked')) {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_title"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_icon"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_bg_color"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_color"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_hover_bg_color"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_hover_color"]').removeClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_border_radius"]').removeClass('vi-wcaio-disabled');
        jQuery.each(languages, function (k, v) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_title_' + v + '"]').removeClass('vi-wcaio-disabled');
        });
    } else {
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_title"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_icon"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_bg_color"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_color"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_hover_bg_color"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_hover_color"]').addClass('vi-wcaio-disabled');
        jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_border_radius"]').addClass('vi-wcaio-disabled');
        jQuery.each(languages, function (k, v) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_title_' + v + '"]').addClass('vi-wcaio-disabled');
        });
    }
    jQuery('input:checkbox[name="woo_cart_all_in_one_params[sc_checkout_bt_btc_enable]"]').on('change', function () {
        if (jQuery(this).prop('checked')) {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_title"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_icon"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_bg_color"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_color"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_hover_bg_color"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_hover_color"]').removeClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_border_radius"]').removeClass('vi-wcaio-disabled');
            jQuery.each(languages, function (k, v) {
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_title_' + v + '"]').removeClass('vi-wcaio-disabled');
            });
        } else {
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_title"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_icon"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_bg_color"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_color"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_hover_bg_color"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_hover_color"]').addClass('vi-wcaio-disabled');
            jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_border_radius"]').addClass('vi-wcaio-disabled');
            jQuery.each(languages, function (k, v) {
                jQuery('li[id="customize-control-woo_cart_all_in_one_params-sc_checkout_bt_btc_title_' + v + '"]').addClass('vi-wcaio-disabled');
            });
        }
    });
    if (vi_wcaio_preview_setting.sc_checkout_enable) {
        jQuery('select[id="_customize-input-woo_cart_all_in_one_params[sc_footer_button]"]').val('checkout').trigger('change');
        jQuery('li#customize-control-woo_cart_all_in_one_params-sc_footer_button').addClass('vi-wcaio-disabled');
    }else {
        jQuery('#sub-accordion-section-vi_wcaio_design_checkout > li.vi-wcaio-disabled').addClass('vi-wcaio-disabled-1');
        jQuery('#sub-accordion-section-vi_wcaio_design_checkout > li:gt(1)').addClass('vi-wcaio-disabled');
    }
    jQuery('input:checkbox[name="woo_cart_all_in_one_params[sc_checkout_enable]"]').on('change', function () {
        if (jQuery(this).prop('checked')) {
            jQuery('select[id="_customize-input-woo_cart_all_in_one_params[sc_footer_button]"]').val('checkout').trigger('change');
            jQuery('li#customize-control-woo_cart_all_in_one_params-sc_footer_button').addClass('vi-wcaio-disabled');
            jQuery('#sub-accordion-section-vi_wcaio_design_checkout > li:gt(1)').removeClass('vi-wcaio-disabled');
            jQuery('#sub-accordion-section-vi_wcaio_design_checkout > li.vi-wcaio-disabled-1').removeClass('vi-wcaio-disabled-1').addClass('vi-wcaio-disabled');
        }else {
            jQuery('#sub-accordion-section-vi_wcaio_design_checkout > li.vi-wcaio-disabled').addClass('vi-wcaio-disabled-1');
            jQuery('#sub-accordion-section-vi_wcaio_design_checkout > li:gt(1)').addClass('vi-wcaio-disabled');
            jQuery('li#customize-control-woo_cart_all_in_one_params-sc_footer_button').removeClass('vi-wcaio-disabled');
        }
    });
}
