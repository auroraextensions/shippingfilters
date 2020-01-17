<?php
/**
 * SetShippingPostalCodeDictionary.php
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

class SetShippingPostalCodeDictionary
{
    /** @constant string DICT */
    public const DICT = 'whitelist_postal_code_id';

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
        /** @var array $imports */
        $imports = &$result['components']['checkout']['children']['steps']
            ['children']['shipping-step']['children']['shippingAddress']
            ['children']['shipping-address-fieldset']['children']['postcode']['imports'];

        if (isset($imports)) {
            $imports['initialOptions'] = 'index = checkoutProvider:dictionaries.' . static::DICT;
            $imports['setOptions'] = 'index = checkoutProvider:dictionaries.' . static::DICT;
        }

        return $result;
    }
}
