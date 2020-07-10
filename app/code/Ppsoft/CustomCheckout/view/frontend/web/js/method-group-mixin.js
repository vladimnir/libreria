define(['mage/translate'], function ($t) {
    "use strict";

    return function (target) {
        return target.extend({
            /**
             * @return {*}
             */
            defaults: {
                title: $t('Selecciona el Método de Pago')

            }
        });
    }
});