if (viwcaio_sc_checkout_params.WC_Gateway_Twocheckout ){
    if (viwcaio_sc_checkout_params.WC_Gateway_Twocheckout.not_twocheckoutIsCheckout) {
        var  twocheckoutIsCheckout  = viwcaio_sc_checkout_params.WC_Gateway_Twocheckout.twocheckoutIsCheckout;
    }else if (typeof twocheckoutIsCheckout !== 'undefined'){
        twocheckoutIsCheckout = viwcaio_sc_checkout_params.WC_Gateway_Twocheckout.twocheckoutIsCheckout;
    }
}
if (viwcaio_sc_checkout_params.country_locale &&  typeof wc_address_i18n_params !== 'undefined' ){
    wc_address_i18n_params.locale  = viwcaio_sc_checkout_params.country_locale;
}
jQuery(window).on('load', function () {
    'use strict';
    viwcaio_checkout_init();
});
function viwcaio_checkout_init() {
    let checkout = viwcaio_checkout;
    if (typeof viwcaio_sc_params !== 'undefined') {
        checkout.wc_ajax_url = viwcaio_sc_params.wc_ajax_url || '';
        checkout.language = viwcaio_sc_params.language || '';
    }
    checkout.WC_Gateway_Twocheckout_Inline = viwcaio_sc_checkout_params.WC_Gateway_Twocheckout_Inline || '';
    checkout.invalid_text = viwcaio_sc_checkout_params.invalid_text || '';
    checkout.unavailable_checkout_text = viwcaio_sc_checkout_params.unavailable_checkout_text || 'Checkout is not available whilst your cart is empty.';
    checkout.$wrap = jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout');
    checkout.$checkout_form = jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout form.checkout');
    if ( typeof PayPalCommerceGateway !== 'undefined' ) {
        jQuery('#ppc-button').each(function (k, v) {
           if (!jQuery(v).closest('.vi-wcaio-sidebar-cart-checkout-wrap').length){
               jQuery(v).remove();
           }else {
               jQuery(v).css('display','block');
           }
        });
    }
    checkout.init();
}
var viwcaio_checkout = {
    wc_ajax_url: '',
    language: '',
    invalid_text: '',
    unavailable_checkout_text: '',
    $wrap: jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout'),
    $checkout_form: jQuery('.vi-wcaio-sidebar-cart-content-wrap-checkout form.checkout'),
    is_paysoncheckout_default: '',
    init: function () {
        let self = this;
        self.$wrap.find('.vi-wcaio-checkout-nav-step-wrap').each(function () {
            let steps = jQuery(this).find('.vi-wcaio-checkout-nav-step').length;
            jQuery(this).css({gridTemplateColumns: 'repeat(' + steps + ',1fr)', top: (self.$wrap.find('.vi-wcaio-sidebar-cart-header-wrap').outerHeight() + 'px')});
        });
        self.$wrap.on('init_checkout', self.init_checkout);
        self.$wrap.on('init_checkout_form', function (e, data, url) {
            self.init_checkout_form(data, url)
        });
        self.$wrap.on('click', '.vi-wcaio-sidebar-cart-bt-checkout-cancel', self.close_checkout);
        self.$wrap.on('click', '.vi-wcaio-sidebar-cart-bt-nav-checkout', self.open_checkout);
        self.$wrap.on('click', '.vi-wcaio-checkout-nav-step, .vi-wcaio-sidebar-cart-bt-checkout-next, .vi-wcaio-sidebar-cart-bt-checkout-back', self.may_be_switch_step);
        self.$checkout_form.on('change', '#ship-to-different-address input', self.country_select_select2);
        self.$wrap.on('set_navs_button_text', function (e, index, wrap) {
            self.set_navs_button_text(index, wrap);
        });
        self.$wrap.on('keydown', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });
        self.$checkout_form.on('submit',function (e) {
            if (jQuery.inArray(jQuery('[name="payment_method"]').filter( ':checked' ).eq(0).val(),['stripe','woocommerce_payments']) > -1 && jQuery('.wc-upe-form').length){
                jQuery(this).addClass('processing');
            }
        });
        self.$wrap.on('click', '.vi-wcaio-sidebar-cart-bt-checkout-place_order', function () {
            jQuery( '.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message' ).remove();
            jQuery('.vi-wcaio-sidebar-cart-overlay, .vi-wcaio-sidebar-cart-close-wrap').addClass('vi-wcaio-not-hidden');
            self.start_loading();
            if (self.WC_Gateway_Twocheckout_Inline || self.$checkout_form.find('#payment_method_twocheckout_inline').length){
                self.$checkout_form.find('#place_order').trigger('click');
                return false;
            }
            if (self.$checkout_form.triggerHandler('submit') === false  && !self.$checkout_form.hasClass('processing')) {
                setTimeout(function () {
                    if (jQuery('.vi-wcaio-warning-wrap-open').length) {
                        return false;
                    }
                    let wrap_error = jQuery('.woocommerce-error, .woocommerce-message, .woocommerce-NoticeGroup-checkout');
                    if (wrap_error.length) {
                        vi_wcaio_show_message(wrap_error.html());
                    }
                    jQuery('.vi-wcaio-sidebar-cart-overlay, .vi-wcaio-sidebar-cart-close-wrap').removeClass('vi-wcaio-not-hidden');
                    jQuery('html, body').stop();
                    self.end_loading();
                }, 2000);
            }
        });
        jQuery(document).on('keydown', function (e) {
            if (self.$wrap.find('.vi-wcaio-sidebar-cart-checkout-wrap:not(.vi-wcaio-disabled)').data('use_keyboard')) {
                let key = e.which;
                if (key === 37) {
                    self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-back').trigger('click');
                } else if (key === 39) {
                    self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-next').trigger('click');
                }
            }
        });
        self.woo_checkout();
        //compatible with PaysonCheckout for WooCommerce by Krokedil
        if (typeof pco_wc_params !== "undefined") {
            self.is_paysoncheckout_default = jQuery('input[name="payment_method"]#payment_method_paysoncheckout').prop('checked');
            self.$wrap.on('click', '.paysoncheckout-select-other-wrapper, #paysoncheckout-select-other ', function (e) {
                e.preventDefault();
                e.stopPropagation();
                jQuery.ajax({
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        pco: false,
                        order_id: pco_wc_params.order_id,
                        nonce: pco_wc_params.change_payment_method_nonce
                    },
                    url: pco_wc_params.change_payment_method_url,
                    beforeSend: function () {
                        viwcaio_checkout.start_loading();
                    },
                    complete: function (response) {
                        let data = {viwcaio_get_checkout: 1, viwcaio_language: viwcaio_checkout.language, is_paysoncheckout:0};
                        viwcaio_checkout.$wrap.trigger('init_checkout_form', [data, viwcaio_checkout.wc_ajax_url.toString().replace('%%endpoint%%', 'viwcaio_get_checkout_form')]);
                    }
                });
            });
            self.$wrap.on('change', 'input[name="payment_method"]', function (e) {
                if ( 'paysoncheckout' === jQuery(this).val() ) {
                    e.preventDefault();
                    e.stopPropagation();
                    jQuery.ajax({
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            pco: true,
                            order_id: pco_wc_params.order_id,
                            nonce: pco_wc_params.change_payment_method_nonce
                        },
                        url: pco_wc_params.change_payment_method_url,
                        beforeSend: function () {
                            viwcaio_checkout.start_loading();
                        },
                        complete: function (response) {
                            let data = {viwcaio_get_checkout: 1, viwcaio_language: viwcaio_checkout.language, is_paysoncheckout: 1};
                            viwcaio_checkout.$wrap.trigger('init_checkout_form', [data, viwcaio_checkout.wc_ajax_url.toString().replace('%%endpoint%%', 'viwcaio_get_checkout_form')]);
                        }
                    });
                }
            });
        }
    },
    woo_checkout: function () {
        let self = viwcaio_checkout;
        let place_order_text = self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').data('place_order_text') ||self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').attr('data-place_order_text') ;
        place_order_text = place_order_text ? place_order_text.toString() : '';
        if (!self.$checkout_form.find('#place_order').length ){
            self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').addClass('vi-wcaio-bt-disabled');
        }else {
            self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').removeClass('vi-wcaio-bt-disabled').html(place_order_text.replace(/{order_button_text}/g,
                self.$checkout_form.find('#place_order').html() || self.$checkout_form.find('#place_order').val() || self.$checkout_form.find('#place_order').data('value')));
        }
        if (jQuery('input[name="payment_method"]#payment_method_ppcp-gateway').prop('checked')){
            self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').addClass('vi-wcaio-bt-disabled');
        }
        if (self.$wrap.hasClass('vi-wcaio-sidebar-cart-content-wrap-customize')) {
            jQuery(document.body).on('payment_method_selected', function () {
                if (!self.$checkout_form.find('#place_order').length){
                    self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').addClass('vi-wcaio-bt-disabled');
                }else {
                    place_order_text = self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').data('place_order_text')||self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').attr('data-place_order_text');
                    self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').removeClass('vi-wcaio-bt-disabled').html(place_order_text.replace(/{order_button_text}/g,
                        self.$checkout_form.find('#place_order').html()||self.$checkout_form.find('#place_order').val()||self.$checkout_form.find('#place_order').data('value')));
                }
                if (jQuery('input[name="payment_method"]#payment_method_ppcp-gateway').prop('checked')){
                    self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').addClass('vi-wcaio-bt-disabled');
                }
            });
        } else if (place_order_text.indexOf('{order_button_text}') !== -1) {
            jQuery(document.body).on('payment_method_selected', function () {
                if (!self.$checkout_form.find('#place_order').length){
                    self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').addClass('vi-wcaio-bt-disabled');
                }else {
                    self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').removeClass('vi-wcaio-bt-disabled').html(place_order_text.replace(/{order_button_text}/g,
                        self.$checkout_form.find('#place_order').html()||self.$checkout_form.find('#place_order').val()||self.$checkout_form.find('#place_order').data('value')));
                }
                if (jQuery('input[name="payment_method"]#payment_method_ppcp-gateway').prop('checked')){
                    self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').addClass('vi-wcaio-bt-disabled');
                }
            });
        }
        if ( typeof PayPalCommerceGateway !== 'undefined' && !self.$checkout_form.find(PayPalCommerceGateway.button.wrapper).length) {
            self.$checkout_form.find('#payment').after('<div id="'+PayPalCommerceGateway.button.wrapper.replace('#', '')+'"></div>');
        }
        jQuery(document.body).on('payment_method_selected', function () {
            if (!self.$checkout_form.find('#place_order').length){
                self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').addClass('vi-wcaio-bt-disabled');
            }else {
                self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').removeClass('vi-wcaio-bt-disabled').html(place_order_text.replace(/{order_button_text}/g,
                    self.$checkout_form.find('#place_order').html()||self.$checkout_form.find('#place_order').val()||self.$checkout_form.find('#place_order').data('value')));
            }
            if (jQuery('input[name="payment_method"]#payment_method_ppcp-gateway').prop('checked')){
                self.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').addClass('vi-wcaio-bt-disabled');
            }
            if ( jQuery.inArray(jQuery('[name="payment_method"]').filter( ':checked' ).eq(0).val(),['stripe','woocommerce_payments']) > -1  && jQuery('.wc-upe-form').length && jQuery('.wc-upe-form').parent().find('.woocommerce-SavedPaymentMethods-new').length ){
                jQuery('.wc-upe-form').parent().find('.woocommerce-SavedPaymentMethods-new').remove();
            }
        });
        jQuery(document.body).on('updated_checkout', function () {
            if (self.$checkout_form.find('.vi-wcaio-checkout-shipping-replace').length && self.$checkout_form.find('#ship-to-different-address input').length) {
                self.$checkout_form.find('.vi-wcaio-checkout-shipping-replace').removeClass('vi-wcaio-checkout-shipping-replace');
                self.$checkout_form.find('#ship-to-different-address input').trigger('change');
                return false;
            }
            if (self.$wrap.find('.vi-wcaio-sidebar-cart-checkout-wrap:not(.vi-wcaio-disabled)')) {
                self.end_loading();
            }
            if ( jQuery.inArray(jQuery('[name="payment_method"]').filter( ':checked' ).eq(0).val() ,['stripe','woocommerce_payments']) > -1 && jQuery('.wc-upe-form').length && jQuery('.wc-upe-form').parent().find('.woocommerce-SavedPaymentMethods-new').length ){
                jQuery('.wc-upe-form').parent().find('.woocommerce-SavedPaymentMethods-new').remove();
            }
        });
        jQuery(document.body).on('removed_coupon_in_checkout', function () {
            vi_wcaio_show_message(self.$wrap.find('.woocommerce-message').html());
        });
        jQuery(document.body).on('stripeError', function (e, error_message) {
            jQuery('.vi-wcaio-sidebar-cart-overlay, .vi-wcaio-sidebar-cart-close-wrap').removeClass('vi-wcaio-not-hidden');
            jQuery('html, body').stop();
            self.end_loading();
            if (error_message.error.message) {
                vi_wcaio_show_message(error_message.error.message);
            } else if (self.$checkout_form.find('.woocommerce_error').length) {
                vi_wcaio_show_message(self.$checkout_form.find('.woocommerce_error').html());
            }
        });
        jQuery(document.body).on('checkout_error', function (e, error_message) {
            jQuery('.vi-wcaio-sidebar-cart-overlay, .vi-wcaio-sidebar-cart-close-wrap').removeClass('vi-wcaio-not-hidden');
            jQuery('html, body').stop();
            self.end_loading();
            if (error_message) {
                vi_wcaio_show_message(error_message);
            } else if(self.$checkout_form.find('.woocommerce-NoticeGroup-checkout').length) {
                vi_wcaio_show_message(self.$checkout_form.find('.woocommerce-NoticeGroup-checkout').html());
            }
        });
        jQuery(document).on('ajaxSend',function(ev, xhr, settings) {
            if (settings.url && settings.url.search('wc-ajax=update_order_review') > 0) {
                self.start_loading();
                let data = [], data_t='';
                if ( !self.$checkout_form.find('#ship-to-different-address').length) {
                    data.push('viwcaio_need_shipping');
                }
                if ( jQuery('.vi-wcaio-sidebar-cart-footer-coupons').length) {
                    data.push('viwcaio_coupons');
                }
                if ( jQuery('.vi-wcaio-sidebar-cart-footer-cart_total.vi-wcaio-sidebar-cart-footer-total').length) {
                    data.push('viwcaio_cart_total');
                }
                if (data.length){
                    data_t = data[0]+'=1';
                    for (let i = 1; i <data.length ; i++) {
                        data_t += '&'+ data[i]+ '=1';
                    }
                }
                if (data_t){
                    settings.data = settings.data ? settings.data + '&'+ data_t : data_t;
                }
            }
        });
    },
    init_checkout: function () {
        if (viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-nav-step-wrap:not(.vi-wcaio-disabled)').length) {
            viwcaio_checkout.$wrap.addClass('vi-wcaio-sidebar-cart-content-wrap-checkout-navs');
        }
        viwcaio_checkout.switch_step(0, viwcaio_checkout.$wrap);
        jQuery(document.body).trigger('timeslots_loaded');
        if (typeof wc_stripe_upe_params !== 'undefined' && !wc_stripe_upe_params.isPaymentNeeded){
            wc_stripe_upe_params.isPaymentNeeded='1';
        }
    },
    init_checkout_form: function (data, url) {
        jQuery.ajax({
            url: url,
            type: 'POST',
            data: data,
            beforeSend: function () {
                viwcaio_checkout.start_loading();
            },
            success: function (response) {
                if (response && response.status === 'success' && response.html && response.html.indexOf('vi-wcaio-checkout-step') > 0) {
                    wc_checkout_params.update_order_review_nonce = response.update_order_review_nonce;
                    let temp_html = jQuery(response.html);
                    if (temp_html.find('.vi-wcaio-checkout-login-notice').length){
                        viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-step-wrap').prepend('<div class="vi-wcaio-checkout-login-notice"></div>');
                        viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-login-notice').replaceWith(temp_html.find('.vi-wcaio-checkout-login-notice'));
                    }
                    if (temp_html.find('.vi-wcaio-checkout-step-login').length){
                        viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-step-wrap').prepend('<div class="vi-wcaio-checkout-step-login"></div>');
                        viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-step-login').replaceWith(temp_html.find('.vi-wcaio-checkout-step-login'));
                    }
                    if (temp_html.find('.vi-wcaio-checkout-nav-step-wrap').length){
                        viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-step-wrap').prepend('<div class="vi-wcaio-checkout-nav-step-wrap"></div>');
                        viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-nav-step-wrap').replaceWith(temp_html.find('.vi-wcaio-checkout-nav-step-wrap'));
                    }
                    jQuery.each(['.vi-wcaio-checkout-before-checkout-form','.vi-wcaio-checkout-after-checkout-form'], function (k, v) {
                        viwcaio_checkout.$wrap.find(v).replaceWith(temp_html.find(v));
                    });
                    viwcaio_checkout.$wrap.find('.vi-wcaio-sidebar-cart-checkout-wrap').addClass('vi-wcaio-sidebar-cart-checkout-wrap-refresh');
                    viwcaio_checkout.$checkout_form.addClass('vi-wcaio-checkout-refreshed').html(temp_html.find('form.woocommerce-checkout').html());
                    if ( typeof PayPalCommerceGateway !== 'undefined' && !viwcaio_checkout.$checkout_form.find(PayPalCommerceGateway.button.wrapper).length) {
                        viwcaio_checkout.$checkout_form.find('#payment').after('<div id="'+PayPalCommerceGateway.button.wrapper.replace('#', '')+'"></div>');
                    }
                    viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-nav-step-wrap').each(function () {
                        let steps = jQuery(this).find('.vi-wcaio-checkout-nav-step').length;
                        jQuery(this).css({gridTemplateColumns: 'repeat(' + steps + ',1fr)', top: (viwcaio_checkout.$wrap.find('.vi-wcaio-sidebar-cart-header-wrap').outerHeight() + 'px')});
                    });
                    let place_order = viwcaio_checkout.$wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order'), place_order_t = viwcaio_checkout.$checkout_form.find('#place_order');
                    if (place_order.hasClass('vi-wcaio-bt-disabled') && place_order_t.length ){
                        let place_order_text = place_order.data('place_order_text') ||place_order.attr('data-place_order_text') ;
                        place_order_text = place_order_text ? place_order_text.toString() : '';
                        place_order.removeClass('vi-wcaio-bt-disabled').html(place_order_text.replace(/{order_button_text}/g,
                            place_order_t.html() || place_order_t.val() || place_order_t.data('value')));
                    }
                    if (typeof pco_wc_params !== "undefined"){
                        jQuery(document).on('payment_method_selected',function () {
                            if (data.is_paysoncheckout !== 1 && jQuery('input[name="payment_method"]#payment_method_paysoncheckout').prop('checked')){
                                jQuery('input[name="payment_method"]:not(#payment_method_paysoncheckout)').eq(0).trigger('click');
                            }
                        });
                    }
                    jQuery.getScript(jQuery('#wc-checkout-js').attr('src'));
                    if (typeof window.wc_square_credit_card_payment_form_handler !== 'undefined' && typeof window.viwcaio_wc_square_credit_card_payment_form_handler !== 'undefined') {
                        window.wc_square_credit_card_payment_form_handler.constructor(window.viwcaio_wc_square_credit_card_payment_form_handler)
                    }
                    if (typeof window.thwcfe_public_var !== 'undefined' || typeof thwcfe_public_var !== 'undefined' ) {
                        jQuery.getScript(jQuery('#thwcfe-public-checkout-script-js').attr('src'));
                    }
                    viwcaio_checkout.$wrap.find('.vi-wcaio-sidebar-cart-bt-nav-checkout').trigger('click');
                }else {
                    console.log(response)
                }
            },
            complete: function () {
                viwcaio_checkout.end_loading();
            },
            error: function (e) {
                console.log(e)
            }
        });
    },
    open_checkout: function (e) {
        e.preventDefault();
        e.stopPropagation();
        if (!viwcaio_checkout.$wrap.hasClass('vi-wcaio-sidebar-cart-content-wrap-customize') && viwcaio_checkout.$wrap.find('.vi-wcaio-sidebar-cart-pd-empty').length) {
            vi_wcaio_show_message(viwcaio_checkout.unavailable_checkout_text);
        } else if (viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-login-notice-warning').length) {
            vi_wcaio_show_message(viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-login-notice-warning').html());
        } else if (viwcaio_checkout.wc_ajax_url && viwcaio_checkout.$wrap.find('.vi-wcaio-sidebar-cart-checkout-wrap-refresh').length && !viwcaio_checkout.$checkout_form.hasClass('vi-wcaio-checkout-refreshed')) {
            let data = {viwcaio_get_checkout: 1, viwcaio_language: viwcaio_checkout.language};
            viwcaio_checkout.$wrap.trigger('init_checkout_form', [data, viwcaio_checkout.wc_ajax_url.toString().replace('%%endpoint%%', 'viwcaio_get_checkout_form')]);
        }else if (viwcaio_checkout.wc_ajax_url && (!viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-step').length || (!viwcaio_checkout.$checkout_form.length && !viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-login-notice').length))) {
            let data = {viwcaio_get_checkout: 1, viwcaio_language: viwcaio_checkout.language};
            viwcaio_checkout.$wrap.trigger('init_checkout_form', [data, viwcaio_checkout.wc_ajax_url.toString().replace('%%endpoint%%', 'viwcaio_get_checkout_form')]);
        }  else {
            vi_wcaio_sc_toggle('show-checkout');
            viwcaio_checkout.$wrap.trigger('init_checkout');
            viwcaio_checkout.$checkout_form.removeClass('vi-wcaio-checkout-refreshed');
        }
    },
    close_checkout: function () {
        vi_wcaio_sc_toggle('show');
    },
    may_be_switch_step: function () {
        let current_index = viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-step').index(viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-step.vi-wcaio-checkout-step-current'));
        if (jQuery(this).hasClass('vi-wcaio-sidebar-cart-bt-checkout-next')) {
            let total_step = viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-step').length;
            if (current_index === (total_step - 1) && total_step > 1) {
                return false;
            }
            if (viwcaio_checkout.validate()) {
                viwcaio_checkout.switch_step(current_index + 1, viwcaio_checkout.$wrap);
            }
        } else if (jQuery(this).hasClass('vi-wcaio-sidebar-cart-bt-checkout-back')) {
            if (current_index === 0) {
                return false;
            }
            viwcaio_checkout.switch_step(current_index - 1, viwcaio_checkout.$wrap);
        } else {
            let index = viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-nav-step').index(jQuery(this));
            if (index < current_index || jQuery(this).hasClass('vi-wcaio-checkout-step-selected')) {
                viwcaio_checkout.switch_step(index, viwcaio_checkout.$wrap);
            } else if (viwcaio_checkout.validate()) {
                viwcaio_checkout.switch_step(current_index + 1, viwcaio_checkout.$wrap);
            }
        }
    },
    switch_step: function (index, wrap) {
        if (index < 0) {
            return false;
        }
        wrap = jQuery(wrap);
        wrap.find('.vi-wcaio-checkout-step').removeClass('vi-wcaio-checkout-step-current').addClass('vi-wcaio-disabled');
        wrap.find('.vi-wcaio-checkout-step').eq(index).removeClass('vi-wcaio-disabled').addClass('vi-wcaio-checkout-step-current');
        wrap.find('.vi-wcaio-checkout-nav-step.vi-wcaio-checkout-step-current').addClass('vi-wcaio-checkout-step-selected').removeClass('vi-wcaio-checkout-step-current');
        wrap.find('.vi-wcaio-checkout-nav-step').eq(index).addClass('vi-wcaio-checkout-step-current');
        if (wrap.find('.vi-wcaio-checkout-step').eq(index).find('.viwcuf-checkout-funnel-container').length) {
            if (typeof viwcuf_upsell_funnel !== "undefined") {
                viwcuf_upsell_funnel.design(true);
            }
        }
        let total_step = wrap.find('.vi-wcaio-checkout-step').length;
        if (total_step - index > 1) {
            wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').addClass('vi-wcaio-disabled');
            wrap.find('.vi-wcaio-checkout-after-checkout-form').addClass('vi-wcaio-disabled');
            wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-next').removeClass('vi-wcaio-disabled');
        } else {
            if (total_step !== 1 || index === 0) {
                wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-place_order').removeClass('vi-wcaio-disabled');
                wrap.find('.vi-wcaio-checkout-after-checkout-form').removeClass('vi-wcaio-disabled');
            }
            wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-next').addClass('vi-wcaio-disabled');
        }
        if (index > 0) {
            wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-back').removeClass('vi-wcaio-disabled');
            if (wrap.find('.vi-wcaio-checkout-step-login').length && index === 1){
                wrap.find('.vi-wcaio-checkout-before-checkout-form').removeClass('vi-wcaio-disabled');
            }else {
                wrap.find('.vi-wcaio-checkout-before-checkout-form').addClass('vi-wcaio-disabled');
            }
        } else {
            if (!wrap.find('.vi-wcaio-checkout-step-current').hasClass('vi-wcaio-checkout-step-login')){
                wrap.find('.vi-wcaio-checkout-before-checkout-form').removeClass('vi-wcaio-disabled');
            }else {
                wrap.find('.vi-wcaio-checkout-before-checkout-form').addClass('vi-wcaio-disabled');
            }
            wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-back').addClass('vi-wcaio-disabled');
        }
        viwcaio_checkout.$wrap.trigger('set_navs_button_text', [index, wrap]);
        viwcaio_checkout.country_select_select2();
    },
    set_navs_button_text: function (index, wrap) {
        wrap = jQuery(wrap)
        let next_text = '', pre_text = '', bt_pre_text = wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-back').data('pre_text'),
            bt_next_text = wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-next').data('next_text');
        if (!bt_pre_text){
            bt_pre_text = wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-back').attr('data-pre_text') ;
        }
        bt_pre_text = bt_pre_text ? bt_pre_text.toString() : '';
        if (!bt_next_text){
            bt_next_text = wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-next').attr('data-next_text') ;
        }
        bt_next_text = bt_next_text ? bt_next_text.toString() : '';
        if (bt_next_text.indexOf('{next_title}') !== -1) {
            next_text = index === 0 && wrap.find('.vi-wcaio-checkout-step').eq(index).data('next_title') ? wrap.find('.vi-wcaio-checkout-step').eq(index).data('next_title') : wrap.find('.vi-wcaio-checkout-step').eq(index + 1).data('step_title');
            wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-next').html(bt_next_text.replace(/{next_title}/g, next_text));
        }
        if (bt_pre_text.indexOf('{pre_title}') !== -1) {
            pre_text = index > 0 ? wrap.find('.vi-wcaio-checkout-step').eq(index - 1).data('step_title') : '';
            wrap.find('.vi-wcaio-sidebar-cart-bt-checkout-back').html(bt_pre_text.replace(/{pre_title}/g, pre_text));
        }
    },
    country_select_select2: function () {
        if (viwcaio_checkout.$wrap.find('select:visible:not(.select2-hidden-accessible)').length) {
            jQuery(document.body).trigger('country_to_state_changed');
        }
    },
    validate: function () {
        if (!viwcaio_checkout.$wrap.hasClass('vi-wcaio-sidebar-cart-content-wrap-customize')) {
            viwcaio_checkout.$wrap.find('.form-row').removeClass('woocommerce-invalid woocommerce-invalid-required-field woocommerce-invalid-email woocommerce-validated');
            viwcaio_checkout.$wrap.find('.vi-wcaio-not-validate').removeClass('vi-wcaio-not-validate');
            let ship_to_different_address = viwcaio_checkout.$checkout_form.find('.vi-wcaio-checkout-step.vi-wcaio-checkout-step-current #ship-to-different-address input'),
                createaccount = viwcaio_checkout.$checkout_form.find('.vi-wcaio-checkout-step.vi-wcaio-checkout-step-current input#createaccount');
            if (ship_to_different_address.length && !ship_to_different_address.is(':checked')) {
                ship_to_different_address.closest('.woocommerce-shipping-fields').find('.input-text, select, input:checkbox').addClass('vi-wcaio-not-validate');
            }
            if (createaccount.length && !createaccount.is(':checked')) {
                viwcaio_checkout.$checkout_form.find('.vi-wcaio-checkout-step.vi-wcaio-checkout-step-current .create-account').find('.input-text, select, input:checkbox').addClass('vi-wcaio-not-validate');
            }
            if (viwcaio_checkout.$checkout_form.find('.vi-wcaio-checkout-step.vi-wcaio-checkout-step-current .awcfe_cl_hidden ').length) {
                viwcaio_checkout.$checkout_form.find('.vi-wcaio-checkout-step.vi-wcaio-checkout-step-current .awcfe_cl_hidden').find('.input-text, select, input:checkbox').addClass('vi-wcaio-not-validate');
            }
            viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-step.vi-wcaio-checkout-step-current').find('.input-text:not(.vi-wcaio-not-validate), select:not(.vi-wcaio-not-validate), input:checkbox:not(.vi-wcaio-not-validate)').trigger('validate');
            if (viwcaio_checkout.$wrap.find('.vi-wcaio-checkout-step.vi-wcaio-checkout-step-current .woocommerce-invalid').length) {
                vi_wcaio_show_message(viwcaio_checkout.invalid_text);
                return false;
            }
        }
        return true;
    },
    start_loading: function () {
        jQuery(' .vi-wcaio-sidebar-cart-loading-wrap').removeClass('vi-wcaio-disabled');
    },
    end_loading: function () {
        jQuery(' .vi-wcaio-sidebar-cart-loading-wrap').addClass('vi-wcaio-disabled');
    }
};