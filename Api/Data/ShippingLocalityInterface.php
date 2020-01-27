<?php
/**
 * ShippingLocalityInterface.php
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

interface ShippingLocalityInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingLocalityInterface
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param \DateTime|string $createdAt
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingLocalityInterface
     */
    public function setCreatedAt($createdAt): ShippingLocalityInterface;

    /**
     * @return string
     */
    public function getLocalityName(): string;

    /**
     * @param string $name
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingLocalityInterface
     */
    public function setLocalityName(string $name): ShippingLocalityInterface;

    /**
     * @return int
     */
    public function getRegionId(): int;

    /**
     * @param int $regionId
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingLocalityInterface
     */
    public function setRegionId(int $regionId): ShippingLocalityInterface;

    /**
     * @return string
     */
    public function getRegionCode(): string;

    /**
     * @param string $regionCode
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingLocalityInterface
     */
    public function setRegionCode(string $regionCode): ShippingLocalityInterface;

    /**
     * @return string
     */
    public function getRegionName(): string;

    /**
     * @param string $regionName
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingLocalityInterface
     */
    public function setRegionName(string $regionName): ShippingLocalityInterface;

    /**
     * @return string
     */
    public function getCountryCode(): string;

    /**
     * @param string $countryCode
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingLocalityInterface
     */
    public function setCountryCode(string $countryCode): ShippingLocalityInterface;

    /**
     * @return string
     */
    public function getCountryName(): string;

    /**
     * @param string $countryName
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingLocalityInterface
     */
    public function setCountryName(string $countryName): ShippingLocalityInterface;

    /**
     * @return bool
     */
    public function getIsActive(): bool;

    /**
     * @param bool $isActive
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingLocalityInterface
     */
    public function setIsActive(bool $isActive): ShippingLocalityInterface;
}
