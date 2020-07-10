/**
 * Widget to select options on landing epson
 * IMPORTANT: Remove this file is landing epson is not working
 */

define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

     let _element;

    $.widget('dismac.EpsonFeaturePrints', {
        options: {
            cssClassCurrentElement: 'ot-current-option'
        },

        _create: function () {
            this._element = this.element;
            this.selectOptions();
        },

        _hideAllOptions: function(element){
            let self = this;
            $(element).find('li.option').removeClass(self.options.cssClassCurrentElement);
        },

        _showOptionClicked: function(element){
            let self = this;
            $(element).addClass(self.options.cssClassCurrentElement);
        },

        selectOptions : function () {
            let self = this;
            $(self._element).find('li.option a.ot-option-btn').on('click', function(event){
                event.preventDefault();
                self._hideAllOptions(self._element);
                self._showOptionClicked($(this).parent());
            });

        }
    });

    return $.dismac.EpsonFeaturePrints;
});
