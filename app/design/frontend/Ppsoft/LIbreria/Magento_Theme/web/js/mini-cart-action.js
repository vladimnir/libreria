define([
    'jquery'
], function ($) {
    return function () {
      /*  $('[data-block="minicart"]').on('contentLoading', function () {
            $('[data-block="minicart"]').on('contentUpdated', function ()  {
                // $('html, body').animate({ scrollTop: 0 }, 'slow');

                    $('#popup-addtocart').show();
            });
        });*/
        $(document).on('ajax:addToCart', function () {
            $('#popup-addtocart').show();
        });
    }
});