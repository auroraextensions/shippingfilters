/**
 * city.js
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
            dict: 'whitelist_city_id',
            exports: {
                locality: '${ $.parentName }.city:value'
            },
            listens: {
                value: 'onValueChange'
            },
            locality: ko.observable()
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
        onValueChange: function (value) {
            var name;

            if (!value) {
                return;
            }

            /** @var {String} name */
            name = this.getFieldByValue(
                value,
                'locality_name'
            );

            this.locality(name);
        }
    });
});
