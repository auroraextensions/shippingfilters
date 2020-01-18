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
    'Magento_Ui/js/form/element/select'
], function (_, ko, registry, Select) {
    'use strict';

    return Select.extend({
        defaults: {
            dict: 'whitelist_postal_code_id',
            exports: {
                postalCode: '${ $.parentName }.postcode:value'
            },
            imports: {
                onCountrySelect: '${ $.parentName }.country_id:value'
            },
            listens: {
                value: 'onValueChange'
            },
            postalCode: ko.observable()
        },
        /**
         * @param {mixed} value
         * @param {String} field
         * @return {mixed}
         */
        getFieldByValue: function (value, field) {
            var dict, index,
                item, result = null;

            registry.get(this.provider, function (provider) {
                /** @var {Array} dict */
                dict = provider.dictionaries[this.dict];

                if (!dict) {
                    return result;
                }

                /** @var {Number} index */
                for (index = 0; index < dict.length; index += 1) {
                    /** @var {Object} item */
                    item = dict[index];

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
