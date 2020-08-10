require([
    'jquery',
    'domReady!'
], function($){

    $(document).ready(function() {
        var width = $(window).width();

        //mobile
        if (width < 768){

            $('.nav-sections-items #store\\.menu .navigation ul li ul.level0 > li.level1').click(function(event){
                $(this).find('> .submenu').toggle();
                $('.nav-sections-items #store\\.menu .navigation ul li ul.level0 > li.level1').not(this).find('> .submenu').hide();

            });
            $('.nav-sections-items #store\\.menu .navigation ul li ul.level0 > li.level1 #ul-level1 > .level2 > a').click(function(event){
                event.stopPropagation();
                event.preventDefault();
                $(this).parent().find('> .submenu').toggle();
                $('.nav-sections-items #store\\.menu .navigation ul li ul.level0 > li.level1 #ul-level1 > .level2 > a').not(this).parent().find('> .submenu').hide();
            });
            var widthbanner =  $('.banner-text-mobile').outerHeight()/2;
            var topbanner = $('.banner-container .banner-mobile img').outerHeight()/2;
            $('.banner-text-mobile').css('top', (topbanner - widthbanner));
            $(window).resize(function(){
                $('.banner-text-mobile').css('top', (topbanner - widthbanner));
            });
            $('.footer-mobile .container .column ul .main').click(function() {
                $(this).siblings().find('a').toggle();
                $(this).find('> .title').toggleClass('active');
            });
            $('.navigation ul li ul > li > a').click( function () {
                $('.navigation ul li ul > li > a').not(this).closest('li').removeClass('active-li');
                $(this).closest('ul').toggleClass('active-ul');
                $(this).closest('li').toggleClass('active-li');
            });
        }
        //desktop
        var opcloaded = setInterval(function() {
            if ($('#co-shipping-method-form').length) {
                clearInterval(opcloaded);
                console.log('existe opc');
                if ($('.checkout-container #userlogged .opc .checkout-shipping-address .step-content .addresses .control .shipping-address-items').length) {
                    console.log('existe form');
                    $('#opc-shipping_method').css('margin-top', '0');
                }
            }
        }, 100);

        $('.add-protocolo').on( "click", function(){
            $('.proto-img-dk').toggle();
            $('.cross-protocolo-desk').toggle();
        });
        $('.add-protocolo-mobile').on( "click", function(){
            $('.proto-img-mb').toggle();
            $('.cross-protocolo-mobile').toggle();
        });
        $('.cross-protocolo-desk').on( "click", function(){
            $('.proto-img-dk').hide();
            $('.cross-protocolo-desk').hide();
        });
        $('.cross-protocolo-mobile').on( "click", function(){
            $('.proto-img-mb').hide();
            $('.cross-protocolo-mobile').hide();
        });

        $('.pagar-img').on( "click", function(){
            $('.popup-modal').toggle();
        });
        $('.cross-modal').on( "click", function(){
            $('.popup-modal').toggle();
        });

        var existCondition2 = setInterval(function() {
            if ($('.checkout-shipping-method').length) {
                clearInterval(existCondition2);
                if (!$('.form-login').length) {
                    $('#opc-shipping_method').addClass('logged');
                }
            }
        }, 100);

        $('.product #disponible\\.tab div .switcher-language .switcher-options .switcher-trigger strong').on( "click", function(){
            $(this).toggleClass('uparrow');
            $('.content .disponible-en').toggle();
        });
        if (width > 768){
            $('.navigation ul li ul > li').hover( function (e) {
                $(this).closest('.submenu').toggleClass('active-ul', e.type === 'mouseenter');
                $(this).toggleClass('active-li', e.type === 'mouseenter');
            });
        }
        $('#link-auth').on( "click", function(){
            $('#menulogin').toggle();
        });
        var existCondition = setInterval(function() {
            if ($('#checkout-shipping-method-load').length) {
                clearInterval(existCondition);
                $("#checkout-step-pas").detach().appendTo('#checkout-shipping-method-load');
                $(".store-pickup").detach().appendTo('#checkout-step-pas');
            }
        }, 100);

        $(document).on("click", "#checkout-shipping-method-load .table-checkout-shipping-method tbody tr .col-method > .radio", function () {

            $(this).attr('checked', true);
            $("#checkout-step-pas .pas #pas-yes").removeAttr('checked');
            if (!$("#checkout-step-pas .pas #pas-yes").prop('checked')) {
                $(".store-pickup").css({"display": "none"});
            }
        });

        $('#products-dropdown').on( "click", function(){
            $(this).toggleClass('product-active');
            $('.custom-sidebar .container-products .product-content').toggle();

        });
        $('#categorías').click(function(){
            var level0 = $('.navigation .ui-menu .ui-menu-item > ul.level0');
            level0.toggle();
            var level1 = $('.navigation .ui-menu .ui-menu-item ul.level1');
            level1.hide();

        });

        var mediatop = $('.slick-active div #media-container > img').outerHeight()/2;
        $('.slick-active div #media-container > img').css({"position": "relative", "top": 'calc(50% - ' + mediatop+ 'px)' });
        $('#nosubscription').click(function() {
            $('#subscription').prop('checked', false);
        });
        $('#close-ciudades').click(function(){
            $('.ciudades').hide();
        });

        $('#close-ciudades-mobile').click(function(){
            $('.acepto').hide();
        });
        $('.acepto-btn').click(function(){
            $('.acepto').hide();
        });
        $('.customer-menu > .header.links').clone().appendTo('#store\\.links');
        $(".nav-sections-items #store\\.settings > #switcher-language-nav").clone().appendTo('#store\\.menu');
        $('.cart-container .form-cart').clone().appendTo('#editar');
        $('.cart-container .form-cart .main .update span').html('Actualizar');
        $('.servicios #checkbox-servicios').click(function(){
            $(this).toggleClass("checked");
        });

        if (! localStorage.noFirstVisit) {
            $('.ciudades-container').css('display', 'block');
            localStorage.noFirstVisit = "1";
        }
        var userAgent = navigator.userAgent || navigator.vendor || window.opera;
        if (/android/i.test(userAgent)) {
            $('#android').css('display', 'inline-block');
        }
        else {
            $('#ios').css('display', 'inline-block');
        }
        $('.filters-block').click(function(){
            $('.sidebar-main').toggle();
            $('#narrow-by-list').toggle();
        });
        $('.grid-block').on( "click", function(){
            $('.recent-container').toggleClass('list-view');
            $('.grid-block').toggleClass('list-active');
        });
        $('.before-up').on( "click", function(){
            var num = parseInt(($(this).siblings('.input-text').val())) + parseInt(1);
            var qty = $(this).data("max-qty");
            if (qty >= num) {
                $(this).siblings('.input-text').val(num);
            }
        });
        $('.after-down').on( "click", function(){
            var num = parseInt(($(this).siblings('.input-text').val())) - parseInt(1);
            if (num > 0) {
                $(this).siblings('.input-text').val(num);
            }
        });
    });
});