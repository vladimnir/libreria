define(['jquery', 'Magento_Ui/js/modal/modal', 'mage/loader'], function ($, modal, loader) {
    'use strict';
    return function (config, node) {
        var product_id = config.id;
        var product_name = "Tienda "+config.name;
        var product_url = config.url;
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: false,
            title: $.mage.__(product_name),
            size: 'sm',
            buttons: [{
                text: $.mage.__('Cerrar >'), class: 'close-modal', click: function () {
                    this.closeModal();
                }
            }]
        };

        var value = "#quickViewContainer" + product_id;
        if ($(value).length != 0) {
            var popup = modal(options, $(value));
            // $(value).modal('openModal');
        }
        var click = '#quickViewButton' + product_id;

        $(click).on("click", function () {
            openQuickViewModal();

        });
        var openQuickViewModal = function () {
            var modalContainer = $(value);
            modalContainer.html(createIframe());
            var iframearea = "#new_frame" + product_id;

            $(iframearea).on("load", function () {
                modalContainer.addClass("product-quickview");
                modalContainer.modal('openModal');

            });
        };

        var createIframe = function () {
            return $('<iframe />', { id: 'new_frame' + product_id, src: product_url, width: 900, height: 450 });
        }
    };
});
