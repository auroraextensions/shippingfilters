<?php
/**
 * LocalitySearchResultsInterface.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/shippingfilters/LICENSE.txt
 *
 * @package        AuroraExtensions_ShippingFilters
 * @copyright      Copyright (C) 2020 Aurora Extensions <support@auroraextensions.com>
 * @license        MIT License
 */
declare(strict_types=1);

namespace AuroraExtensions\ShippingFilters\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface LocalitySearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingLocalityInterface[]
     */
    public function getItems();

    /**
     * @param \AuroraExtensions\ShippingFilters\Api\Data\ShippingLocalityInterface[] $items
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function setItems(array $items);
}
