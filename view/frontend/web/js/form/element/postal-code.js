/**
 * postal-code.js
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/shippingfilters/LICENSE.txt
 *
 * @package       AuroraExtensions_ShippingFilters
 * @copyright     Copyright (C) 2020 Aurora Extensions <support@auroraextensions.com>
 * @license       MIT License
 */
define([
    'underscore',
    'ko',
    'uiRegistry',
    'mage/translate',
    'Magento_Ui/js/form/element/select'
], function (_, ko, registry, $t, Select) {
    'use strict';

    /**
     * @param {Array} data
     * @param {Object} result
     * @returns {Object}
     */
    function indexOptions(data, result) {
        var value;

        result = result || {};

        data.forEach(function (item) {
            value = item.value;

            if (Array.isArray(value)) {
                indexOptions(value, result);
            } else {
                result[value] = item;
            }
        });

        return result;
    }

    /**
     * @param {Array} a
     * @param {Array} b
     * @return {Boolean}
     */
    function isArrayEqual(a, b) {
        return (JSON.stringify(a) === JSON.stringify(b));
    }

    return Select.extend({
        defaults: {
            dict: 'whitelist_postal_code_id',
            exports: {
                postalCode: '${ $.parentName }.postcode:value'
            },
            imports: {
                onCountrySelect: '${ $.parentName }.country_id:value',
                onLocalitySelect: '${ $.parentName }.city_id:value'
            },
            listens: {
                options: 'onOptionsChange',
                value: 'onValueChange'
            },
            filterOptions: ko.observableArray([]),
            postalCode: ko.observable(),
            messages: {
                noZipCodesWarning: $t('We\'re unable to ship to your selected city. We apologize for the inconvenience.')
            }
        },
        /**
         * @return {String|null}
         */
        getLocalityValue: function () {
            var component;

            /** @var {UiClass} component */
            component = registry.get(this.parentName + '.city_id');

            if (!component.value()) {
                return null;
            }

            return component.value();
        },
        /**
         * @param {mixed} value
         * @param {String} field
         * @param {String} dict
         * @return {mixed}
         */
        getFieldByValue: function (value, field, dict) {
            var data, index,
                item, result = null;

            dict = dict || this.dict;

            registry.get(this.provider, function (provider) {
                /** @var {Array} data */
                data = provider.dictionaries[dict];

                if (!data) {
                    return result;
                }

                /** @var {Number} index */
                for (index = 0; index < data.length; index += 1) {
                    /** @var {Object} item */
                    item = data[index];

                    if (result !== null) {
                        break;
                    }

                    if (item['value'] === value) {
                        result = item[field];
                    }
                }
            }.bind(this));

            return result;
        },
        /**
         * @param {String} value
         * @return {void}
         */
        onCountrySelect: function (value) {
            var country, options, option;

            if (!value) {
                return;
            }

            /** @var {UiClass} country */
            country = registry.get(this.parentName + '.country_id');

            /** @var {Object} options */
            options = country.indexedOptions;

            /** @var {Object|null|void} option */
            option = options[value];

            if (!option) {
                return;
            }

            if (option['is_zipcode_optional']) {
                this.error(false);
                this.validation = _.omit(this.validation, 'required-entry');
            } else {
                this.validation['required-entry'] = true;
            }

            this.required(!option['is_zipcode_optional']);
        },
        /**
         * @param {String} value
         * @return {void}
         */
        onLocalitySelect: function (value) {
            var result;

            if (!value) {
                return;
            }

            /** @var {Array} result */
            result = this.filterByLocality(value);

            this.filterOptions(result);
            this.setOptions(result);

            if (!result.length) {
                this.disabled(true);
                this.error(false);
                this.warn(this.messages['noZipCodesWarning']);
            } else {
                this.disabled(false);
                this.warn(false);
            }

            if (result.length && result.length < 2) {
                this.disabled(true);
                this.value(result[0]['value']);
            }
        },
        /**
         * @param {Array} options
         * @return {void}
         */
        onOptionsChange: function (options) {
            var locality;

            if (!isArrayEqual(options, this.filterOptions())) {
                this.setOptions(this.filterOptions());
            }
        },
        /**
         * @param {String} value
         * @return {Array}
         */
        filterByLocality: function (value) {
            var field, locality, result;

            if (!value) {
                return [];
            }

            /** @var {String} field */
            field = 'locality_name';

            /** @var {String} locality */
            locality = this.getFieldByValue(
                value,
                field,
                'whitelist_city_id'
            );

            return _.filter(this.initialOptions, function (item) {
                return item[field] === locality || item.value === '';
            });
        },
        /**
         * @param {String} value
         * @return {void}
         */
        onValueChange: function (value) {
            var code;

            if (!value) {
                return;
            }

            /** @var {String} code */
            code = this.getFieldByValue(
                value,
                'postal_code'
            );

            this.postalCode(code);
        }
    });
});
