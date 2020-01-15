<?php
/**
 * AddFilterShippingRegionDictionary.php
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

namespace AuroraExtensions\ShippingFilters\Plugin\Checkout;

use AuroraExtensions\ShippingFilters\Csi\Filter\RegionFilterInterface;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;

class AddFilterShippingRegionDictionary
{
    /** @constant string DICT */
    public const DICT = 'whitelist_region_id';

    /** @property RegionFilterInterface $regionFilter */
    private $regionFilter;

    /**
     * @param RegionFilterInterface $regionFilter
     * @return void
     */
    public function __construct(
        RegionFilterInterface $regionFilter
    ) {
        $this->regionFilter = $regionFilter;
    }

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
        /** @var array $dicts */
        $dicts = &$result['components']['checkoutProvider']['dictionaries'];

        if (isset($dicts)) {
            $dicts[static::DICT] = $this->regionFilter->getOptions();
        }

        return $result;
    }
}
