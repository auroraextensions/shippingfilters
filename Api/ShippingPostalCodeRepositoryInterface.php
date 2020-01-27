<?php
/**
 * ShippingPostalCodeRepositoryInterface.php
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

namespace AuroraExtensions\ShippingFilters\Api;

use Magento\Directory\Api\Data\RegionInformationInterface;

interface ShippingPostalCodeRepositoryInterface
{
    /**
     * @param \Magento\Directory\Api\Data\RegionInformationInterface $region
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingPostalCodeInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(RegionInformationInterface $region): Data\ShippingPostalCodeInterface;

    /**
     * @param int $id
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingPostalCodeInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): Data\ShippingPostalCodeInterface;

    /**
     * @param \AuroraExtensions\ShippingFilters\Api\Data\ShippingPostalCodeInterface $postalCode
     * @return int
     */
    public function save(Data\ShippingPostalCodeInterface $postalCode): int;

    /**
     * @param \AuroraExtensions\ShippingFilters\Api\Data\ShippingPostalCodeInterface $postalCode
     * @return bool
     */
    public function delete(Data\ShippingPostalCodeInterface $postalCode): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;
}
