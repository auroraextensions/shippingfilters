<?php
/**
 * AddPostalCodeSelectComponent.php
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
declare(strict_types=1);

namespace AuroraExtensions\ShippingFilters\Plugin\Checkout\PostalCode;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;

class AddPostalCodeSelectComponent
{
    /** @constant string COMPONENT */
    public const COMPONENT = 'AuroraExtensions_ShippingFilters/js/form/element/postal-code';

    /** @constant string DATASCOPE */
    public const DATASCOPE = 'postcode_id';

    /** @constant string DICT */
    public const DICT = 'whitelist_postal_code_id';

    /** @constant string TMPL */
    public const TMPL = 'AuroraExtensions_ShippingFilters/form/element/postal-code';

    /**
     * @param LayoutProcessorInterface $subject
     * @param array $result
     * @return array
     */
    public function afterProcess(
        LayoutProcessorInterface $subject,
        array $result
    ): array
    {
        /** @var array $component */
        $component = &$result['components']['checkout']['children']['steps']
            ['children']['shipping-step']['children']['shippingAddress']
            ['children']['shipping-address-fieldset']['children'][static::DATASCOPE];

        if (!isset($component)) {
            $component = [];
        }

        $component += [
            'component' => static::COMPONENT,
            'config' => [
                'caption' => __('Please select a ZIP/postal code'),
                'customScope' => 'shippingAddress',
                'elementTmpl' => static::TMPL,
                'template' => 'ui/form/field',
            ],
            'dataScope' => 'shippingAddress.' . static::DATASCOPE,
            'filterBy' => [
                'field' => 'region_id',
                'target' => '${ $.parentName }.region_id:value',
            ],
            'imports' => [
                'initialOptions' => 'index = checkoutProvider:dictionaries.' . static::DICT,
                'setOptions' => 'index = checkoutProvider:dictionaries.' . static::DICT,
            ],
            'label' => __('ZIP/Postal Code'),
            'options' => [],
            'provider' => 'checkoutProvider',
            'validation' => [
                'required-entry' => true,
            ],
            'value' => '',
            'visible' => true,
        ];

        return $result;
    }
}
