<?php
/**
 * PostalCodeRepositoryInterface.php
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

interface PostalCodeRepositoryInterface
{
    /**
     * @param \Magento\Directory\Api\Data\RegionInformationInterface $region
     * @return \AuroraExtensions\ShippingFilters\Api\Data\PostalCodeInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(RegionInformationInterface $region): Data\PostalCodeInterface;

    /**
     * @param int $id
     * @return \AuroraExtensions\ShippingFilters\Api\Data\PostalCodeInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): Data\PostalCodeInterface;

    /**
     * @param \AuroraExtensions\ShippingFilters\Api\Data\PostalCodeInterface $postalCode
     * @return int
     */
    public function save(Data\PostalCodeInterface $postalCode): int;

    /**
     * @param \AuroraExtensions\ShippingFilters\Api\Data\PostalCodeInterface $postalCode
     * @return bool
     */
    public function delete(Data\PostalCodeInterface $postalCode): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;
}
