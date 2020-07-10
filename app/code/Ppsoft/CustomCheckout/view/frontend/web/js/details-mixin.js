define([
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils'
], function(
    quote,
    priceUtils
) {
    'use strict';

    return function (target) {
        return target.extend({
            /**
             * @param {*} price
             * @return {*|String}
             */
            getFormattedPrice: function (price) {
                return priceUtils.formatPrice(price, quote.getPriceFormat());
            }
        });
    }
});