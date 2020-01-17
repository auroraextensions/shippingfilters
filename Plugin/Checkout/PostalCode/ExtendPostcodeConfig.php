<?php
/**
 * ExtendPostcodeConfig.php
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

class ExtendPostcodeConfig
{
    /** @constant string COMPONENT */
    public const COMPONENT = 'AuroraExtensions_ShippingFilters/js/form/element/postal-code';

    /** @constant string TEMPLATE */
    public const TEMPLATE = 'AuroraExtensions_ShippingFilters/form/element/postal-code';

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
        /** @var array $config */
        $config = &$result['components']['checkout']['children']['steps']
            ['children']['shipping-step']['children']['shippingAddress']
            ['children']['shipping-address-fieldset']['children']['postcode']['config'];

        if (isset($config)) {
            $config['caption'] = __('Please select a ZIP/postal code.');
            $config['component'] = static::COMPONENT;
            $config['elementTmpl'] = static::TEMPLATE;
            $config['filterBy'] = [
                'field' => 'region_id',
                'target' => '${ $.parentName }.region_id:value',
            ];
        }

        return $result;
    }
}
