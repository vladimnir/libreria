var config = {
    map: {
        '*': {
            'Magento_Checkout/template/onepage.html':
                'Ppsoft_CustomCheckout/template/onepage.html',
            'Magento_Checkout/template/progress-bar.html':
                'Ppsoft_CustomCheckout/template/progress-bar.html',
            'Magento_Checkout/template/summary.html':
                'Ppsoft_CustomCheckout/template/summary.html',
            'ui/template/form/field.html':
                'Ppsoft_CustomCheckout/template/form/field.html',
            'Magento_Checkout/template/form/element/email.html':
                'Ppsoft_CustomCheckout/template/form/element/email.html',
            'Magento_Checkout/template/shipping.html':
                'Ppsoft_CustomCheckout/template/shipping.html',
           /* 'Magento_Checkout/js/view/shipping':
                'Ppsoft_CustomCheckout/js/view/shipping',*/
            'Magento_OfflinePayments/template/payment/banktransfer.html':
                'Ppsoft_CustomCheckout/template/payment/banktransfer.html',
            'Magento_OfflinePayments/template/payment/cashondelivery.html':
                'Ppsoft_CustomCheckout/template/payment/cashondelivery.html',
            'Magento_OfflinePayments/template/payment/checkmo.html':
                'Ppsoft_CustomCheckout/template/payment/checkmo.html',
            'Wyomind_PickupAtStore/template/shipping.html':
                'Ppsoft_CustomCheckout/template/wyomind/shipping.html',
            'Wyomind_PickupAtStore/js/view/shipping':
                'Ppsoft_CustomCheckout/js/view/wyomind/shipping',
            'Magento_Checkout/js/view/minicart':
                'Ppsoft_CustomCheckout/js/view/minicart'
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/model/payment/method-group': {
                'Ppsoft_CustomCheckout/js/method-group-mixin': true
            },
            'Magento_Checkout/js/view/summary/item/details': {
                'Ppsoft_CustomCheckout/js/details-mixin': true
            },
            /*'Magento_Checkout/js/view/shipping': {
                'Ppsoft_CustomCheckout/js/view/wyomind/shipping-mixin': true
            },*/
            'Magento_Checkout/js/view/summary/abstract-total': {
                'Ppsoft_CustomCheckout/js/view/summary/abstract-total-mixin': true
            }
        }
    }
};