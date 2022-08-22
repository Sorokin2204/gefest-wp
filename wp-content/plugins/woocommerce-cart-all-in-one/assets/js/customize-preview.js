(function () {
    'use strict';
    let languages = vi_wcaio_preview.languages;
    wp.customize.bind('preview-ready', function () {
        wp.customize.preview.bind('vi_wcaio_update_url', function (url) {
            wp.customize.preview.send('vi_wcaio_update_url', url);
        });
        wp.customize.preview.bind('vi_wcaio_sb_update_url', function (product_id) {
            if (jQuery('body').hasClass('single')) {
                return false;
            }
            if (!product_id) {
                product_id = wp.customize('woo_cart_all_in_one_params[sb_select_product]').get()
            }
            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: vi_wcaio_preview.ajax_url,
                data: {
                    action: 'vi_wcaio_get_product_url',
                    product_id: product_id
                },
                success: function (response) {
                    if (response && response.status === 'success') {
                        wp.customize.preview.send('vi_wcaio_update_url', response.message);
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        });
        wp.customize.preview.bind('vi_wcaio_sc_toggle', function (action, new_effect) {
            if (action === 'show-checkout') {
                jQuery('.vi-wcaio-sidebar-cart-bt-nav-checkout').trigger('click');
            } else {
                jQuery('.vi-wcaio-sidebar-cart-wrap').data('cart_icon', wp.customize('woo_cart_all_in_one_params[sc_icon_enable]').get());
                vi_wcaio_sc_toggle(action, new_effect);
            }
        });
        wp.customize.preview.bind('active', function () {
            jQuery('.vi-wcaio-sidebar-cart-overlay, .vi-wcaio-sidebar-cart-close-wrap').on('click', function () {
                vi_wcaio_sc_toggle('hide');
            });
            jQuery('.vi-wcaio-sidebar-cart-icon-wrap').on('mouseenter', function () {
                if (jQuery(this).hasClass('vi-wcaio-sidebar-cart-icon-wrap-click')) {
                    jQuery(this).removeClass('vi-wcaio-sidebar-cart-icon-wrap-mouseleave').addClass('vi-wcaio-sidebar-cart-icon-wrap-mouseenter');
                } else {
                    vi_wcaio_sc_toggle('show');
                }
            }).on('mouseleave', function () {
                if (jQuery(this).hasClass('vi-wcaio-sidebar-cart-icon-wrap-mouseenter')) {
                    jQuery(this).removeClass('vi-wcaio-sidebar-cart-icon-wrap-mouseenter').addClass('vi-wcaio-sidebar-cart-icon-wrap-mouseleave');
                }
            }).on('click', function () {
                if (jQuery(this).hasClass('vi-wcaio-sidebar-cart-icon-wrap-click')) {
                    vi_wcaio_sc_toggle('show');
                }
            });
            //Set default of sidebar_cart
            if (jQuery('.vi-wcaio-sidebar-cart').length && wp.customize('woo_cart_all_in_one_params[sc_trigger_style]').get() !== jQuery('.vi-wcaio-sidebar-cart').data('effect')) {
                vi_wcaio_sc_toggle('start', wp.customize('woo_cart_all_in_one_params[sc_trigger_style]').get());
            }
            if (wp.customize('woo_cart_all_in_one_params[sc_icon_use_img]').get()) {
                jQuery('.vi-wcaio-sidebar-cart-icon').addClass('vi-wcaio-sidebar-cart-icon-image');
                jQuery('.vi-wcaio-sidebar-cart-icon-image').css('background-image', 'url(' + wp.customize('woo_cart_all_in_one_params[sc_icon_img]').get() + ')');
            }
            let cart_icon_count_html = jQuery('.vi-wcaio-sidebar-cart-icon-count-html').data('count_html') ?? {},
                sc_icon_count_type = wp.customize('woo_cart_all_in_one_params[sc_icon_count_type]').get();
            if (cart_icon_count_html[sc_icon_count_type]) {
                jQuery('.vi-wcaio-sidebar-cart-count-wrap').attr('class', 'vi-wcaio-sidebar-cart-count-wrap vi-wcaio-sidebar-cart-count-wrap-' + sc_icon_count_type);
                jQuery('.vi-wcaio-sidebar-cart-count').html(cart_icon_count_html[sc_icon_count_type]);
            }
            jQuery('.vi-wcaio-sidebar-cart-loading:not(.vi-wcaio-sidebar-cart-loading-' + wp.customize('woo_cart_all_in_one_params[sc_loading]').get() + ')').addClass('vi-wcaio-disabled');
            if (wp.customize('woo_cart_all_in_one_params[sc_header_coupon_enable]').get()) {
                jQuery('.vi-wcaio-sidebar-cart-header-coupon-wrap').removeClass('vi-wcaio-disabled');
            } else {
                jQuery('.vi-wcaio-sidebar-cart-header-coupon-wrap').addClass('vi-wcaio-disabled');
            }
            if (wp.customize('woo_cart_all_in_one_params[sc_pd_update_cart]').get()) {
                jQuery('.vi-wcaio-sidebar-cart-bt-update').addClass('vi-wcaio-disabled');
            } else {
                jQuery('.vi-wcaio-sidebar-cart-bt-update').removeClass('vi-wcaio-disabled');
            }
            //Set default of menu_cart
            if (wp.customize('woo_cart_all_in_one_params[mc_content]').get()) {
                jQuery('.vi-wcaio-menu-cart').addClass('vi-wcaio-menu-cart-show');
            } else {
                jQuery('.vi-wcaio-menu-cart').removeClass('vi-wcaio-menu-cart-show');
            }
            //Set default of sticky_atc
            if (wp.customize('woo_cart_all_in_one_params[sb_enable]').get()) {
                jQuery('.vi-wcaio-sb-container.vi-wcaio-sb-container-' + wp.customize('woo_cart_all_in_one_params[sb_template]').get()).removeClass('vi-wcaio-disabled');
            }
            if (wp.customize('woo_cart_all_in_one_params[sb_position]').get() == 0) {
                jQuery('.vi-wcaio-sb-container').addClass('vi-wcaio-sb-container-ps-0').removeClass('vi-wcaio-sb-container-ps-1');
            } else {
                jQuery('.vi-wcaio-sb-container').addClass('vi-wcaio-sb-container-ps-1').removeClass('vi-wcaio-sb-container-ps-0');
            }
            if (wp.customize('woo_cart_all_in_one_params[sb_always_appear]').get()) {
                jQuery('.vi-wcaio-sb-container').addClass('vi-wcaio-sb-container-always_appear');
            }
            if (wp.customize('woo_cart_all_in_one_params[sb_pd_review]').get()) {
                jQuery('.vi-wcaio-sb-product-rating-wrap').removeClass('vi-wcaio-disabled');
            } else {
                jQuery('.vi-wcaio-sb-product-rating-wrap').addClass('vi-wcaio-disabled');
            }
            if (wp.customize('woo_cart_all_in_one_params[sb_quantity]').get()) {
                jQuery('.vi-wcaio-sb-wrap .quantity').removeClass('vi-wcaio-disabled');
            } else {
                jQuery('.vi-wcaio-sb-wrap .quantity').addClass('vi-wcaio-disabled');
            }
            let sb_bt_atc_title = wp.customize('woo_cart_all_in_one_params[sb_bt_atc_title]').get();
            if (sb_bt_atc_title && sb_bt_atc_title.indexOf('{cart_icon}') >= 0) {
                let cart_icon = '';
                jQuery.ajax({
                    type: 'POST',
                    url: vi_wcaio_preview.ajax_url,
                    data: {
                        action: 'vi_wcaio_get_class_icon',
                        icon_id: wp.customize('woo_cart_all_in_one_params[sb_bt_atc_cart_icon]').get(),
                        type: 'cart_icons_atc',
                    },
                    success: function (response) {
                        if (response && response.status === 'success') {
                            cart_icon = '<i class="vi-wcaio-sb-bt-atc-cart_icons ' + response.message + '" ></i>';
                            sb_bt_atc_title = sb_bt_atc_title.replace(/{cart_icon}/g, cart_icon);
                            jQuery('.vi-wcaio-sb-container .vi-wcaio-product-bt-atc').html(sb_bt_atc_title);
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            } else {
                jQuery('.vi-wcaio-sb-container .vi-wcaio-product-bt-atc').html(sb_bt_atc_title);
            }
            //set default of checkout form
            jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_enable]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                vi_wcaio_sc_toggle('show');
            } else {
                vi_wcaio_sc_icon_may_be_toggle();
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_display_type]', function (value) {
        value.bind(function (newval) {
            let wrap = jQuery('.vi-wcaio-sidebar-cart');
            let oldval = wrap.data('type');
            wrap.removeClass('vi-wcaio-sidebar-cart-' + oldval).addClass('vi-wcaio-sidebar-cart-' + newval);
            wrap.removeClass('vi-wcaio-sidebar-cart-init');
            wrap.data('type', newval);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_position]', function (value) {
        value.bind(function (newval) {
            let wrap = jQuery('.vi-wcaio-sidebar-cart');
            let oldval = wrap.data('position');
            wrap.removeClass('vi-wcaio-sidebar-cart-' + oldval).addClass('vi-wcaio-sidebar-cart-' + newval);
            wrap.data('position', newval);
            wrap.data('old_position', oldval);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_horizontal]', function (value) {
        value.bind(function (newval) {
            let sc_horizontal_mobile = parseInt(newval) > 20 ? 20 - parseInt(newval) : 0;
            let css = '\n' +
                '            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_left,\n' +
                '            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_left{\n' +
                '                left: ' + newval + 'px ;\n' +
                '            }\n' +
                '            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_right,\n' +
                '            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_right{\n' +
                '                right: ' + newval + 'px ;\n' +
                '            }\n' +
                '            @media screen and (max-width: 768px) {\n' +
                '                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_left .vi-wcaio-sidebar-cart-content-wrap,\n' +
                '                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_left .vi-wcaio-sidebar-cart-content-wrap{\n' +
                '                    left: ' + sc_horizontal_mobile + 'px  ;\n' +
                '                }\n' +
                '                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_right .vi-wcaio-sidebar-cart-content-wrap,\n' +
                '                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_right .vi-wcaio-sidebar-cart-content-wrap\n' +
                '                    right: ' + sc_horizontal_mobile + 'px  ;\n' +
                '                }\n' +
                '            }';
            jQuery('#vi-wcaio-preview-sc_horizontal').html(css);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_vertical]', function (value) {
        value.bind(function (newval) {
            let sc_vertical_mobile = parseInt(newval) > 20 ? 20 - parseInt(newval) : 0;
            let css = '\n' +
                '            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_left,\n' +
                '            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_right{\n' +
                '                top: ' + newval + 'px ;\n' +
                '            }\n' +
                '            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_right,\n' +
                '            .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_left{\n' +
                '                bottom: ' + newval + 'px ;\n' +
                '            }\n' +
                '            @media screen and (max-width: 768px) {\n' +
                '                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_left .vi-wcaio-sidebar-cart-content-wrap,\n' +
                '                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-top_right .vi-wcaio-sidebar-cart-content-wrap{\n' +
                '                    top: ' + sc_vertical_mobile + 'px ;\n' +
                '                }\n' +
                '                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_right .vi-wcaio-sidebar-cart-content-wrap,\n' +
                '                .vi-wcaio-sidebar-cart.vi-wcaio-sidebar-cart-1.vi-wcaio-sidebar-cart-bottom_left .vi-wcaio-sidebar-cart-content-wrap{\n' +
                '                    bottom: ' + sc_vertical_mobile + 'px ;\n' +
                '                }\n' +
                '            }';
            jQuery('#vi-wcaio-preview-sc_vertical').html(css);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_trigger_type]', function (value) {
        value.bind(function (newval) {
            let wrap = jQuery('.vi-wcaio-sidebar-cart-icon-wrap');
            let oldval = wrap.data('trigger');
            wrap.removeClass('vi-wcaio-sidebar-cart-icon-wrap-mouseenter vi-wcaio-sidebar-cart-icon-wrap-mouseleave vi-wcaio-sidebar-cart-icon-wrap-' + oldval)
                .addClass('vi-wcaio-sidebar-cart-icon-wrap-' + newval);
            wrap.data('trigger', newval);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_icon_enable]', function (value) {
        value.bind(function (newval) {
            jQuery('.vi-wcaio-sidebar-cart-wrap').data('cart_icon', newval ? 1:'');
            if (newval) {
                vi_wcaio_sc_icon_may_be_toggle(jQuery('.vi-wcaio-sidebar-cart-wrap'), true);
            } else {
                vi_wcaio_sc_icon_may_be_toggle();
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_icon_position]', function (value) {
        value.bind(function (newval) {
            let wrap = jQuery('.vi-wcaio-sidebar-cart-icon-wrap');
            let oldval = wrap.data('position');
            wrap.removeClass('vi-wcaio-sidebar-cart-icon-wrap-' + oldval).addClass('vi-wcaio-sidebar-cart-icon-wrap-' + newval);
            wrap.data('position', newval);
            wrap.data('old_position', oldval);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_icon_horizontal]', function (value) {
        value.bind(function (newval) {
            let css = '.vi-wcaio-sidebar-cart-icon-wrap-top_left, .vi-wcaio-sidebar-cart-icon-wrap-bottom_left{left:' + newval + 'px;}' + '\n' +
                '.vi-wcaio-sidebar-cart-icon-wrap-top_right, .vi-wcaio-sidebar-cart-icon-wrap-bottom_right {right: ' + newval + 'px;}';
            jQuery('#vi-wcaio-preview-sc_icon_horizontal').html(css);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_icon_vertical]', function (value) {
        value.bind(function (newval) {
            let css = '.vi-wcaio-sidebar-cart-icon-wrap-top_left, .vi-wcaio-sidebar-cart-icon-wrap-top_right{top:' + newval + 'px;}' + '\n' +
                '.vi-wcaio-sidebar-cart-icon-wrap-bottom_left, .vi-wcaio-sidebar-cart-icon-wrap-bottom_right{bottom: ' + newval + 'px;}';
            jQuery('#vi-wcaio-preview-sc_icon_vertical').html(css);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_icon_style]', function (value) {
        value.bind(function (newval) {
            let wrap = jQuery('.vi-wcaio-sidebar-cart-icon');
            let oldval = wrap.data('display_style');
            wrap.removeClass('vi-wcaio-sidebar-cart-icon-' + oldval).addClass('vi-wcaio-sidebar-cart-icon-' + newval);
            wrap.data('display_style', newval);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_icon_box_shadow]', function (value) {
        value.bind(function (newval) {
            let css = '';
            if (newval) {
                css = '.vi-wcaio-sidebar-cart-icon-wrap{\n' +
                    '                box-shadow: inset 0 0 2px rgba(0,0,0,0.03), 0 4px 10px rgba(0,0,0,0.17);\n' +
                    '            }';
            }
            jQuery('#vi-wcaio-preview-sc_icon_box_shadow').html(css);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_icon_scale]', function (value) {
        value.bind(function (newval) {
            let css = '.vi-wcaio-sidebar-cart-icon-wrap {\n' +
                '                transform: scale(' + newval + ') ;\n' +
                '            }\n' +
                '            @keyframes vi-wcaio-cart-icon-slide_in_left {\n' +
                '                from {\n' +
                '                    transform: translate3d(-100%, 0, 0) scale(' + newval + ');\n' +
                '                    visibility: hidden;\n' +
                '                }\n' +
                '                to {\n' +
                '                    transform: translate3d(0, 0, 0) scale(' + newval + ');\n' +
                '                }\n' +
                '            }\n' +
                '            @keyframes vi-wcaio-cart-icon-slide_in_right {\n' +
                '                from {\n' +
                '                    transform: translate3d(100%, 0, 0) scale(' + newval + ');\n' +
                '                    visibility: hidden;\n' +
                '                }\n' +
                '                to {\n' +
                '                    transform: translate3d(0, 0, 0) scale(' + newval + ');\n' +
                '                }\n' +
                '            }';
            jQuery('#vi-wcaio-preview-sc_icon_scale').html(css);
            let sc_icon_hover_scale = wp.customize('woo_cart_all_in_one_params[sc_icon_hover_scale]').get();
            let css1 = '\n' +
                '            @keyframes vi-wcaio-cart-icon-mouseenter {\n' +
                '                from {\n' +
                '                    transform: translate3d(0, 0, 0) scale(' + newval + ');\n' +
                '                }\n' +
                '                to {\n' +
                '                    transform: translate3d(0, 0, 0) scale(' + sc_icon_hover_scale + ');\n' +
                '                }\n' +
                '            }\n' +
                '            @keyframes vi-wcaio-cart-icon-mouseleave {\n' +
                '                from {\n' +
                '                    transform: translate3d(0, 0, 0) scale(' + sc_icon_hover_scale + ');\n' +
                '                }\n' +
                '                to {\n' +
                '                    transform: translate3d(0, 0, 0) scale(' + newval + ');\n' +
                '                }\n' +
                '            }\n' +
                '            @keyframes vi-wcaio-cart-icon-slide_out_left {\n' +
                '                from {\n' +
                '                    transform: translate3d(0, 0, 0) scale(' + sc_icon_hover_scale + ');\n' +
                '                    visibility: visible;\n' +
                '                    opacity: 1;\n' +
                '                }\n' +
                '                to {\n' +
                '                    transform: translate3d(-100%, 0, 0) scale(' + sc_icon_hover_scale + ');\n' +
                '                    visibility: hidden;\n' +
                '                    opacity: 0;\n' +
                '                }\n' +
                '            }\n' +
                '            @keyframes vi-wcaio-cart-icon-slide_out_right {\n' +
                '                from {\n' +
                '                    transform: translate3d(0, 0, 0) scale(' + sc_icon_hover_scale + ');\n' +
                '                    visibility: visible;\n' +
                '                    opacity: 1;\n' +
                '                }\n' +
                '                to {\n' +
                '                    transform: translate3d(100%, 0, 0) scale(' + sc_icon_hover_scale + ');\n' +
                '                    visibility: hidden;\n' +
                '                    opacity: 0;\n' +
                '                }\n' +
                '            }';
            jQuery('#vi-wcaio-preview-sc_icon_hover_scale').html(css1);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_icon_hover_scale]', function (value) {
        value.bind(function (newval) {
            let sc_icon_scale = wp.customize('woo_cart_all_in_one_params[sc_icon_scale]').get();
            let css = '\n' +
                '            @keyframes vi-wcaio-cart-icon-mouseenter {\n' +
                '                from {\n' +
                '                    transform: translate3d(0, 0, 0) scale(' + sc_icon_scale + ');\n' +
                '                }\n' +
                '                to {\n' +
                '                    transform: translate3d(0, 0, 0) scale(' + newval + ');\n' +
                '                }\n' +
                '            }\n' +
                '            @keyframes vi-wcaio-cart-icon-mouseleave {\n' +
                '                from {\n' +
                '                    transform: translate3d(0, 0, 0) scale(' + newval + ');\n' +
                '                }\n' +
                '                to {\n' +
                '                    transform: translate3d(0, 0, 0) scale(' + sc_icon_scale + ');\n' +
                '                }\n' +
                '            }\n' +
                '            @keyframes vi-wcaio-cart-icon-slide_out_left {\n' +
                '                from {\n' +
                '                    transform: translate3d(0, 0, 0) scale(' + newval + ');\n' +
                '                    visibility: visible;\n' +
                '                    opacity: 1;\n' +
                '                }\n' +
                '                to {\n' +
                '                    transform: translate3d(-100%, 0, 0) scale(' + newval + ');\n' +
                '                    visibility: hidden;\n' +
                '                    opacity: 0;\n' +
                '                }\n' +
                '            }\n' +
                '            @keyframes vi-wcaio-cart-icon-slide_out_right {\n' +
                '                from {\n' +
                '                    transform: translate3d(0, 0, 0) scale(' + newval + ');\n' +
                '                    visibility: visible;\n' +
                '                    opacity: 1;\n' +
                '                }\n' +
                '                to {\n' +
                '                    transform: translate3d(100%, 0, 0) scale(' + newval + ');\n' +
                '                    visibility: hidden;\n' +
                '                    opacity: 0;\n' +
                '                }\n' +
                '            }';
            jQuery('#vi-wcaio-preview-sc_icon_hover_scale').html(css);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_icon_use_img]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                jQuery('.vi-wcaio-sidebar-cart-icon').addClass('vi-wcaio-sidebar-cart-icon-image');
                let img_url = wp.customize('woo_cart_all_in_one_params[sc_icon_img]').get();
                jQuery('.vi-wcaio-sidebar-cart-icon-image').css('background-image', 'url(' + img_url + ')');
            } else {
                jQuery('.vi-wcaio-sidebar-cart-icon').removeClass('vi-wcaio-sidebar-cart-icon-image').css('background-image', 'none');
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_icon_img]', function (value) {
        value.bind(function (newval) {
            jQuery('.vi-wcaio-sidebar-cart-icon-image').css('background-image', 'url(' + newval + ')');
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_icon_default_icon]', function (value) {
        value.bind(function (newval) {
            jQuery.ajax({
                type: 'POST',
                url: vi_wcaio_preview.ajax_url,
                data: {
                    action: 'vi_wcaio_get_class_icon',
                    icon_id: newval,
                    type: 'cart_icons',
                },
                success: function (response) {
                    if (response && response.status === 'success') {
                        jQuery('.vi-wcaio-sidebar-cart-icon i').attr('class', response.message);
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_icon_count_type]', function (value) {
        value.bind(function (newval) {
            let old_type = jQuery('.vi-wcaio-sidebar-cart-count-html').data('type');
            if (newval === old_type) {
                return false;
            }
            let cart_icon_count_html = jQuery('.vi-wcaio-sidebar-cart-icon-count-html').data('count_html') ?? {};
            if (cart_icon_count_html[newval]) {
                jQuery('.vi-wcaio-sidebar-cart-count-wrap').attr('class', 'vi-wcaio-sidebar-cart-count-wrap vi-wcaio-sidebar-cart-count-wrap-' + newval);
                jQuery('.vi-wcaio-sidebar-cart-count').html(cart_icon_count_html[newval]);
                jQuery('.vi-wcaio-sidebar-cart-count-html').data('type', newval)
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_header_title]', function (value) {
        value.bind(function (newval) {
            jQuery('.vi-wcaio-sidebar-cart-header-title-wrap').html(newval);
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_cart_all_in_one_params[sc_header_title_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.vi-wcaio-sidebar-cart-header-title-wrap').html(newval);
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_header_coupon_enable]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                jQuery('.vi-wcaio-sidebar-cart-header-coupon-wrap').removeClass('vi-wcaio-disabled');
            } else {
                jQuery('.vi-wcaio-sidebar-cart-header-coupon-wrap').addClass('vi-wcaio-disabled');
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_footer_coupon]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                jQuery('.vi-wcaio-sidebar-cart-footer-coupons').removeClass('vi-wcaio-disabled');
            } else {
                jQuery('.vi-wcaio-sidebar-cart-footer-coupons').addClass('vi-wcaio-disabled');
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_footer_cart_total]', function (value) {
        value.bind(function (newval) {
            jQuery('.vi-wcaio-sidebar-cart-footer-cart_total').addClass('vi-wcaio-disabled');
            jQuery('.vi-wcaio-sidebar-cart-footer-cart_total.vi-wcaio-sidebar-cart-footer-' + newval).removeClass('vi-wcaio-disabled');
            jQuery('.vi-wcaio-sidebar-cart-footer-cart_total1').each(function (k, v) {
                jQuery(v).html(jQuery(v).parent().data('cart_total'));
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_footer_cart_total_text]', function (value) {
        value.bind(function (newval) {
            jQuery('.vi-wcaio-sidebar-cart-footer-cart_total .vi-wcaio-sidebar-cart-footer-cart_total-title').html(newval);
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_cart_all_in_one_params[sc_footer_cart_total_text_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.vi-wcaio-sidebar-cart-footer-cart_total .vi-wcaio-sidebar-cart-footer-cart_total-title').html(newval);
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_footer_button]', function (value) {
        value.bind(function (newval) {
            jQuery('.vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt-nav').addClass('vi-wcaio-disabled');
            jQuery('.vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt-nav-' + newval).removeClass('vi-wcaio-disabled');
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_footer_bt_cart_text]', function (value) {
        value.bind(function (newval) {
            jQuery('.vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt-nav-cart').html(newval);
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_cart_all_in_one_params[sc_footer_bt_cart_text_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt-nav-cart').html(newval);
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_footer_bt_checkout_text]', function (value) {
        value.bind(function (newval) {
            jQuery('.vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt-nav-checkout').html(newval);
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_cart_all_in_one_params[sc_footer_bt_checkout_text_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt-nav-checkout').html(newval);
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_footer_message]', function (value) {
        value.bind(function (newval) {
            let data = {
                action: 'vi_wcaio_get_sc_footer_message_html',
                custom_message: newval
            };
            jQuery('.vi-wcaio-sidebar-cart-footer-message-wrap').html('');
            if (newval) {
                jQuery.ajax({
                    type: 'POST',
                    dataType: "json",
                    url: vi_wcaio_preview.ajax_url,
                    data: data,
                    success: function (response) {
                        if (response && response.status === 'success') {
                            jQuery('.vi-wcaio-sidebar-cart-footer-message-wrap').html(response.message);
                            jQuery('.vi-wcaio-sidebar-cart-footer-pd-wrap-wrap:not(.vi-wcaio-slide-init)').each(function () {
                                vi_wcaio_sc_flexslider(jQuery(this));
                            });
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            }
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_cart_all_in_one_params[sc_footer_message_' + v + ']', function (value) {
            value.bind(function (newval) {
                let data = {
                    action: 'vi_wcaio_get_sc_footer_message_html',
                    custom_message: newval
                };
                jQuery('.vi-wcaio-sidebar-cart-footer-message-wrap').html('');
                if (newval) {
                    jQuery.ajax({
                        type: 'POST',
                        dataType: "json",
                        url: vi_wcaio_preview.ajax_url,
                        data: data,
                        success: function (response) {
                            if (response && response.status === 'success') {
                                jQuery('.vi-wcaio-sidebar-cart-footer-message-wrap').html(response.message);
                                jQuery('.vi-wcaio-sidebar-cart-footer-pd-wrap-wrap:not(.vi-wcaio-slide-init)').each(function () {
                                    vi_wcaio_sc_flexslider(jQuery(this));
                                });
                            }
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });
                }
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_footer_pd_plus]', function (value) {
        value.bind(function (newval) {
            let data;
            jQuery('.vi-wcaio-sidebar-cart-footer-pd-wrap-wrap').html('');
            if (newval === 'select_cat') {
                data = {
                    action: 'vi_wcaio_get_sc_footer_pd_plus_html',
                    vicaio_selected_cats: wp.customize('woo_cart_all_in_one_params[sc_footer_pd_plus_cats]').get(),
                    type: newval
                };
            } else if (newval === 'select_pd') {
                data = {
                    action: 'vi_wcaio_get_sc_footer_pd_plus_html',
                    vicaio_selected_pd: wp.customize('woo_cart_all_in_one_params[sc_footer_pd_plus_products]').get(),
                    type: newval
                };
            } else if (newval) {
                data = {
                    action: 'vi_wcaio_get_sc_footer_pd_plus_html',
                    type: newval,
                };
            }
            if (data) {
                jQuery.ajax({
                    type: 'POST',
                    dataType: "json",
                    url: vi_wcaio_preview.ajax_url,
                    data: data,
                    success: function (response) {
                        if (response && response.status === 'success') {
                            jQuery('.vi-wcaio-sidebar-cart-footer-pd-wrap-wrap').replaceWith(response.message);
                            jQuery('.vi-wcaio-sidebar-cart-footer-pd-wrap-wrap:not(.vi-wcaio-disabled):not(.vi-wcaio-slide-init)').each(function () {
                                jQuery(this).find('.vi-wcaio-sidebar-cart-footer-pd-plus-title').html(wp.customize('woo_cart_all_in_one_params[sc_footer_pd_plus_title]').get());
                                vi_wcaio_sc_flexslider(jQuery(this));
                            });
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_footer_pd_plus_cats]', function (value) {
        value.bind(function (newval) {
            jQuery('.vi-wcaio-sidebar-cart-footer-pd-wrap-wrap').html('');
            if (newval) {
                let data = {
                    action: 'vi_wcaio_get_sc_footer_pd_plus_html',
                    type: 'select_cat',
                    vicaio_selected_cats: newval
                };
                jQuery.ajax({
                    type: 'POST',
                    dataType: "json",
                    url: vi_wcaio_preview.ajax_url,
                    data: data,
                    success: function (response) {
                        if (response && response.status === 'success') {
                            jQuery('.vi-wcaio-sidebar-cart-footer-pd-wrap-wrap').replaceWith(response.message);
                            jQuery('.vi-wcaio-sidebar-cart-footer-pd-wrap-wrap:not(.vi-wcaio-disabled):not(.vi-wcaio-slide-init)').each(function () {
                                jQuery(this).find('.vi-wcaio-sidebar-cart-footer-pd-plus-title').html(wp.customize('woo_cart_all_in_one_params[sc_footer_pd_plus_title]').get());
                                vi_wcaio_sc_flexslider(jQuery(this));
                            });
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_footer_pd_plus_products]', function (value) {
        value.bind(function (newval) {
            jQuery('.vi-wcaio-sidebar-cart-footer-pd-wrap-wrap').html('');
            if (newval) {
                let data = {
                    action: 'vi_wcaio_get_sc_footer_pd_plus_html',
                    type: 'select_pd',
                    vicaio_selected_pd: newval
                };
                jQuery.ajax({
                    type: 'POST',
                    dataType: "json",
                    url: vi_wcaio_preview.ajax_url,
                    data: data,
                    success: function (response) {
                        if (response && response.status === 'success') {
                            jQuery('.vi-wcaio-sidebar-cart-footer-pd-wrap-wrap').replaceWith(response.message);
                            jQuery('.vi-wcaio-sidebar-cart-footer-pd-wrap-wrap:not(.vi-wcaio-disabled):not(.vi-wcaio-slide-init)').each(function () {
                                jQuery(this).find('.vi-wcaio-sidebar-cart-footer-pd-plus-title').html(wp.customize('woo_cart_all_in_one_params[sc_footer_pd_plus_title]').get());
                                vi_wcaio_sc_flexslider(jQuery(this));
                            });
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_footer_pd_plus_title]', function (value) {
        value.bind(function (newval) {
            jQuery('.vi-wcaio-sidebar-cart-footer-pd-wrap-wrap .vi-wcaio-sidebar-cart-footer-pd-plus-title').html(newval);
            jQuery('.vi-wcaio-sidebar-cart').removeClass('vi-wcaio-sidebar-cart-init');
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_cart_all_in_one_params[sc_footer_pd_plus_title_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.vi-wcaio-sidebar-cart-footer-pd-wrap-wrap .vi-wcaio-sidebar-cart-footer-pd-plus-title').html(newval);
                jQuery('.vi-wcaio-sidebar-cart').removeClass('vi-wcaio-sidebar-cart-init');
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_title]', function (value) {
        value.bind(function (newval) {
            if (newval && newval.indexOf('{cart_icon}') >= 0) {
                let cart_icon = '';
                jQuery.ajax({
                    type: 'POST',
                    url: vi_wcaio_preview.ajax_url,
                    data: {
                        action: 'vi_wcaio_get_class_icon',
                        icon_id: wp.customize('woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_cart_icon]').get(),
                        type: 'cart_icons_atc',
                    },
                    success: function (response) {
                        if (response && response.status === 'success') {
                            cart_icon = '<i class="vi-wcaio-pd_plus-bt-atc-cart_icons ' + response.message + '" ></i>';
                            newval = newval.replace(/{cart_icon}/g, cart_icon);
                            jQuery('.vi-wcaio-sidebar-cart-footer-pd.vi-wcaio-sidebar-cart-footer-pd-type-1 .vi-wcaio-pd_plus-product-bt-atc').html(newval);
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            } else {
                jQuery('.vi-wcaio-sidebar-cart-footer-pd.vi-wcaio-sidebar-cart-footer-pd-type-1 .vi-wcaio-pd_plus-product-bt-atc').html(newval);
            }
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_title_' + v + ']', function (value) {
            value.bind(function (newval) {
                if (newval && newval.indexOf('{cart_icon}') >= 0) {
                    let cart_icon = '';
                    jQuery.ajax({
                        type: 'POST',
                        url: vi_wcaio_preview.ajax_url,
                        data: {
                            action: 'vi_wcaio_get_class_icon',
                            icon_id: wp.customize('woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_cart_icon]').get(),
                            type: 'cart_icons_atc',
                        },
                        success: function (response) {
                            if (response && response.status === 'success') {
                                cart_icon = '<i class="vi-wcaio-pd_plus-bt-atc-cart_icons ' + response.message + '" ></i>';
                                newval = newval.replace(/{cart_icon}/g, cart_icon);
                                jQuery('.vi-wcaio-sidebar-cart-footer-pd.vi-wcaio-sidebar-cart-footer-pd-type-1 .vi-wcaio-pd_plus-product-bt-atc').html(newval);
                            }
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });
                } else {
                    jQuery('.vi-wcaio-sidebar-cart-footer-pd.vi-wcaio-sidebar-cart-footer-pd-type-1 .vi-wcaio-pd_plus-product-bt-atc').html(newval);
                }
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_footer_pd_plus_bt_atc_cart_icon]', function (value) {
        value.bind(function (newval) {
            jQuery.ajax({
                type: 'POST',
                url: vi_wcaio_preview.ajax_url,
                data: {
                    action: 'vi_wcaio_get_class_icon',
                    icon_id: newval,
                    type: 'cart_icons_atc',
                },
                success: function (response) {
                    if (response && response.status === 'success') {
                        jQuery('.vi-wcaio-sidebar-cart-footer-pd.vi-wcaio-sidebar-cart-footer-pd-type-1 .vi-wcaio-pd_plus-product-bt-atc i.vi-wcaio-pd_plus-bt-atc-cart_icons').attr('class', 'vi-wcaio-pd_plus-bt-atc-cart_icons ' + response.message);
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_pd_update_cart]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                jQuery('.vi-wcaio-sidebar-cart-bt-update').addClass('vi-wcaio-disabled');
            } else {
                jQuery('.vi-wcaio-sidebar-cart-bt-update').removeClass('vi-wcaio-disabled');
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_pd_img_box_shadow]', function (value) {
        value.bind(function (newval) {
            let css = '';
            if (newval) {
                css = '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-img-wrap img{\n' +
                    '                box-shadow: 0 4px 10px rgba(0,0,0,0.07);\n' +
                    '            }';
            }
            jQuery('#vi-wcaio-preview-sc_pd_img_box_shadow').html(css);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_pd_price_style]', function (value) {
        value.bind(function (newval) {
            if (!jQuery('.vi-wcaio-sidebar-cart-pd-empty').length) {
                jQuery.ajax({
                    type: 'POST',
                    url: vi_wcaio_preview.ajax_url,
                    data: {
                        action: 'vi_wcaio_change_sc_pd_price_style',
                        style: newval,
                    },
                    beforeSend: function () {
                        jQuery('.vi-wcaio-sidebar-cart-wrap').find(' .vi-wcaio-sidebar-cart-loading-wrap').removeClass('vi-wcaio-disabled');
                    },
                    success: function (response) {
                        if (response && response.status === 'success') {
                            jQuery('.vi-wcaio-sidebar-cart-products').html(response.message);
                        }
                    },
                    complete: function () {
                        jQuery('.vi-wcaio-sidebar-cart-wrap').find(' .vi-wcaio-sidebar-cart-loading-wrap').addClass('vi-wcaio-disabled');
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_pd_delete_icon]', function (value) {
        value.bind(function (newval) {
            jQuery.ajax({
                type: 'POST',
                url: vi_wcaio_preview.ajax_url,
                data: {
                    action: 'vi_wcaio_get_class_icon',
                    icon_id: newval,
                    type: 'delete_icons',
                },
                success: function (response) {
                    if (response && response.status === 'success') {
                        jQuery('.vi-wcaio-sidebar-cart-pd-remove-wrap i').attr('class', response.message);
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[mc_enable]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                jQuery('.vi-wcaio-menu-cart').removeClass('vi-wcaio-disabled');
            } else {
                jQuery('.vi-wcaio-menu-cart').addClass('vi-wcaio-disabled');
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[mc_content]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                jQuery('.vi-wcaio-menu-cart').addClass('vi-wcaio-menu-cart-show');
            } else {
                jQuery('.vi-wcaio-menu-cart').removeClass('vi-wcaio-menu-cart-show');
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[mc_icon]', function (value) {
        value.bind(function (newval) {
            jQuery.ajax({
                type: 'POST',
                url: vi_wcaio_preview.ajax_url,
                data: {
                    action: 'vi_wcaio_get_class_icon',
                    icon_id: newval,
                    type: 'cart_icons',
                },
                success: function (response) {
                    if (response && response.status === 'success') {
                        jQuery('.vi-wcaio-menu-cart-icon i').attr('class', response.message);
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[mc_display_style]', function (value) {
        value.bind(function (newval) {
            jQuery.ajax({
                type: 'POST',
                url: vi_wcaio_preview.ajax_url,
                data: {
                    action: 'vi_wcaio_get_menu_cart_text',
                    display_type: newval,
                    cart_total_type: wp.customize('woo_cart_all_in_one_params[mc_cart_total]').get(),
                },
                success: function (response) {
                    if (response && response.status === 'success') {
                        jQuery('.vi-wcaio-menu-cart-text-wrap').html(response.message);
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[mc_cart_total]', function (value) {
        value.bind(function (newval) {
            jQuery.ajax({
                type: 'POST',
                url: vi_wcaio_preview.ajax_url,
                data: {
                    action: 'vi_wcaio_get_menu_cart_text',
                    display_type: wp.customize('woo_cart_all_in_one_params[mc_display_style]').get(),
                    cart_total_type: newval,
                },
                success: function (response) {
                    if (response && response.status === 'success') {
                        jQuery('.vi-wcaio-menu-cart-text-wrap').html(response.message);
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sb_enable]', function (value) {
        value.bind(function (newval) {
            if (!newval) {
                jQuery('.vi-wcaio-sb-container').addClass('vi-wcaio-disabled');
            } else {
                jQuery('.vi-wcaio-sb-container.vi-wcaio-sb-container-' + wp.customize('woo_cart_all_in_one_params[sb_template]').get()).removeClass('vi-wcaio-disabled');
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[sb_always_appear]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                jQuery('.vi-wcaio-sb-container').addClass('vi-wcaio-sb-container-always_appear');
            } else {
                jQuery('.vi-wcaio-sb-container').removeClass('vi-wcaio-sb-container-always_appear');
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[sb_select_product]', function (value) {
        value.bind(function (newval) {
            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: vi_wcaio_preview.ajax_url,
                data: {
                    action: 'vi_wcaio_get_product_url',
                    product_id: newval
                },
                success: function (response) {
                    if (response && response.status === 'success') {
                        wp.customize.preview.send('vi_wcaio_update_url', response.message);
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sb_template]', function (value) {
        value.bind(function (newval) {
            jQuery('.vi-wcaio-sb-container').addClass('vi-wcaio-disabled');
            jQuery('.vi-wcaio-sb-container.vi-wcaio-sb-container-' + newval).removeClass('vi-wcaio-disabled');
        });
    });
    wp.customize('woo_cart_all_in_one_params[sb_position]', function (value) {
        value.bind(function (newval) {
            let remove = 'vi-wcaio-sb-container-ps-0', add = 'vi-wcaio-sb-container-ps-1';
            if (newval === '0') {
                add = 'vi-wcaio-sb-container-ps-0';
                remove = 'vi-wcaio-sb-container-ps-1';
            }
            jQuery('.vi-wcaio-sb-container').addClass(add).removeClass(remove);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sb_quantity]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                jQuery('.vi-wcaio-sb-container .quantity').removeClass('vi-wcaio-disabled');
            } else {
                jQuery('.vi-wcaio-sb-container .quantity').addClass('vi-wcaio-disabled');
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[sb_bt_atc_title]', function (value) {
        value.bind(function (newval) {
            if (newval && newval.indexOf('{cart_icon}') >= 0) {
                let cart_icon = '';
                jQuery.ajax({
                    type: 'POST',
                    url: vi_wcaio_preview.ajax_url,
                    data: {
                        action: 'vi_wcaio_get_class_icon',
                        icon_id: wp.customize('woo_cart_all_in_one_params[sb_bt_atc_cart_icon]').get(),
                        type: 'cart_icons_atc',
                    },
                    success: function (response) {
                        if (response && response.status === 'success') {
                            cart_icon = '<i class="vi-wcaio-sb-bt-atc-cart_icons ' + response.message + '" ></i>';
                            newval = newval.replace(/{cart_icon}/g, cart_icon);
                            jQuery('.vi-wcaio-sb-container .vi-wcaio-product-bt-atc').html(newval);
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            } else {
                jQuery('.vi-wcaio-sb-container .vi-wcaio-product-bt-atc').html(newval);
            }
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_cart_all_in_one_params[sb_bt_atc_title_' + v + ']', function (value) {
            value.bind(function (newval) {
                if (newval && newval.indexOf('{cart_icon}') >= 0) {
                    let cart_icon = '';
                    jQuery.ajax({
                        type: 'POST',
                        url: vi_wcaio_preview.ajax_url,
                        data: {
                            action: 'vi_wcaio_get_class_icon',
                            icon_id: wp.customize('woo_cart_all_in_one_params[sb_bt_atc_cart_icon]').get(),
                            type: 'cart_icons_atc',
                        },
                        success: function (response) {
                            if (response && response.status === 'success') {
                                cart_icon = '<i class="vi-wcaio-sb-bt-atc-cart_icons ' + response.message + '" ></i>';
                                newval = newval.replace(/{cart_icon}/g, cart_icon);
                                jQuery('.vi-wcaio-sb-container .vi-wcaio-product-bt-atc').html(newval);
                            }
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });
                } else {
                    jQuery('.vi-wcaio-sb-container .vi-wcaio-product-bt-atc').html(newval);
                }
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sb_bt_atc_cart_icon]', function (value) {
        value.bind(function (newval) {
            jQuery.ajax({
                type: 'POST',
                url: vi_wcaio_preview.ajax_url,
                data: {
                    action: 'vi_wcaio_get_class_icon',
                    icon_id: newval,
                    type: 'cart_icons_atc',
                },
                success: function (response) {
                    if (response && response.status === 'success') {
                        jQuery('.vi-wcaio-sb-container .vi-wcaio-product-bt-atc i.vi-wcaio-sb-bt-atc-cart_icons').attr('class', 'vi-wcaio-sb-bt-atc-cart_icons ' + response.message);
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_trigger_style]', function (value) {
        value.bind(function (newval) {
            if (!wp.customize('woo_cart_all_in_one_params[sc_enable]').get()) {
                return false;
            }
            if (jQuery('.vi-wcaio-sidebar-cart-content-wrap').hasClass('vi-wcaio-sidebar-cart-content-open')) {
                vi_wcaio_sc_toggle('hide');
                setTimeout(function () {
                    vi_wcaio_sc_toggle('show', newval);
                }, 1000);
            } else {
                vi_wcaio_sc_toggle('show', newval);
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_loading]', function (value) {
        value.bind(function (newval) {
            if (!wp.customize('woo_cart_all_in_one_params[sc_enable]').get()) {
                return false;
            }
            vi_wcaio_sc_toggle('show', wp.customize('woo_cart_all_in_one_params[sc_trigger_style]').get());
            jQuery('.vi-wcaio-sidebar-cart-loading').addClass('vi-wcaio-disabled');
            jQuery('.vi-wcaio-sidebar-cart-loading.vi-wcaio-sidebar-cart-loading-' + newval).removeClass('vi-wcaio-disabled');
            jQuery('.vi-wcaio-sidebar-cart-loading-wrap').removeClass('vi-wcaio-disabled');
            setTimeout(function () {
                jQuery('.vi-wcaio-sidebar-cart-loading-wrap').addClass('vi-wcaio-disabled');
            }, 5000);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_loading_color]', function (value) {
        value.bind(function (newval) {
            if (!wp.customize('woo_cart_all_in_one_params[sc_enable]').get()) {
                return false;
            }
            let css = '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-dual_ring:after {\n' +
                '                border-color: ' + newval + '  transparent ' + newval + '  transparent;\n' +
                '            }\n' +
                '            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-ring div{\n' +
                '                border-color: ' + newval + '  transparent transparent transparent;\n' +
                '            }\n' +
                '            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-ripple  div{\n' +
                '                border: 4px solid ' + newval + ' ;\n' +
                '            }\n' +
                '            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-default div,\n' +
                '            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-animation_face_1 div,\n' +
                '            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-animation_face_2 div,\n' +
                '            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-roller div:after,\n' +
                '            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-loader_balls_1 div,\n' +
                '            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-loader_balls_2 div,\n' +
                '            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-loader_balls_3 div,\n' +
                '            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-loading-spinner div:after{\n' +
                '                background: ' + newval + ' ;\n' +
                '            }';
            vi_wcaio_sc_toggle('show', wp.customize('woo_cart_all_in_one_params[sc_trigger_style]').get());
            jQuery('#vi-wcaio-preview-sc_loading_color').html(css);
            jQuery('.vi-wcaio-sidebar-cart-loading-wrap').removeClass('vi-wcaio-disabled');
            setTimeout(function () {
                jQuery('.vi-wcaio-sidebar-cart-loading-wrap').addClass('vi-wcaio-disabled');
            }, 5000);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sb_box_shadow_color]', function (value) {
        value.bind(function (newval) {
            let css = '';
            if (newval) {
                css = '.vi-wcaio-sb-container {\n' +
                    '                box-shadow: 0 1px 4px 0 ' + newval + ';\n' +
                    '            }';
            }
            jQuery('#vi-wcaio-preview-sb_box_shadow_color').html(css);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sb_pd_review]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                jQuery('.vi-wcaio-sb-product-rating-wrap').removeClass('vi-wcaio-disabled');
            } else {
                jQuery('.vi-wcaio-sb-product-rating-wrap').addClass('vi-wcaio-disabled');
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[sb_mobile_bt_atc_bg_color]', function (value) {
        value.bind(function (newval) {
            let css = '@media screen and (max-width: 1000px) {\n' +
                '                .vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview button.vi-wcaio-product-bt-atc{\n' +
                '                    background: ' + newval + ' ;\n' +
                '                }\n' +
                '            }';
            jQuery('#vi-wcaio-preview-sb_mobile_bt_atc_bg_color').html(css);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sb_mobile_bt_atc_color]', function (value) {
        value.bind(function (newval) {
            let css = '@media screen and (max-width: 1000px) {\n' +
                '                .vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview button.vi-wcaio-product-bt-atc{\n' +
                '                    color: ' + newval + ' ;\n' +
                '                }\n' +
                '            }';
            jQuery('#vi-wcaio-preview-sb_mobile_bt_atc_color').html(css);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sb_mobile_bt_atc_border_radius]', function (value) {
        value.bind(function (newval) {
            let css = '@media screen and (max-width: 1000px) {\n' +
                '                .vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview button.vi-wcaio-product-bt-atc{\n' +
                '                    border-radius: ' + newval + 'px ;\n' +
                '                }\n' +
                '            }';
            jQuery('#vi-wcaio-preview-sb_mobile_bt_atc_border_radius').html(css);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sb_mobile_bt_atc_font_size]', function (value) {
        value.bind(function (newval) {
            let css = '@media screen and (max-width: 1000px) {\n' +
                '                .vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview button.vi-wcaio-product-bt-atc{\n' +
                '                    font-size: ' + newval + 'px ;\n' +
                '                }\n' +
                '            }';
            jQuery('#vi-wcaio-preview-sb_mobile_bt_atc_font_size').html(css);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sb_pd_img_width]', function (value) {
        value.bind(function (newval) {
            let css = '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-product-img-wrap,\n' +
                '            .vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-product-img-wrap img{\n' +
                '                width:  ' + newval + 'px ;\n' +
                '                max-width: ' + newval + 'px ;\n' +
                '            }';
            jQuery('#vi-wcaio-preview-sb_pd_img_width').html(css);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sb_pd_img_height]', function (value) {
        value.bind(function (newval) {
            let css = '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-product-img-wrap,\n' +
                '            .vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-product-img-wrap img{\n' +
                '                height:  ' + newval + 'px ;\n' +
                '                max-height: ' + newval + 'px ;\n' +
                '            }';
            jQuery('#vi-wcaio-preview-sb_pd_img_height').html(css);
        });
    });
    wp.customize('woo_cart_all_in_one_params[custom_css]', function (value) {
        value.bind(function (newval) {
            jQuery('#vi-wcaio-preview-custom_css').html(newval);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_pd_qty_border_color]', function (value) {
        value.bind(function (newval) {
            let css = '';
            if (newval) {
                css = ' .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-desc .vi-wcaio-sidebar-cart-pd-quantity {\n' +
                    '                border: 1px solid ' + newval + ';\n' +
                    '            }\n' +
                    '            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-desc .vi_wcaio_minus {\n' +
                    '                border-right: 1px solid ' + newval + ';\n' +
                    '            }\n' +
                    '            .vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-desc .vi_wcaio_plus {\n' +
                    '                border-left: 1px solid ' + newval + ';\n' +
                    '            }'
            }
            jQuery('#vi-wcaio-preview-sc_pd_qty_border_color').html(css);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_checkout_enable]', function (value) {
        value.bind(function (newval) {
            if (!wp.customize('woo_cart_all_in_one_params[sc_enable]').get()) {
                return false;
            }
            if (newval) {
                vi_wcaio_sc_toggle('show-checkout');
            } else {
                vi_wcaio_sc_toggle('show');
            }
            jQuery('.vi-wcaio-sidebar-cart-loading').addClass('vi-wcaio-disabled');
            jQuery('.vi-wcaio-sidebar-cart-loading.vi-wcaio-sidebar-cart-loading-' + wp.customize('woo_cart_all_in_one_params[sc_loading]').get()).removeClass('vi-wcaio-disabled');
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_checkout_keyboard_nav]', function (value) {
        value.bind(function (newval) {
            jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout .vi-wcaio-sidebar-cart-checkout-wrap').data('use_keyboard', newval);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_checkout_billing_shipping]', function (value) {
        value.bind(function (newval) {
            let sc_checkout_order_payment = wp.customize('woo_cart_all_in_one_params[sc_checkout_order_payment]').get() ? 1 : '';
            let data = {
                action: 'viwcaio_get_checkout_form_preview',
                viwcaio_get_checkout: 1,
                viwcaio_checkout_form: {
                    sc_checkout_billing_shipping: newval ? 1 : '',
                    sc_checkout_billing_shipping_title: newval ? wp.customize('woo_cart_all_in_one_params[sc_checkout_billing_shipping_title]').get() : '',
                    sc_checkout_billing_title: newval ? '' : wp.customize('woo_cart_all_in_one_params[sc_checkout_billing_title]').get(),
                    sc_checkout_shipping_title: newval ? '' : wp.customize('woo_cart_all_in_one_params[sc_checkout_shipping_title]').get(),
                    sc_checkout_order_payment: sc_checkout_order_payment,
                    sc_checkout_order_payment_title: sc_checkout_order_payment ? wp.customize('woo_cart_all_in_one_params[sc_checkout_order_payment_title]').get() : '',
                    sc_checkout_order_review_title: sc_checkout_order_payment ? '' : wp.customize('woo_cart_all_in_one_params[sc_checkout_order_review_title]').get(),
                    sc_checkout_payment_title: sc_checkout_order_payment ? '' : wp.customize('woo_cart_all_in_one_params[sc_checkout_payment_title]').get(),
                }
            };
            jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout').trigger('init_checkout_form', [data, vi_wcaio_preview.ajax_url]);
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_checkout_order_payment]', function (value) {
        value.bind(function (newval) {
            let sc_checkout_billing_shipping = wp.customize('woo_cart_all_in_one_params[sc_checkout_billing_shipping]').get() ? 1 : '';
            let data = {
                action: 'viwcaio_get_checkout_form_preview',
                viwcaio_get_checkout: 1,
                viwcaio_checkout_form: {
                    sc_checkout_billing_shipping: sc_checkout_billing_shipping,
                    sc_checkout_billing_shipping_title: sc_checkout_billing_shipping ? wp.customize('woo_cart_all_in_one_params[sc_checkout_billing_shipping_title]').get() : '',
                    sc_checkout_billing_title: sc_checkout_billing_shipping ? '' : wp.customize('woo_cart_all_in_one_params[sc_checkout_billing_title]').get(),
                    sc_checkout_shipping_title: sc_checkout_billing_shipping ? '' : wp.customize('woo_cart_all_in_one_params[sc_checkout_shipping_title]').get(),
                    sc_checkout_order_payment: newval ? 1 : '',
                    sc_checkout_order_payment_title: newval ? wp.customize('woo_cart_all_in_one_params[sc_checkout_order_payment_title]').get() : '',
                    sc_checkout_order_review_title: newval ? '' : wp.customize('woo_cart_all_in_one_params[sc_checkout_order_review_title]').get(),
                    sc_checkout_payment_title: newval ? '' : wp.customize('woo_cart_all_in_one_params[sc_checkout_payment_title]').get(),
                }
            };
            jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout').trigger('init_checkout_form', [data, vi_wcaio_preview.ajax_url]);
        });
    });
    let checkout_step_title = {
        'billing-shipping': 'sc_checkout_billing_shipping_title',
        'billing': 'sc_checkout_billing_title',
        'shipping': 'sc_checkout_shipping_title',
        'order-payment': 'sc_checkout_order_payment_title',
        'order_review': 'sc_checkout_order_review_title',
        'payment': 'sc_checkout_payment_title',
    };
    jQuery.each(checkout_step_title, function (k, v) {
        wp.customize('woo_cart_all_in_one_params[' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout .vi-wcaio-checkout-step-' + k).data('step_title', newval);
                jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout').trigger('set_navs_button_text',
                    [jQuery('.vi-wcaio-checkout-step').index(jQuery('.vi-wcaio-checkout-step.vi-wcaio-checkout-step-current')), jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout')]);
            });
        });
        jQuery.each(languages, function (k1, v1) {
            wp.customize('woo_cart_all_in_one_params[' + v + '_' + v1 + ']', function (value) {
                value.bind(function (newval) {
                    jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout .vi-wcaio-checkout-step-' + k).data('step_title', newval);
                    jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout').trigger('set_navs_button_text',
                        [jQuery('.vi-wcaio-checkout-step').index(jQuery('.vi-wcaio-checkout-step.vi-wcaio-checkout-step-current')), jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout')]);
                });
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_checkout_bt_btc_enable]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                jQuery('.vi-wcaio-sidebar-cart-bt-checkout-cancel').removeClass('vi-wcaio-disabled');
            } else {
                jQuery('.vi-wcaio-sidebar-cart-bt-checkout-cancel').addClass('vi-wcaio-disabled');
            }
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_checkout_bt_btc_title]', function (value) {
        value.bind(function (newval) {
            if (newval && newval.indexOf('{back_icon}') >= 0) {
                let cart_icon = '';
                jQuery.ajax({
                    type: 'POST',
                    url: vi_wcaio_preview.ajax_url,
                    data: {
                        action: 'vi_wcaio_get_class_icon',
                        icon_id: wp.customize('woo_cart_all_in_one_params[sc_checkout_bt_btc_icon]').get(),
                        type: 'back_icons',
                    },
                    success: function (response) {
                        if (response && response.status === 'success') {
                            cart_icon = '<i class="vi-wcaio-sc-checkout-bt-btc-back_icons ' + response.message + '" ></i>';
                            newval = newval.replace(/{back_icon}/g, cart_icon);
                            jQuery('.vi-wcaio-sidebar-cart-bt-checkout-cancel').html(newval);
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            } else {
                jQuery('.vi-wcaio-sidebar-cart-bt-checkout-cancel').html(newval);
            }
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_cart_all_in_one_params[sc_checkout_bt_btc_title_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.vi-wcaio-sidebar-cart-bt-checkout-cancel').html(newval);
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_checkout_bt_btc_icon]', function (value) {
        value.bind(function (newval) {
            jQuery.ajax({
                type: 'POST',
                url: vi_wcaio_preview.ajax_url,
                data: {
                    action: 'vi_wcaio_get_class_icon',
                    icon_id: newval,
                    type: 'back_icons',
                },
                success: function (response) {
                    if (response && response.status === 'success') {
                        jQuery('.vi-wcaio-sidebar-cart-bt-checkout-cancel i.vi-wcaio-sc-checkout-bt-btc-back_icons').attr('class', 'vi-wcaio-sc-checkout-bt-btc-back_icons ' + response.message);
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_checkout_bt_next_title]', function (value) {
        value.bind(function (newval) {
            jQuery('.vi-wcaio-sidebar-cart-bt-checkout-next').data('next_text', newval);
            jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout').trigger('set_navs_button_text',
                [jQuery('.vi-wcaio-checkout-step').index(jQuery('.vi-wcaio-checkout-step.vi-wcaio-checkout-step-current')), jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout')]);
            if (jQuery('.vi-wcaio-sidebar-cart-bt-checkout-next').hasClass('vi-wcaio-disabled')) {
                jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order:not(.vi-wcaio-disabled)').addClass('vi-wcaio-disabled vi-wcaio-disabled-temp');
                jQuery('.vi-wcaio-sidebar-cart-bt-checkout-next').removeClass('vi-wcaio-disabled');
                setTimeout(function () {
                    jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order.vi-wcaio-disabled-temp').removeClass('vi-wcaio-disabled vi-wcaio-disabled-temp');
                    jQuery('.vi-wcaio-sidebar-cart-bt-checkout-next').addClass('vi-wcaio-disabled');
                }, 5000)
            }
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_cart_all_in_one_params[sc_checkout_bt_next_title_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.vi-wcaio-sidebar-cart-bt-checkout-next').data('next_text', newval);
                jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout').trigger('set_navs_button_text',
                    [jQuery('.vi-wcaio-checkout-step').index(jQuery('.vi-wcaio-checkout-step.vi-wcaio-checkout-step-current')), jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout')]);
                if (jQuery('.vi-wcaio-sidebar-cart-bt-checkout-next').hasClass('vi-wcaio-disabled')) {
                    jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order:not(.vi-wcaio-disabled)').addClass('vi-wcaio-disabled vi-wcaio-disabled-temp');
                    jQuery('.vi-wcaio-sidebar-cart-bt-checkout-next').removeClass('vi-wcaio-disabled');
                    setTimeout(function () {
                        jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order.vi-wcaio-disabled-temp').removeClass('vi-wcaio-disabled vi-wcaio-disabled-temp');
                        jQuery('.vi-wcaio-sidebar-cart-bt-checkout-next').addClass('vi-wcaio-disabled');
                    }, 5000)
                }
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_checkout_bt_pre_title]', function (value) {
        value.bind(function (newval) {
            jQuery('.vi-wcaio-sidebar-cart-bt-checkout-back').data('pre_text', newval);
            jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout').trigger('set_navs_button_text',
                [jQuery('.vi-wcaio-checkout-step').index(jQuery('.vi-wcaio-checkout-step.vi-wcaio-checkout-step-current')), jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout')]);
            if (jQuery('.vi-wcaio-sidebar-cart-bt-checkout-back').hasClass('vi-wcaio-disabled')) {
                jQuery('.vi-wcaio-sidebar-cart-bt-checkout-back').removeClass('vi-wcaio-disabled');
                setTimeout(function () {
                    jQuery('.vi-wcaio-sidebar-cart-bt-checkout-back').addClass('vi-wcaio-disabled');
                }, 5000)
            }
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_cart_all_in_one_params[sc_checkout_bt_pre_title_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.vi-wcaio-sidebar-cart-bt-checkout-back').data('pre_text', newval);
                jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout').trigger('set_navs_button_text',
                    [jQuery('.vi-wcaio-checkout-step').index(jQuery('.vi-wcaio-checkout-step.vi-wcaio-checkout-step-current')), jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout')]);
                if (jQuery('.vi-wcaio-sidebar-cart-bt-checkout-back').hasClass('vi-wcaio-disabled')) {
                    jQuery('.vi-wcaio-sidebar-cart-bt-checkout-back').removeClass('vi-wcaio-disabled');
                    setTimeout(function () {
                        jQuery('.vi-wcaio-sidebar-cart-bt-checkout-back').addClass('vi-wcaio-disabled');
                    }, 5000)
                }
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_checkout_bt_place_order_title]', function (value) {
        value.bind(function (newval) {
            jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order').data('place_order_text', newval);
            jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order').html(newval.replace(/{order_button_text}/g, jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout #place_order').html()));
            if (jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order').hasClass('vi-wcaio-disabled')) {
                jQuery('.vi-wcaio-sidebar-cart-bt-checkout-next:not(.vi-wcaio-disabled)').addClass('vi-wcaio-disabled vi-wcaio-disabled-temp');
                jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order').removeClass('vi-wcaio-disabled');
                setTimeout(function () {
                    jQuery('.vi-wcaio-sidebar-cart-bt-checkout-next.vi-wcaio-disabled-temp').removeClass('vi-wcaio-disabled vi-wcaio-disabled-temp');
                    jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order').addClass('vi-wcaio-disabled');
                }, 5000)
            }
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_cart_all_in_one_params[sc_checkout_bt_place_order_title_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order').data('place_order_text', newval);
                jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order').html(newval.replace(/{order_button_text}/g, jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout #place_order').html()));
                if (jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order').hasClass('vi-wcaio-disabled')) {
                    jQuery('.vi-wcaio-sidebar-cart-bt-checkout-next:not(.vi-wcaio-disabled)').addClass('vi-wcaio-disabled vi-wcaio-disabled-temp');
                    jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order').removeClass('vi-wcaio-disabled');
                    setTimeout(function () {
                        jQuery('.vi-wcaio-sidebar-cart-bt-checkout-next.vi-wcaio-disabled-temp').removeClass('vi-wcaio-disabled vi-wcaio-disabled-temp');
                        jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order').addClass('vi-wcaio-disabled');
                    }, 5000)
                }
            });
        });
    });
    let checkout_bt_place_order_design = [
        'sc_checkout_bt_place_order_border_radius',
        'sc_checkout_bt_place_order_bg_color',
        'sc_checkout_bt_place_order_color',
        'sc_checkout_bt_place_order_hover_bg_color',
        'sc_checkout_bt_place_order_hover_color',
    ];
    jQuery.each(checkout_bt_place_order_design, function (k, v) {
        wp.customize('woo_cart_all_in_one_params[' + v + ']', function (value) {
            value.bind(function (newval) {
                if (jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order').hasClass('vi-wcaio-disabled')) {
                    jQuery('.vi-wcaio-sidebar-cart-bt-checkout-next:not(.vi-wcaio-disabled)').addClass('vi-wcaio-disabled vi-wcaio-disabled-temp');
                    jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order').removeClass('vi-wcaio-disabled');
                    setTimeout(function () {
                        jQuery('.vi-wcaio-sidebar-cart-bt-checkout-next.vi-wcaio-disabled-temp').removeClass('vi-wcaio-disabled vi-wcaio-disabled-temp');
                        jQuery('.vi-wcaio-sidebar-cart-bt-checkout-place_order').addClass('vi-wcaio-disabled');
                    }, 5000)
                }
            });
        });
    });
    wp.customize('woo_cart_all_in_one_params[sc_checkout_nav_bar]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout').addClass('vi-wcaio-sidebar-cart-content-wrap-checkout-navs');
                jQuery('.vi-wcaio-checkout-nav-step-wrap').removeClass('vi-wcaio-disabled');
            } else {
                jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout').removeClass('vi-wcaio-sidebar-cart-content-wrap-checkout-navs');
                jQuery('.vi-wcaio-checkout-nav-step-wrap').addClass('vi-wcaio-disabled');
            }
        });
    });
    vi_wcaio_add_preview_control('sc_radius', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-content-wrap', 'border-radius', 'px');
    vi_wcaio_add_preview_control('sc_icon_border_radius', '.vi-wcaio-sidebar-cart-icon-wrap', 'border-radius', 'px');
    vi_wcaio_add_preview_control('sc_icon_bg_color', '.vi-wcaio-sidebar-cart-icon-wrap', 'background', '');
    vi_wcaio_add_preview_control('sc_icon_color', '.vi-wcaio-sidebar-cart-icon-wrap .vi-wcaio-sidebar-cart-icon i', 'color', '');
    vi_wcaio_add_preview_control('sc_icon_count_bg_color', '.vi-wcaio-sidebar-cart-icon-wrap .vi-wcaio-sidebar-cart-count-wrap', 'background', '');
    vi_wcaio_add_preview_control('sc_icon_count_color',
        '.vi-wcaio-sidebar-cart-icon-wrap .vi-wcaio-sidebar-cart-count-wrap, .vi-wcaio-sidebar-cart-icon-wrap .vi-wcaio-sidebar-cart-count-wrap .amount',
        'color', '');
    vi_wcaio_add_preview_control('sc_icon_count_border_radius', '.vi-wcaio-sidebar-cart-icon-wrap .vi-wcaio-sidebar-cart-count-wrap', 'border-radius', 'px');
    vi_wcaio_add_preview_control('sc_header_bg_color', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap', 'background', '');
    vi_wcaio_add_preview_control('sc_header_border_style', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap', 'border-style', '');
    vi_wcaio_add_preview_control('sc_header_border_color', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap', 'border-color', '');
    vi_wcaio_add_preview_control('sc_header_title_color', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-title-wrap', 'color', '');
    vi_wcaio_add_preview_control('sc_header_coupon_input_radius',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-coupon-wrap .vi-wcaio-coupon-code',
        'border-radius', 'px');
    vi_wcaio_add_preview_control('sc_header_coupon_button_bg_color',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-coupon-wrap button.vi-wcaio-bt-coupon-code',
        'background', '');
    vi_wcaio_add_preview_control('sc_header_coupon_button_color',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-coupon-wrap button.vi-wcaio-bt-coupon-code',
        'color', '');
    vi_wcaio_add_preview_control('sc_header_coupon_button_bg_color_hover',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-coupon-wrap button.vi-wcaio-bt-coupon-code:hover',
        'background', '');
    vi_wcaio_add_preview_control('sc_header_coupon_button_color_hover',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-coupon-wrap button.vi-wcaio-bt-coupon-code:hover',
        'color', '');
    vi_wcaio_add_preview_control('sc_header_coupon_button_border_radius',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-header-wrap .vi-wcaio-sidebar-cart-header-coupon-wrap button.vi-wcaio-bt-coupon-code',
        'border-radius', 'px');
    vi_wcaio_add_preview_control('sc_footer_bg_color', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap', 'background', '');
    vi_wcaio_add_preview_control('sc_footer_border_type', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap', 'border-style', '');
    vi_wcaio_add_preview_control('sc_footer_border_color', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap', 'border-color', '');
    vi_wcaio_add_preview_control('sc_footer_cart_total_color',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-footer-cart_total > div:nth-child(1)',
        'color', '');
    vi_wcaio_add_preview_control('sc_footer_cart_total_color1',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-footer-cart_total > div:nth-child(2)',
        'color', '');
    vi_wcaio_add_preview_control('sc_footer_button_bg_color',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt.vi-wcaio-sidebar-cart-bt-nav',
        'background', '');
    vi_wcaio_add_preview_control('sc_footer_button_color',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt.vi-wcaio-sidebar-cart-bt-nav',
        'color', '');
    vi_wcaio_add_preview_control('sc_footer_button_hover_bg_color',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt.vi-wcaio-sidebar-cart-bt-nav:hover',
        'background', '');
    vi_wcaio_add_preview_control('sc_footer_button_hover_color',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt.vi-wcaio-sidebar-cart-bt-nav:hover',
        'color', '');
    vi_wcaio_add_preview_control('sc_footer_button_border_radius',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-bt.vi-wcaio-sidebar-cart-bt-nav',
        'border-radius', 'px');
    vi_wcaio_add_preview_control('sc_footer_bt_update_bg_color',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap button.vi-wcaio-sidebar-cart-bt-update',
        'background', '');
    vi_wcaio_add_preview_control('sc_footer_bt_update_color',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap button.vi-wcaio-sidebar-cart-bt-update',
        'color', '');
    vi_wcaio_add_preview_control('sc_footer_bt_update_hover_bg_color',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap button.vi-wcaio-sidebar-cart-bt-update:hover',
        'background', '');
    vi_wcaio_add_preview_control('sc_footer_bt_update_hover_color',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap button.vi-wcaio-sidebar-cart-bt-update:hover',
        'color', '');
    vi_wcaio_add_preview_control('sc_footer_bt_update_border_radius',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap button.vi-wcaio-sidebar-cart-bt-update',
        'border-radius', 'px');
    vi_wcaio_add_preview_control('sc_footer_pd_plus_color',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-footer-wrap .vi-wcaio-sidebar-cart-footer-pd-plus-title',
        'color', '');
    vi_wcaio_add_preview_control('sc_pd_bg_color', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products-wrap', 'background', '');
    vi_wcaio_add_preview_control('sc_pd_img_border_radius', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-img-wrap img', 'border-radius', 'px');
    vi_wcaio_add_preview_control('sc_pd_name_color',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-info-wrap .vi-wcaio-sidebar-cart-pd-name-wrap .vi-wcaio-sidebar-cart-pd-name, .vi-wcaio-sidebar-cart-footer-pd-name *',
        'color', '');
    vi_wcaio_add_preview_control('sc_pd_name_hover_color',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-info-wrap .vi-wcaio-sidebar-cart-pd-name-wrap .vi-wcaio-sidebar-cart-pd-name:hover, .vi-wcaio-sidebar-cart-footer-pd-name *:hover',
        'color', '');
    vi_wcaio_add_preview_control('sc_pd_price_color',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-info-wrap .vi-wcaio-sidebar-cart-pd-price *, .vi-wcaio-sidebar-cart-footer-pd-price *',
        'color', '');
    vi_wcaio_add_preview_control('sc_pd_delete_icon_font_size',
        '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-remove-wrap i:before', 'font-size', 'px');
    vi_wcaio_add_preview_control('sc_pd_delete_icon_color', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-remove-wrap i', 'color', '');
    vi_wcaio_add_preview_control('sc_pd_delete_icon_hover_color', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-remove-wrap i:hover', 'color', '');
    vi_wcaio_add_preview_control('mc_icon_color', '.vi-wcaio-menu-cart .vi-wcaio-menu-cart-icon i', 'color', '');
    vi_wcaio_add_preview_control('mc_icon_hover_color', '.vi-wcaio-menu-cart .vi-wcaio-menu-cart-nav-wrap:hover .vi-wcaio-menu-cart-icon i', 'color', '');
    vi_wcaio_add_preview_control('mc_color', '.vi-wcaio-menu-cart .vi-wcaio-menu-cart-text-wrap *', 'color', '');
    vi_wcaio_add_preview_control('mc_hover_color', '.vi-wcaio-menu-cart .vi-wcaio-menu-cart-nav-wrap:hover .vi-wcaio-menu-cart-text-wrap *', 'color', '');
    vi_wcaio_add_preview_control('sb_bg_color', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview', 'background');
    vi_wcaio_add_preview_control('sb_padding', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview', 'padding');
    vi_wcaio_add_preview_control('sb_border_radius', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview', 'border-radius', 'px');
    vi_wcaio_add_preview_control('sb_quantity_border_radius', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-wrap .quantity', 'border-radius', 'px');
    vi_wcaio_add_preview_control('sb_bt_atc_bg_color', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview button.vi-wcaio-product-bt-atc', 'background');
    vi_wcaio_add_preview_control('sb_bt_atc_color', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview button.vi-wcaio-product-bt-atc', 'color');
    vi_wcaio_add_preview_control('sb_bt_atc_border_radius', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview button.vi-wcaio-product-bt-atc', 'border-radius', 'px');
    vi_wcaio_add_preview_control('sb_bt_atc_font_size', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview button.vi-wcaio-product-bt-atc', 'font-size', 'px');
    vi_wcaio_add_preview_control('sb_pd_name_color', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-product-title', 'color');
    vi_wcaio_add_preview_control('sb_pd_price_color2', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-product-price-wrap .price', 'color');
    vi_wcaio_add_preview_control('sb_pd_price_color1', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-product-price-wrap .price del', 'color');
    vi_wcaio_add_preview_control('sb_pd_review_color', '.vi-wcaio-sb-container.vi-wcaio-sb-container-customize_preview .vi-wcaio-sb-product-rating-wrap *:before', 'color');
    vi_wcaio_add_preview_control('sc_pd_qty_border_radius', '.vi-wcaio-sidebar-cart .vi-wcaio-sidebar-cart-products .vi-wcaio-sidebar-cart-pd-desc .vi-wcaio-sidebar-cart-pd-quantity',
        'border-radius', 'px');
    vi_wcaio_add_preview_control('sc_footer_pd_plus_bt_atc_bg_color', '.vi-wcaio-sidebar-cart .button.vi-wcaio-pd_plus-product-bt-atc',
        'background', '');
    vi_wcaio_add_preview_control('sc_footer_pd_plus_bt_atc_color', '.vi-wcaio-sidebar-cart .button.vi-wcaio-pd_plus-product-bt-atc',
        'color', '');
    vi_wcaio_add_preview_control('sc_footer_pd_plus_bt_atc_hover_bg_color', '.vi-wcaio-sidebar-cart .button.vi-wcaio-pd_plus-product-bt-atc:hover',
        'background', '');
    vi_wcaio_add_preview_control('sc_footer_pd_plus_bt_atc_hover_color', '.vi-wcaio-sidebar-cart .button.vi-wcaio-pd_plus-product-bt-atc:hover',
        'color', '');
    vi_wcaio_add_preview_control('sc_checkout_bg_color', '.vi-wcaio-sidebar-cart-content-wrap1.vi-wcaio-sidebar-cart-checkout-wrap',
        'background', '');
    vi_wcaio_add_preview_control('sc_checkout_bt_btc_bg_color', '.vi-wcaio-sidebar-cart-wrap button.vi-wcaio-sidebar-cart-bt-checkout-cancel',
        'background', '');
    vi_wcaio_add_preview_control('sc_checkout_bt_btc_color', '.vi-wcaio-sidebar-cart-wrap button.vi-wcaio-sidebar-cart-bt-checkout-cancel',
        'color', '');
    vi_wcaio_add_preview_control('sc_checkout_bt_btc_hover_bg_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-cancel:hover',
        'background', '');
    vi_wcaio_add_preview_control('sc_checkout_bt_btc_hover_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-cancel:hover',
        'color', '');
    vi_wcaio_add_preview_control('sc_checkout_bt_btc_border_radius', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-cancel',
        'border-radius', 'px');
    vi_wcaio_add_preview_control('sc_checkout_bt_next_border_radius', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-next',
        'border-radius', 'px');
    vi_wcaio_add_preview_control('sc_checkout_bt_next_bg_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-next',
        'background', '');
    vi_wcaio_add_preview_control('sc_checkout_bt_next_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-next',
        'color', '');
    vi_wcaio_add_preview_control('sc_checkout_bt_next_hover_bg_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-next:hover',
        'background', '');
    vi_wcaio_add_preview_control('sc_checkout_bt_next_hover_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-next:hover',
        'color', '');
    vi_wcaio_add_preview_control('sc_checkout_bt_pre_border_radius', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-back',
        'border-radius', 'px');
    vi_wcaio_add_preview_control('sc_checkout_bt_pre_bg_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-back',
        'background', '');
    vi_wcaio_add_preview_control('sc_checkout_bt_pre_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-back',
        'color', '');
    vi_wcaio_add_preview_control('sc_checkout_bt_pre_hover_bg_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-back:hover',
        'background', '');
    vi_wcaio_add_preview_control('sc_checkout_bt_pre_hover_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-nav.vi-wcaio-sidebar-cart-bt-checkout-back:hover',
        'color', '');
    vi_wcaio_add_preview_control('sc_checkout_bt_place_order_border_radius', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-place_order',
        'border-radius', 'px');
    vi_wcaio_add_preview_control('sc_checkout_bt_place_order_bg_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-place_order',
        'background', '');
    vi_wcaio_add_preview_control('sc_checkout_bt_place_order_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-place_order',
        'color', '');
    vi_wcaio_add_preview_control('sc_checkout_bt_place_order_hover_bg_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-place_order:hover',
        'background', '');
    vi_wcaio_add_preview_control('sc_checkout_bt_place_order_hover_color', '.vi-wcaio-sidebar-cart button.vi-wcaio-sidebar-cart-bt-checkout-place_order:hover',
        'color', '');
    vi_wcaio_add_preview_control('sc_checkout_nav_bar_color', '.vi-wcaio-sidebar-cart-wrap .vi-wcaio-checkout-nav-step-wrap .vi-wcaio-checkout-nav-step',
        'background', '');
    vi_wcaio_add_preview_control('sc_checkout_nav_bar_hover_color', '.vi-wcaio-sidebar-cart-wrap .vi-wcaio-checkout-nav-step-wrap .vi-wcaio-checkout-nav-step:hover',
        'background', '');
    vi_wcaio_add_preview_control('sc_checkout_nav_bar_selected_color',
        '.vi-wcaio-sidebar-cart-wrap .vi-wcaio-checkout-nav-step-wrap .vi-wcaio-checkout-nav-step.vi-wcaio-checkout-step-current',
        'background', '');
    jQuery(document).ready(function () {
        if (jQuery('.vi-wcaio-sidebar-cart-wrap').length) {
            viwcaio_sidebar_cart_refresh(vi_wcaio_preview.ajax_url, {action: 'viwcaio_get_cart_fragments', viwcaio_get_cart_fragments: 1, viwcaio_is_customize: 1});
        }
        jQuery('.vi-wcaio-sidebar-cart-footer-pd-wrap-wrap:not(.vi-wcaio-slide-init)').each(function () {
            vi_wcaio_sc_flexslider(jQuery(this));
        });
        jQuery('.vi-wcaio-sb-container img').each(function () {
            jQuery(this).attr('src', jQuery(this).data('src'));
        });
        jQuery(document.body).on('click', '.vi-wcaio-sidebar-cart-pd-wrap .vi_wcaio_change_qty', function (e) {
            e.preventDefault();
            e.stopPropagation();
            let qty_input = jQuery(this).closest('.vi-wcaio-sidebar-cart-pd-quantity').find('.vi_wcaio_qty');
            let val = parseFloat(qty_input.val()),
                step = parseFloat(qty_input.attr('step')),
                min = parseFloat(qty_input.attr('min')),
                max = parseFloat(qty_input.attr('max'));
            if (jQuery(this).hasClass('vi_wcaio_plus')) {
                if (max === val) {
                    return false;
                }
                val += step;
            } else {
                if (min === val) {
                    return false;
                }
                val -= step;
            }
            qty_input.val(val);
        });
        jQuery(document.body).on('wc_fragments_refreshed wc_fragments_ajax_error', function (e) {
            if (!wp.customize('woo_cart_all_in_one_params[sc_footer_coupon]').get()) {
                jQuery('.vi-wcaio-sidebar-cart-footer-coupons').addClass('vi-wcaio-disabled');
            }
        });
    });
    jQuery(window).on('scroll', function () {
        if (jQuery('.vi-wcaio-sb-container').length) {
            viwcaio_sb_toggle();
        }
    });
})();

function vi_wcaio_add_preview_control(name, element, style, suffix = '') {
    wp.customize('woo_cart_all_in_one_params[' + name + ']', function (value) {
        value.bind(function (newval) {
            jQuery('#vi-wcaio-preview-' + name).html(element + '{' + style + ':' + newval + suffix + '}');
        })
    })
}