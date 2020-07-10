/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'jquery',
    'underscore',
    'jquery-ui-modules/widget',
    'Magento_Ui/js/modal/confirm',
    'mage/translate'
], function ($, _) {
    'use strict';

    $.widget('mage.alert', $.mage.confirm, {
        options: {
            modalClass: 'confirm',

            title: $.mage.__('Atención'),
            actions: {

                /**
                 * Callback always - called on all actions.
                 */
                always: function () {}
            },
            buttons: [{
                text: $.mage.__('OK'),
                class: 'action-primary action-accept',

                /**
                 * Click handler.
                 */
                click: function () {
                    this.closeModal(true);
                }
            }]
        },

        /**
         * Create widget.
         */
        _create: function () {
            this.options.actions.always();
            this._super();
        },

        /**
         * Close modal window.
         */
        closeModal: function () {
            this.element.bind('alertclosed', _.bind(this._remove, this));

            return this._super();
        }
    });

    return function (config) {
        return $('<div></div>').html(config.content).alert(config);
    };
});
