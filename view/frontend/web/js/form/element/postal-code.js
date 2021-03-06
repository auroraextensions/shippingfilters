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
     * @param {Object} a
     * @param {Object} b
     * @return {Boolean}
     */
    function isCompositeEqual(a, b) {
        return (JSON.stringify(a) === JSON.stringify(b));
    }

    return Select.extend({
        defaults: {
            customFilters: ko.observable({}),
            customName: '${ $.parentName }.postcode',
            dict: 'whitelist_postal_code_id',
            exports: {
                postalCode: '${ $.parentName }.postcode:value'
            },
            filterOptions: ko.observableArray([]),
            imports: {
                onCountrySelect: '${ $.parentName }.country_id:value',
                onLocalitySelect: '${ $.parentName }.city_id:value',
                onRegionSelect: '${ $.parentName }.region_id:value',
                customFilters: '${ $.provider }:customFilters'
            },
            initialized: ko.observable(false),
            listens: {
                options: 'onOptionsChange',
                value: 'onValueChange'
            },
            postalCode: ko.observable(),
        },
        /**
         * {@inheritdoc}
         */
        initialize: function () {
            var country, options;

            this._super();

            /** @var {Array} options */
            options = (this.getRegionValue() !== null && this.getLocalityValue() !== null)
                ? this.initialOptions
                : [];

            this.filterOptions(options);
            this.setOptions(options);
            this.initialized(true);

            /** @var {String} country */
            country = this.getCountryValue();

            if (!this.isCountrySupported(country)) {
                this.setVisible(false)
                    .toggleInput(true);
            }

            return this;
        },
        /**
         * @return {UiClass}
         */
        getDefaultComponent: function () {
            var component;

            /** @var {UiClass} component */
            component = registry.get(this.customName);

            return !!component ? component : this;
        },
        /**
         * @return {Array}
         */
        getSupportedCountries: function () {
            var customFilters, countries;

            /** @var {Object} customFilters */
            customFilters = this.customFilters();

            if (!customFilters) {
                return [];
            }

            /** @var {Array|void} countries */
            countries = customFilters['countries'];

            return !!countries ? countries : [];
        },
        /**
         * @param {String} value
         * @return {Boolean}
         */
        isCountrySupported: function (value) {
            var countries;

            if (!value) {
                return false;
            }

            /** @var {Array} countries */
            countries = this.getSupportedCountries();

            return (countries.indexOf(value) > -1);
        },
        /**
         * @return {mixed}
         */
        getCountryValue: function () {
            var country;

            /** @var {UiClass} country */
            country = registry.get(this.parentName + '.country_id');

            if (country) {
                return country.value();
            }

            return null;
        },
        /**
         * @return {mixed}
         */
        getLocalityValue: function () {
            var locality;

            /** @var {UiClass} locality */
            locality = registry.get(this.parentName + '.city_id');

            if (locality) {
                return locality.value();
            }

            return null;
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
        onCountrySelect: function (value) {
            var country, options,
                option, supported;

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

            /** @var {Boolean} supported */
            supported = this.isCountrySupported(value);

            this.setVisible(supported)
                .toggleInput(!supported);

            if (!supported) {
                this.clearMessages()
                    .getDefaultComponent()
                    .clear();
            }
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
            } else if (result.length < 2) {
                this.disabled(true);
                this.value(result[0]['value']);
            } else {
                this.disabled(false);
            }
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
            } else if (result.length < 2) {
                this.disabled(true);
                this.value(result[0]['value']);
            } else {
                this.disabled(false);
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
        filterByLocality: function (value) {
            var field, locality;

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
                return (item[field] === locality || item.value === '');
            });
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
        },
        /**
         * @return {this}
         */
        clearMessages: function () {
            var index, type, types;

            /** @var {Array} types */
            types = [
                this.error,
                this.warn,
                this.notice
            ];

            /** @var {Number} index */
            for (index = 0; index < types.length; index += 1) {
                /** @var {Function} type */
                type = types[index];

                if (_.isFunction(type) && !!type()) {
                    type(false);
                }
            }

            return this;
        }
    });
});
