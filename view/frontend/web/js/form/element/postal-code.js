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
                postalName: '${ $.parentName }.city:value'
            },
            imports: {
                onCountrySelect: '${ $.parentName }.country_id:value'
            },
            listens: {
                value: 'onValueChange'
            },
            postalName: ko.observable()
        },
        /**
         * @param {String|Number} value
         * @return {String|null}
         */
        getNameById: function (value) {
            return registry.get(this.provider, function (provider) {
                var data, dict, index;

                /** @var {Array} dict */
                dict = provider.dictionaries[this.dict];

                if (!dict) {
                    return null;
                }

                for (index = 0; index < dict.length; index += 1) {
                    /** @var {Object} data */
                    data = dict[index];

                    if (data['value'] === value) {
                        return data['postal_name'];
                    }
                }

                return null;
            }.bind(this));
        },
        /**
         * @param {String} value
         * @return {void}
         */
        onCountrySelect: function (value) {
            var country = registry.get(this.parentName + '.country_id'),
                options = country.indexedOptions,
                option = null;

            if (!value) {
                return;
            }

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
            if (!value) {
                return;
            }

            this.postalName(this.getNameById(value));
        }
    });
});
