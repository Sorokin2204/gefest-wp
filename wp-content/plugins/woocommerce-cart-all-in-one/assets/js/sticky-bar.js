(function () {
    if (typeof viwcaio_sb_params === 'undefined') {
        return false;
    }
    jQuery(document).ready(function () {
        'use strict';
        jQuery(document).on('click', '.vi-wcaio-product-bt-atc.vi-wcaio-product-bt-external', function (e) {
            jQuery(this).attr('type', 'submit').trigger('click');
            return false;
        });
        jQuery(document).on('click', '.vi-wcaio-product-bt-atc.vi-wcaio-product-bt-select', function (e) {
            jQuery('form.cart')[0].scrollIntoView();
            return false;
        });
        jQuery(document).on('click', '.vi-wcaio-sb-product-bt-popup', function (e) {
            if (jQuery('.vi-wcaio-sb-container-popup .vi-wcaio-sb-cart-form').length) {
                viwcaio_sb_popup_toggle(true);
            }else {
                jQuery('form.cart')[0].scrollIntoView();
            }
        });
        jQuery(document).on('click', '.vi-wcaio-sb-container-popup-overlay, .vi-wcaio-sb-container-popup-cancel', function (e) {
            viwcaio_sb_popup_toggle();
        });
        jQuery(document.body).on('click', '.vi-wcaio-sb-container .vi_wcaio_change_qty', function (e) {
            e.preventDefault();
            e.stopPropagation();
            let qty_input = jQuery('.vi-wcaio-sb-container input.vi-wcaio-sb-product-qty');
            let val = parseFloat(qty_input.val()),
                step = parseFloat(qty_input.attr('step'));
            if (jQuery(this).hasClass('vi_wcaio_plus')){
                val +=step;
            }else {
                val -=step;
            }
            qty_input.val(val).trigger('change');
        });
        jQuery(document.body).on('change', '.vi-wcaio-sb-container input.vi-wcaio-sb-product-qty', function (e) {
            e.preventDefault();
            e.stopPropagation();
            let val = parseFloat(jQuery(this).val()),
                min = parseFloat(jQuery(this).attr('min')),
                max = parseFloat(jQuery(this).attr('max'));
            if (min > val) {
                val = min;
            }
            if (val > max) {
                val = max;
            }
            jQuery(this).val(val);
        });
    });
    jQuery(window).on('load',function () {
        'use strict';
        viwcaio_sb_design();
    }).scroll(function () {
        'use strict';
        viwcaio_sb_toggle();
    });
})();

function viwcaio_sb_design() {
    if (!jQuery('.vi-wcaio-sb-container:not(.vi-wcaio-sb-container-init)').length) {
        return false;
    }
    let wrap = jQuery('.vi-wcaio-sb-container:not(.vi-wcaio-sb-container-init)');
    if (jQuery('.vi-wcaio-sb-container-popup input[name="quantity"]').attr('type')==='hidden'){
        jQuery('.vi-wcaio-sb-container-popup .vi-wcaio-sb-cart-form-content-qty').addClass('vi-wcaio-disabled').prev('.vi-wcaio-sb-cart-form-content').addClass('vi-wcaio-sb-cart-form-content-border-none')
    }
    wrap.find('img').each(function () {
        jQuery(this).attr('src', jQuery(this).data('src'));
    });
    wrap.find('.vi-wcaio-sb-cart-swatches:not(.vi-wcaio-sb-cart-swatches-init)').each(function () {
        jQuery(this).addClass('vi-wcaio-sb-cart-swatches-init vi_wpvs_variation_form').viwcaio_get_variations(viwcaio_sb_params);
        // Babystreet theme of theAlThemist
        if (jQuery(this).find('.babystreet-wcs-swatches').length) {
            jQuery(this).babystreet_wcs_variation_swatches_form();
        }
    });
    // WooCommerce Product Variations Swatches plugin of VillaTheme
    jQuery(document.body).trigger('vi_wpvs_variation_form');
    viwcaio_sb_toggle();
}

function viwcaio_sb_popup_toggle(show=false) {
    let popup = jQuery('.vi-wcaio-sb-container.vi-wcaio-sb-container-popup'),
        sticky = jQuery('.vi-wcaio-sb-container:not(.vi-wcaio-sb-container-popup)');
    if (show  ){
        if (sticky.hasClass('vi-wcaio-sb-container-always_appear')){
            sticky.removeClass('vi-wcaio-sb-container-always_appear').addClass('vi-wcaio-sb-container-always_appear-disabled');
        }
        if (popup.hasClass('vi-wcaio-sb-container-ps-0')) {
            popup.addClass('vi-wcaio-slide_in_up vi-wcaio-sb-container-popup-show');
            popup.removeClass('vi-wcaio-slide_out_down vi-wcaio-sb-container-hide');
            sticky.removeClass('vi-wcaio-slide_in_up');
            sticky.addClass('vi-wcaio-slide_out_down');
        } else {
            popup.addClass('vi-wcaio-slide_in_dowm vi-wcaio-sb-container-popup-show');
            popup.removeClass('vi-wcaio-slide_out_up vi-wcaio-sb-container-hide');
            sticky.removeClass('vi-wcaio-slide_in_dowm');
            sticky.addClass('vi-wcaio-slide_out_up');
        }
    }else {
        if (popup.hasClass('vi-wcaio-sb-container-ps-0')) {
            popup.removeClass('vi-wcaio-slide_in_up vi-wcaio-sb-container-popup-show');
            popup.addClass('vi-wcaio-slide_out_down');
            sticky.addClass('vi-wcaio-slide_in_up');
            sticky.removeClass('vi-wcaio-slide_out_down vi-wcaio-sb-container-hide');
        } else {
            popup.removeClass('vi-wcaio-slide_in_dowm vi-wcaio-sb-container-popup-show');
            popup.addClass('vi-wcaio-slide_out_up');
            sticky.addClass('vi-wcaio-slide_in_dowm');
            sticky.removeClass('vi-wcaio-slide_out_up vi-wcaio-sb-container-hide');
        }
        if (sticky.hasClass('vi-wcaio-sb-container-always_appear-disabled')){
            setTimeout(function (sticky) {
                sticky.removeClass('vi-wcaio-sb-container-always_appear-disabled').addClass('vi-wcaio-sb-container-always_appear');
            },500,sticky);
        }
    }
    if(jQuery('.vi-wcaio-sidebar-cart-icon-wrap').length){
        vi_wcaio_sc_icon_toggle(!show);
    }
}
function viwcaio_sb_toggle() {
    let form = jQuery('form.cart'),
        wrap = jQuery('.vi-wcaio-sb-container:not(.vi-wcaio-sb-container-popup)');
    if (!form.length || typeof form[0].getBoundingClientRect() ==='undefined'){
        return false;
    }
    let ps_check = form[0].getBoundingClientRect().top + form[0].scrollHeight;
    if (wrap.hasClass('vi-wcaio-sb-container-ps-0')) {
        if (ps_check < 0) {
            wrap.addClass('vi-wcaio-slide_in_up');
            wrap.removeClass('vi-wcaio-slide_out_down vi-wcaio-sb-container-hide');
        } else if (wrap.hasClass('vi-wcaio-slide_in_up')) {
            wrap.removeClass('vi-wcaio-slide_in_up');
            wrap.addClass('vi-wcaio-slide_out_down');
        }
    } else {
        if (ps_check < 0) {
            wrap.addClass('vi-wcaio-slide_in_dowm');
            wrap.removeClass('vi-wcaio-slide_out_up vi-wcaio-sb-container-hide');
        } else if (wrap.hasClass('vi-wcaio-slide_in_dowm')) {
            wrap.removeClass('vi-wcaio-slide_in_dowm');
            wrap.addClass('vi-wcaio-slide_out_up');
        }
    }
}