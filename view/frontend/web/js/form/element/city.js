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
                notice: {
                    oneOptionAvailable: $t('The selected city is the only available option.')
                },
                warn: {
                    noOptionsAvailable: $t('We\'re unable to ship to your selected city. We apologize for the inconvenience.')
                }
            }
        },
        /**
         * {@inheritdoc}
         */
        initialize: function () {
            var options;

            this._super();

            /** @var {Array} options */
            options = this.getRegionValue() !== null
                ? this.initialOptions
                : [];

            this.filterOptions(options);
            this.setOptions(options);
            this.initialized(true);

            return this;
        },
        /**
         * @return {mixed}
         */
        getRegionValue: function () {
            var region;

            /** @var {UiClass} region */
            region = registry.get(this.parentName + '.region_id');

            if (region) {
                return region.value();
            }

            return null;
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
                this.notice(false);
                this.warn(this.messages.warn['noOptionsAvailable']);
            } else if (result.length < 2) {
                this.disabled(true);
                this.value(result[0]['value']);
                this.notice(this.messages.notice['oneOptionAvailable']);
                this.warn(false);
            } else {
                this.disabled(false);
                this.notice(false);
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
