<?php
/**
 * ShippingLocalityRepositoryInterface.php
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

interface ShippingLocalityRepositoryInterface
{
    /**
     * @param int $id
     * @return \AuroraExtensions\ShippingFilters\Api\Data\LocalityInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): Data\LocalityInterface;

    /**
     * @param \AuroraExtensions\ShippingFilters\Api\Data\LocalityInterface $locality
     * @return int
     */
    public function save(Data\LocalityInterface $locality): int;

    /**
     * @param \AuroraExtensions\ShippingFilters\Api\Data\LocalityInterface $locality
     * @return bool
     */
    public function delete(Data\LocalityInterface $locality): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;
}
