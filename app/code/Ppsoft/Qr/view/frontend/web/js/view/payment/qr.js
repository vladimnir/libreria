define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'qr',
                component: 'Ppsoft_Qr/js/view/payment/method-renderer/qr-method'
            }
        );
        return Component.extend({});
    }
);