jQuery(document).ready(function ($) {
    'use strict';
    jQuery('.vi-wcaio-vp-wrap:not(.vi-wcaio-vp-wrap-init)').each(function () {
        viwcaio_flexslider(jQuery(this));
    });
    jQuery(document).ajaxComplete(function (event, jqxhr, settings) {
        jQuery('.vi-wcaio-vp-wrap:not(.vi-wcaio-vp-wrap-init)').each(function () {
            viwcaio_flexslider(jQuery(this));
        });
        return false;
    });

});
jQuery(window).on('load',function () {
    'use strict';
    jQuery('.vi-wcaio-vp-wrap:not(.vi-wcaio-vp-wrap-init)').each(function () {
        viwcaio_flexslider(jQuery(this));
    });
});

function viwcaio_flexslider(wrap) {
    wrap = jQuery(wrap);
    wrap.addClass('vi-wcaio-vp-wrap-init');
    let rtl = false;
    if (wrap.hasClass('vi-wcaio-vp-wrap-rtl')){
        rtl = true;
    }
    let wrap_slide = wrap.find('.vi-wcaio-vp-slider');
    let wrap_width = wrap.innerWidth(),
        colums = parseInt(wrap_slide.data('columns') || 4),
        colums_mobile = parseInt(wrap_slide.data('colums_mobile') || 1),
        loop = wrap_slide.data('loop') == 1,
        move = parseInt(wrap_slide.data('move') || 1),
        auto_play = wrap_slide.data('auto_play') == 1,
        speed = wrap_slide.data('speed') || 2000,
        pause = wrap_slide.data('pause') == 1;
    if (wrap_width < 600 && wrap_width >= 480) {
        colums = 3;
    }
    if (wrap_width < 480) {
        colums = colums_mobile;
    }
    move = move > colums ? colums : move;
    let itemWidth = (wrap_width - 12 * colums) / colums;
    wrap_slide.removeData("flexslider");
    wrap_slide.viwcaio_flexslider({
        namespace: 'vi-wcaio-slider-',
        selector: '.vi-wcaio-vp-products .vi-wcaio-vp-product',
        animation: 'slide',
        animationLoop: loop,
        itemWidth: itemWidth,
        itemMargin: 12,
        controlNav: false,
        maxItems: colums,
        reverse: false,
        rtl: rtl,
        slideshow: auto_play,
        pauseOnHover: pause,
        move: move,
        touch: true,
        slideshowSpeed: speed
    }).addClass('vi-wcaio-vp-slider-init');
}