define([
    "jquery"
], function($){
    "use strict";

    function main(config, element) {
        var AjaxUrl = config.AjaxUrl;

        $(document).ready(function(){
            setTimeout(function(){
                $.ajax({
                    context: '.compare .wrapper',
                    url: AjaxUrl,
                    type: "GET",
                }).done(function (data) {
                    var productIds = data.productIds;
                    $.each( productIds, function( key, productId ) {
                        var comparisonId = "#compare-link-"+productId;
                        var comparisonRemoveId = "#compare-remove-link-"+productId;
                        $(comparisonId).hide();
                        $(comparisonRemoveId).css("display","block");
                    });
                    return true;
                });
            },100);
        });

    };
    return main;
});