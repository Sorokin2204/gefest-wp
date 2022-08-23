jQuery(function ($) {
  $('.other-radio').change(() => {
    $('input[type=hidden][name=changeCategory]').val('false');
    callFilter();
  });
  $('.category-radio').change(() => {
    $('input[type=hidden][name=changeCategory]').val('true');
    $('.other-radio').prop('checked', false);
    callFilter();
  });
  $('.filter-reset').click(() => {
    $('.spollers__body').attr('hidden', '');
    $('.spollers__title').removeClass('_spoller-active');

    $('.category-radio').prop('checked', false);
    $('input[type=hidden][name=changeCategory]').val('true');
    $('.other-radio').prop('checked', false);
    callFilter();
  });

  function callFilter() {
    var filter = $('#filter');
    $.ajax({
      url: filter.attr('action'),
      data: filter.serialize(), // form data
      type: filter.attr('method'), // POST
      beforeSend: function (xhr) {
        var loader = $('.lds-spinner');
        loader.css('display', 'inline-block');

        $('input[type=radio]').prop('disabled', true);
        $('.spollers__title ').prop('disabled', true);
      },
      success: function (data) {
        const dataParse = JSON.parse(data);
        var loader = $('.lds-spinner');
        loader.css('display', 'none');
        const isChangeCategory = $('input[type=hidden][name=changeCategory]').val();
        $('input[type=radio]').prop('disabled', false);
        $('.spollers__title').prop('disabled', false);

        if (dataParse.html_forma_options) {
          if (isChangeCategory === 'true') {
            $('#filter_forma_options').html(dataParse.html_forma_options);
          }

          $('#filter_forma_options_btn').removeClass('spollers__title--disable');
        } else {
          $('#filter_forma_options_btn').addClass('spollers__title--disable');
        }

        if (dataParse.html_paint_options) {
          if (isChangeCategory === 'true') {
            $('#filter_paint_options').html(dataParse.html_paint_options);
          }

          $('#filter_paint_options_btn').removeClass('spollers__title--disable');
        } else {
          $('#filter_paint_options_btn').addClass('spollers__title--disable');
        }

        if (dataParse.html_thickness_options) {
          if (isChangeCategory === 'true') {
            $('#filter_thickness_options').html(dataParse.html_thickness_options);
          }
          $('#filter_thickness_options_btn').removeClass('spollers__title--disable');
        } else {
          $('#filter_thickness_options_btn').addClass('spollers__title--disable');
        }

        if (dataParse.html_products) {
          $('.products-not-found').css('display', 'none');
        } else {
          $('.products-not-found').css('display', 'block');
        }
        $('#products').html(dataParse.html_products);
        $('#response').html();
        $('.other-radio').unbind();
        $('.other-radio').change(() => {
          $('input[type=hidden][name=changeCategory]').val('false');
          callFilter();
        });
      },
    });
    return false;
  }

  callFilter();
});
