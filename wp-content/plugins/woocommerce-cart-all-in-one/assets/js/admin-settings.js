jQuery(document).ready(function () {
    'use strict';
    viwcaio_init_tab();
    jQuery('.vi-ui.dropdown').unbind().dropdown();
    jQuery('.vi-ui.checkbox').unbind().checkbox();
    jQuery('input[type="checkbox"]').unbind().on('change', function () {
        if (jQuery(this).prop('checked')) {
            jQuery(this).parent().find('input[type="hidden"]').val('1');
            if (jQuery(this).hasClass('vi-wcaio-sc_icon_enable-checkbox')) {
                jQuery('.vi-wcaio-sc_icon_enable-enable').removeClass('vi-wcaio-disabled');
                jQuery('#vi-wcaio-sc_content_class_open').removeAttr('required');
            }
            if (jQuery(this).hasClass('vi-wcaio-ajax_atc-checkbox')) {
                jQuery('.vi-wcaio-ajax_atc-enable').removeClass('vi-wcaio-disabled');
            }
            if (jQuery(this).hasClass('vi-wcaio-pd_variable_bt_atc_text_enable-checkbox')) {
                jQuery('.vi-wcaio-pd_variable_bt_atc_text_enable-enable').removeClass('vi-wcaio-disabled');
            }
            if (jQuery(this).hasClass('vi-wcaio-sb_enable-checkbox')) {
                jQuery('.vi-wcaio-sb_enable-enable').removeClass('vi-wcaio-disabled');
            }
        } else {
            jQuery(this).parent().find('input[type="hidden"]').val('');
            if (jQuery(this).hasClass('vi-wcaio-sc_icon_enable-checkbox')) {
                jQuery('.vi-wcaio-sc_icon_enable-enable').addClass('vi-wcaio-disabled');
                jQuery('#vi-wcaio-sc_content_class_open').prop('required',true);
            }
            if (jQuery(this).hasClass('vi-wcaio-ajax_atc-checkbox')) {
                jQuery('.vi-wcaio-ajax_atc-enable').addClass('vi-wcaio-disabled');
            }
            if (jQuery(this).hasClass('vi-wcaio-pd_variable_bt_atc_text_enable-checkbox')) {
                jQuery('.vi-wcaio-pd_variable_bt_atc_text_enable-enable').addClass('vi-wcaio-disabled');
            }
            if (jQuery(this).hasClass('vi-wcaio-sb_enable-checkbox')) {
                jQuery('.vi-wcaio-sb_enable-enable').addClass('vi-wcaio-disabled');
            }
        }
        if (jQuery(this).hasClass('vi-wcaio-vp_slider_loop-checkbox') || jQuery(this).hasClass('vi-wcaio-vp_slider_auto-checkbox') || jQuery(this).hasClass('vi-wcaio-vp_slider_pause-checkbox')) {
            viwcaio_create_shortcode();
        }
    });
    jQuery('input[type = "number"]').unbind().on('blur', function () {
        let new_val, min = parseFloat(jQuery(this).attr('min')) || 0,
            max = parseFloat(jQuery(this).attr('max')),
            val = parseFloat(jQuery(this).val()) || 0;
        new_val = val;
        if (min > val) {
            new_val = min;
        }
        if (max && max < val) {
            new_val = max;
        }
        jQuery(this).val(new_val).trigger('change');
    });
    jQuery('.vi-wcaio-search-select2:not(.vi-wcaio-search-select2-init)').each(function () {
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
        select.addClass('vi-wcaio-search-select2-init').select2(viwcaio_select2_params(placeholder, action, close_on_select, min_input));
    });
    jQuery(document).on('click', '.vi-wcaio-vp-shortcode-bt-copy', function () {
        var value = jQuery('.vi-wcaio-vp-shortcode').html();
        var $temp = jQuery("<input>");
        jQuery("body").append($temp);
        $temp.val(value).select();
        document.execCommand("copy");
        $temp.remove();
        jQuery('.vi-wcaio-vp-shortcode-copied').removeClass('vi-wcaio-disabled');
        setTimeout(function () {
            jQuery('.vi-wcaio-vp-shortcode-copied').addClass('vi-wcaio-disabled');
        }, 5000);
        event.stopPropagation();
    });
    jQuery(document).on('change', '#vi-wcaio-vp_title, #vi-wcaio-vp_pd_limit, #vi-wcaio-vp_pd_column, #vi-wcaio-vp_slider_move, #vi-wcaio-vp_slider_speed', function () {
        viwcaio_create_shortcode();
    });
    //Auto update
    jQuery('.villatheme-get-key-button').one('click', function (e) {
        let v_button = jQuery(this);
        v_button.addClass('loading');
        let data = v_button.data();
        let item_id = data.id;
        let app_url = data.href;
        let main_domain = window.location.hostname;
        main_domain = main_domain.toLowerCase();
        let popup_frame;
        e.preventDefault();
        let download_url = v_button.attr('data-download');
        popup_frame = window.open(app_url, "myWindow", "width=380,height=600");
        window.addEventListener('message', function (event) {
            /*Callback when data send from child popup*/
            let obj = jQuery.parseJSON(event.data);
            let update_key = '';
            let message = obj.message;
            let support_until = '';
            let check_key = '';
            if (obj['data'].length > 0) {
                for (let i = 0; i < obj['data'].length; i++) {
                    if (obj['data'][i].id == item_id && (obj['data'][i].domain == main_domain || obj['data'][i].domain == '' || obj['data'][i].domain == null)) {
                        if (update_key == '') {
                            update_key = obj['data'][i].download_key;
                            support_until = obj['data'][i].support_until;
                        } else if (support_until < obj['data'][i].support_until) {
                            update_key = obj['data'][i].download_key;
                            support_until = obj['data'][i].support_until;
                        }
                        if (obj['data'][i].domain == main_domain) {
                            update_key = obj['data'][i].download_key;
                            break;
                        }
                    }
                }
                if (update_key) {
                    check_key = 1;
                    jQuery('.villatheme-autoupdate-key-field').val(update_key);
                }
            }
            v_button.removeClass('loading');
            if (check_key) {
                jQuery('<p><strong>' + message + '</strong></p>').insertAfter(".villatheme-autoupdate-key-field");
                jQuery(v_button).closest('form').submit();
            } else {
                jQuery('<p><strong> Your key is not found. Please contact support@villatheme.com </strong></p>').insertAfter(".villatheme-autoupdate-key-field");
            }
        });
    });
});

function viwcaio_create_shortcode() {
    let title = jQuery('#vi-wcaio-vp_title').val() || '',
        display = jQuery('#vi-wcaio-vp_pd_limit').val() || '',
        columns = jQuery('#vi-wcaio-vp_pd_column').val() || '',
        loop = jQuery('#vi-wcaio-vp_slider_loop').val() || '',
        move = jQuery('#vi-wcaio-vp_slider_move').val() || '',
        auto_play = jQuery('#vi-wcaio-vp_slider_auto').val() || '',
        speed = jQuery('#vi-wcaio-vp_slider_speed').val() || '',
        pause = jQuery('#vi-wcaio-vp_slider_pause').val() || '';
    let shortcode = '[vi_wcaio_viewed_product class="" title="' + title + '"  display="' + display + '" columns="' + columns + '" loop="' + loop + '" move="' + move + '" auto_play="' + auto_play + '" speed="' + speed + '" pause="' + pause + '" ]';
    jQuery('.vi-wcaio-vp-shortcode').html(shortcode);
}

function viwcaio_select2_params(placeholder, action, close_on_select, min_input) {
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

function viwcaio_init_tab(tab_default = 'sidebar_cart') {
    jQuery('.vi-ui.vi-ui-main.tabular.menu .item').vi_tab({
        history: true,
        historyType: 'hash'
    });
    /*Setup tab*/
    let tabs,
        tabEvent = false,
        initialTab = tab_default,
        navSelector = '.vi-ui.vi-ui-main.menu';
    // Initializes plugin features
    jQuery.address.strict(false).wrap(true);

    if (jQuery.address.value() == '') {
        jQuery.address.history(false).value(initialTab).history(true);
    }
    // Address handler
    jQuery.address.init(function (event) {

        // Tabs setup
        tabs = jQuery('.vi-ui.vi-ui-main.menu')
            .vi_tab({
                history: true,
                historyType: 'hash'
            });

        // Enables the plugin for all the tabs
        jQuery(navSelector + ' a').on('click', function (event) {
            tabEvent = true;
            tabEvent = false;
            return true;
        });

    });
}