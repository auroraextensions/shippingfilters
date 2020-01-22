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
    'mage/translate',
    'Magento_Ui/js/form/element/select'
], function (_, ko, registry, $t, Select) {
    'use strict';

    /**
     * @param {Object} a
     * @param {Object} b
     * @return {Boolean}
     */
    function isCompositeEqual(a, b) {
        return (JSON.stringify(a) === JSON.stringify(b));
    }

    return Select.extend({
        defaults: {
            dict: 'whitelist_city_id',
            exports: {
                locality: '${ $.parentName }.city:value'
            },
            filterOptions: ko.observableArray([]),
            imports: {
                onRegionSelect: '${ $.parentName }.region_id:value'
            },
            initialized: ko.observable(false),
            listens: {
                options: 'onOptionsChange',
                value: 'onValueChange'
            },
            locality: ko.observable(),
            messages: {
                warnings: {
                    noOptionsAvailable: $t('We\'re unable to ship to your selected city. We apologize for the inconvenience.'),
                    oneOptionAvailable: $t('The selected city is the only available option. We apologize for any inconvenience.')
                }
            }
        },
        /**
         * {@inheritdoc}
         */
        initialize: function () {
            this._super();

            this.filterOptions(this.initialOptions);
            this.setOptions(this.initialOptions);
            this.initialized(true);

            return this;
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
        onRegionSelect: function (value) {
            var result;

            if (!value) {
                return;
            }

            /** @var {Array} result */
            result = this.filterByRegion(value);

            this.filterOptions(result);
            this.setOptions(result);

            if (!result.length) {
                this.disabled(true);
                this.error(false);
                this.warn(this.messages.warnings['noOptionsAvailable']);
            } else if (result.length < 2) {
                this.disabled(true);
                this.value(result[0]['value']);
                this.warn(this.messages.warnings['oneOptionAvailable']);
            } else {
                this.disabled(false);
                this.warn(false);
            }
        },
        /**
         * @param {Array} options
         * @return {void}
         */
        onOptionsChange: function (options) {
            if (!this.initialized()) {
                return;
            }

            if (!isCompositeEqual(options, this.filterOptions())) {
                this.setOptions(this.filterOptions());
            }
        },
        /**
         * @param {String} value
         * @return {Array}
         */
        filterByRegion: function (value) {
            var field, region;

            if (!value) {
                return [];
            }

            /** @var {Number} region */
            region = +(this.getFieldByValue(
                value,
                'value',
                'whitelist_region_id'
            ));

            return _.filter(this.initialOptions, function (item) {
                return (item['region_id'] === region || item.value === '');
            });
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
