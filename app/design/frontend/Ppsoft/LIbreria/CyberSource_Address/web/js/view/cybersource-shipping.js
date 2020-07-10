/**
 * Copyright © 2018 CyberSource. All rights reserved.
 * See LICENSE.txt for license details.
 */
/*global define*/
define(
    [
        'jquery',
        'underscore',
        'Magento_Ui/js/form/form',
        'ko',
        'Magento_Customer/js/model/customer',
        'Magento_Customer/js/model/address-list',
        'Magento_Checkout/js/model/address-converter',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/action/create-shipping-address',
        'Magento_Checkout/js/action/select-shipping-address',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/model/shipping-address/form-popup-state',
        'Magento_Checkout/js/model/shipping-service',
        'Magento_Checkout/js/action/select-shipping-method',
        'Magento_Checkout/js/model/shipping-rate-registry',
        'Magento_Checkout/js/action/set-shipping-information',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Ui/js/modal/modal',
        'Magento_Checkout/js/model/checkout-data-resolver',
        'Magento_Checkout/js/checkout-data',
        'uiRegistry',
        'mage/translate',
        'mage/url',
        'Magento_Checkout/js/model/shipping-rate-service'
    ],
    function (
        $,
        _,
        Component,
        ko,
        customer,
        addressList,
        addressConverter,
        quote,
        createShippingAddress,
        selectShippingAddress,
        shippingRatesValidator,
        formPopUpState,
        shippingService,
        selectShippingMethodAction,
        rateRegistry,
        setShippingInformationAction,
        stepNavigator,
        modal,
        checkoutDataResolver,
        checkoutData,
        registry,
        $t,
        urlBuilder
    ) {
        'use strict';

        var popUp = null;

        return Component.extend({
            defaults: {
                template: 'Wyomind_PickupAtStore/shipping',
                shippingFormTemplate: 'Magento_Checkout/shipping-address/form',
                shippingMethodListTemplate: 'Magento_Checkout/shipping-address/shipping-method-list',
                shippingMethodItemTemplate: 'Magento_Checkout/shipping-address/shipping-method-item'
            },
            visible: ko.observable(!quote.isVirtual()),
            errorValidationMessage: ko.observable(false),
            isCustomerLoggedIn: customer.isLoggedIn,
            isFormPopUpVisible: formPopUpState.isVisible,
            isFormInline: addressList().length === 0,
            isNewAddressAdded: ko.observable(false),
            saveInAddressBook: 1,
            quoteIsVirtual: quote.isVirtual(),
            normalizationData: null,
            addressModal: null,
            
            /**
             * @return {exports}
             */
            initialize: function () {
                var self = this,
                    hasNewAddress,
                    fieldsetName = 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset';

                this._super();

                if (!quote.isVirtual()) {
                    stepNavigator.registerStep(
                        'shipping',
                        '',
                        $t('Shipping'),
                        this.visible,
                        _.bind(this.navigate, this),
                        10
                    );
                }
                checkoutDataResolver.resolveShippingAddress();

                hasNewAddress = addressList.some(function (address) {
                    return address.getType() == 'new-customer-address';
                });

                this.isNewAddressAdded(hasNewAddress);

                this.isFormPopUpVisible.subscribe(function (value) {
                    if (value) {
                        self.getPopUp().openModal();
                    }
                });

                quote.shippingMethod.subscribe(function () {
                    self.errorValidationMessage(false);
                });

                registry.async('checkoutProvider')(function (checkoutProvider) {
                    var shippingAddressData = checkoutData.getShippingAddressFromData();

                    if (shippingAddressData) {
                        checkoutProvider.set(
                            'shippingAddress',
                            $.extend({}, checkoutProvider.get('shippingAddress'), shippingAddressData)
                        );
                    }
                    checkoutProvider.on('shippingAddress', function (shippingAddressData) {
                        checkoutData.setShippingAddressFromData(shippingAddressData);
                    });
                    shippingRatesValidator.initFields(fieldsetName);
                });
                return this;
            },

            /**
             * Load data from server for shipping step
             */
            navigate: function (step) {
                step && step.isVisible(true);
            },

            /**
             * @return {*}
             */
            getPopUp: function () {
                var self = this,
                    buttons;

                if (!popUp) {
                    buttons = this.popUpForm.options.buttons;
                    this.popUpForm.options.buttons = [
                        {
                            text: buttons.save.text ? buttons.save.text : $t('Save Address'),
                            class: buttons.save.class ? buttons.save.class : 'action primary action-save-address',
                            click: self.saveNewAddress.bind(self)
                        },
                        {
                            text: buttons.cancel.text ? buttons.cancel.text : $t('Cancel'),
                            class: buttons.cancel.class ? buttons.cancel.class : 'action secondary action-hide-popup',
                            click: function () {
                                this.closeModal();
                            }
                        }
                    ];
                    this.popUpForm.options.closed = function () {
                        self.isFormPopUpVisible(false);
                    };
                    popUp = modal(this.popUpForm.options, $(this.popUpForm.element));
                }
                
                $('#country_id').hide();
                $('[name="city"]').val(ShippingHomeDelivery.currentStore.name).change();
                $('[name="city"]').prop('disabled', true);
                
                if (ShippingHomeDelivery.isCustomerLogged && typeof(ShippingHomeDelivery.customerCi)) {
                    $("[name='custom_attributes[numero_documento]']").val(ShippingHomeDelivery.customerCi).change().prop('disabled', true);                    
                    $("[name='custom_attributes[tipo_documento]']").val(ShippingHomeDelivery.customerExt).change().prop('disabled', true);
                    
                    $("[name='custom_attributes[apellido_materno]']").val(ShippingHomeDelivery.customerApellidoMaterno).change();
                    $("[name='custom_attributes[telefono]']").val(ShippingHomeDelivery.customertelefono).change();
                } 

                return popUp;
            },

            /**
             * Show address form popup
             */
            showFormPopUp: function () {
                this.isFormPopUpVisible(true);
            },

            /**
             * Save new shipping address
             */
            saveNewAddress: function () {
                var addressData,
                    newShippingAddress;

                this.source.set('params.invalid', false);
                this.triggerShippingDataValidateEvent();

                if (!this.source.get('params.invalid')) {
                    addressData = this.source.get('shippingAddress');
                    // if user clicked the checkbox, its value is true or false. Need to convert.
                    addressData.save_in_address_book = this.saveInAddressBook ? 1 : 0;

                    // New address must be selected as a shipping address
                    newShippingAddress = createShippingAddress(addressData);
                    selectShippingAddress(newShippingAddress);
                    checkoutData.setSelectedShippingAddress(newShippingAddress.getKey());
                    checkoutData.setNewCustomerShippingAddress(addressData);
                    this.getPopUp().closeModal();
                    this.isNewAddressAdded(true);
                }
            },

            /**
             * Shipping Method View
             */
            rates: shippingService.getShippingRates(),
            isLoading: shippingService.isLoading,
            displayNewAddressPopup: 0,
            isSelected: ko.computed(function () {
                $('.map-home-delivery').remove();
                
                if (
                        typeof(ShippingHomeDelivery.htmlHomeDelivery) 
                        !== 'undefined' && quote.shippingMethod() 
                        && $('#s_method_Ppsoftshippinghomedelivery_Ppsoftshippinghomedelivery').is(':checked')
                    ) {
                    if (quote.shippingMethod()['carrier_code'] == 'Ppsoftshippinghomedelivery') {
                        $("#s_method_Ppsoftshippinghomedelivery_Ppsoftshippinghomedelivery").parent('.col-method').parent('tr.row').after(ShippingHomeDelivery.htmlHomeDelivery);
                        $('#map-home-delivery-content').before(ShippingHomeDelivery.htmlEstimateDate);
                        
                        if (ShippingHomeDelivery.places) {
                            ShippingHomeDelivery.initialize();
                        } else {
                            $('#map-home-delivery-content').html($.mage.__('En este momento no podemos mostrar el mapa tenemos algun problema con el pos.'));
                        }
                    }
                }
                
                return quote.shippingMethod() ?
                    quote.shippingMethod().carrier_code + '_' + quote.shippingMethod().method_code
                    : null;
            }),

            /**
             * @param {Object} shippingMethod
             * @return {Boolean}
             */
            selectShippingMethod: function (shippingMethod) {
                selectShippingMethodAction(shippingMethod);
                checkoutData.setSelectedShippingRate(shippingMethod.carrier_code + '_' + shippingMethod.method_code);

                return true;
            },

            /**
             * Set shipping information handler
             */
            setShippingInformation: function () {
                if (this.validateShippingInformation()) {
                    checkoutDataResolver.resolveBillingAddress();
                    $('body').trigger('processStart');
                    this.CyberSourceValidation();
                }
            },

            CyberSourceValidation: function () {
                var shippingAddress;
                var result = true;
                shippingAddress = quote.shippingAddress();
                var me = this;
                var url = urlBuilder.build("cybersourcea/index/address");
                $.post(
                    url,
                    {
                    city: shippingAddress.city,
                    country: shippingAddress.countryId,
                    firstname: shippingAddress.firstname,
                    lastname: shippingAddress.lastname,
                    postcode: shippingAddress.postcode,
                    region_id: shippingAddress.regionId,
                    street1: shippingAddress.street[0],
                    street2: shippingAddress.street[1],
                    telephone: shippingAddress.telephone
                    },
                    function (data) {
                    //strict mode
                    if (data.needCheck && data.needForce) {
                        if (data.isValid) {
                            if (data.needUpdate) {
                                me.addressVerificationPopup(data.message, data.normalizationData);
                            } else {
                                me.goToNext();
                            }
                        } else {
                            me.addressVerificationPopup(data.message, false);
                        }
                    } else if (data.needCheck && !data.needForce) {
                        if (data.isValid) {
                            if (data.needUpdate) {
                                me.addressVerificationOptional(data.message, data.normalizationData);
                            } else {
                                me.goToNext();
                            }
                        } else {
                            me.addressVerificationPopup(data.message, false);
                        }
                    } else if (!data.needCheck) {
                        me.goToNext();
                    }
                    $('body').trigger('processStop');
                    },
                    'json'
                );
                return result;
            },

            addressVerificationOptional: function (message, data) {
                var me = this;
                $('<div id="address-verifcation-modal" />').html(message)
                .modal({
                    title: 'Shipping Address Verification Message',
                    autoOpen: true,
                    buttons: [{
                        text: 'Normalize',
                        attr: {
                            'data-action': 'confirm'
                        },
                        'class': 'action-primary',
                        click: function () {
                            me.normalizeAddress(data);
                            this.closeModal();
                        }
                    },
                    {
                        text: 'Continue',
                        attr: {
                            'data-action': 'confirm'
                        },
                        'class': 'action-primary',
                        click: function () {
                            me.goToNext();
                            this.closeModal();
                        }
                    }]
                 });
            },

            addressVerificationPopup: function (message, data) {
                var me = this;
                if (data) {
                    $('<div id="address-verifcation-modal" />').html(message)
                    .modal({
                        title: 'Shipping Address Verification Message',
                        autoOpen: true,
                        buttons: [{
                            text: 'Confirm',
                            attr: {
                                'data-action': 'confirm'
                            },
                            'class': 'action-primary',
                            click: function () {
                                me.normalizeAddress(data);
                                this.closeModal();
                            }
                        }]
                     });
                } else {
                    $('<div id="address-verifcation-modal" />').html(message)
                    .modal({
                        title: 'Shipping Address Verification Message',
                        autoOpen: true
                     });
                }
            },

            normalizeAddress: function (data) {
                if (typeof data["street[0]"] !== 'undefined') {
                    quote.shippingAddress().street[0] = data["street[0]"];
                }
                if (typeof data["street[1]"] !== 'undefined') {
                    if (data["street[1]"] !== '') {
                        quote.shippingAddress().street[1] = data["street[1]"];
                    } else {
                        quote.shippingAddress().street = quote.shippingAddress().street.splice(0, 1);
                    }
                }
                if (typeof data["city"] !== 'undefined') {
                    quote.shippingAddress().city = data["city"];
                }
                if (typeof data["postcode"] !== 'undefined') {
                    quote.shippingAddress().postcode = data["postcode"];
                }

                if (typeof data["region_name"] !== 'undefined') {
                    quote.shippingAddress().region = data["region_name"];
                }
                if (typeof data["region_id"] !== 'undefined') {
                    quote.shippingAddress().regionId = data["region_id"];
                }
                if (typeof data["region_code"] !== 'undefined') {
                    quote.shippingAddress().regionCode = data["region_code"];
                }

                for (var key in data) {
                    $('[name="' + key + '"]').val(data[key]);
                }
                this.goToNext();
            },

            goToNext: function () {
                setShippingInformationAction().done(
                    function () {
                        stepNavigator.next();
                    }
                );
            },

            /**
             * @return {Boolean}
             */
            validateShippingInformation: function () {
                var shippingAddress,
                    addressData,
                    loginFormSelector = 'form[data-role=email-with-possible-login]',
                    emailValidationResult = customer.isLoggedIn();

                if (!quote.shippingMethod()) {
                    this.errorValidationMessage($.mage.__('Please specify a shipping method.'));

                    return false;
                }

                if (!customer.isLoggedIn()) {
                    $(loginFormSelector).validation();
                    emailValidationResult = Boolean($(loginFormSelector + ' input[name=username]').valid());
                }

                if (this.isFormInline) {
                    this.source.set('params.invalid', false);
                    this.triggerShippingDataValidateEvent();
                    if (emailValidationResult &&
                        this.source.get('params.invalid') ||
                        !quote.shippingMethod().method_code ||
                        !quote.shippingMethod().carrier_code
                    ) {
                        this.focusInvalid();

                        return false;
                    }

                    shippingAddress = quote.shippingAddress();
                    addressData = addressConverter.formAddressDataToQuoteAddress(
                        this.source.get('shippingAddress')
                    );

                    //Copy form data to quote shipping address object
                    for (var field in addressData) {
                        if (addressData.hasOwnProperty(field) &&
                            shippingAddress.hasOwnProperty(field) &&
                            typeof addressData[field] != 'function' &&
                            _.isEqual(shippingAddress[field], addressData[field])
                        ) {
                            shippingAddress[field] = addressData[field];
                        } else if (typeof addressData[field] != 'function' &&
                            !_.isEqual(shippingAddress[field], addressData[field])) {
                            shippingAddress = addressData;
                            break;
                        }
                    }

                    if (customer.isLoggedIn()) {
                        shippingAddress.save_in_address_book = 1;
                    }
                    selectShippingAddress(shippingAddress);
                }

                if (!emailValidationResult) {
                    $(loginFormSelector + ' input[name=username]').focus();

                    return false;
                }
                
                $('#country_id').hide();
                $('[name="city"]').val(ShippingHomeDelivery.currentStore.name).change();
                $('[name="city"]').prop('disabled', true);
                
                if (ShippingHomeDelivery.isCustomerLogged && typeof(ShippingHomeDelivery.customerCi)) {
                    $("[name='custom_attributes[numero_documento]']").val(ShippingHomeDelivery.customerCi).change().prop('disabled', true);                    
                    $("[name='custom_attributes[tipo_documento]']").val(ShippingHomeDelivery.customerExt).change().prop('disabled', true);
                }

                return true;
            },

            /**
             * Trigger Shipping data Validate Event.
             *
             * @return {void}
             */
            triggerShippingDataValidateEvent: function () {
                this.source.trigger('shippingAddress.data.validate');
                if (this.source.get('shippingAddress.custom_attributes')) {
                    this.source.trigger('shippingAddress.custom_attributes.data.validate');
                }
            }
        });
    }
);
