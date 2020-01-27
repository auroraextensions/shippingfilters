<?php
/**
 * LocalityInterface.php
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

interface LocalityInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \AuroraExtensions\ShippingFilters\Api\Data\LocalityInterface
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param \DateTime|string $createdAt
     * @return \AuroraExtensions\ShippingFilters\Api\Data\LocalityInterface
     */
    public function setCreatedAt($createdAt): LocalityInterface;

    /**
     * @return string
     */
    public function getLocalityName(): string;

    /**
     * @param string $name
     * @return \AuroraExtensions\ShippingFilters\Api\Data\LocalityInterface
     */
    public function setLocalityName(string $name): LocalityInterface;

    /**
     * @return int
     */
    public function getRegionId(): int;

    /**
     * @param int $regionId
     * @return \AuroraExtensions\ShippingFilters\Api\Data\LocalityInterface
     */
    public function setRegionId(int $regionId): LocalityInterface;

    /**
     * @return string
     */
    public function getRegionCode(): string;

    /**
     * @param string $regionCode
     * @return \AuroraExtensions\ShippingFilters\Api\Data\LocalityInterface
     */
    public function setRegionCode(string $regionCode): LocalityInterface;

    /**
     * @return string
     */
    public function getRegionName(): string;

    /**
     * @param string $regionName
     * @return \AuroraExtensions\ShippingFilters\Api\Data\LocalityInterface
     */
    public function setRegionName(string $regionName): LocalityInterface;

    /**
     * @return string
     */
    public function getCountryCode(): string;

    /**
     * @param string $countryCode
     * @return \AuroraExtensions\ShippingFilters\Api\Data\LocalityInterface
     */
    public function setCountryCode(string $countryCode): LocalityInterface;

    /**
     * @return string
     */
    public function getCountryName(): string;

    /**
     * @param string $countryName
     * @return \AuroraExtensions\ShippingFilters\Api\Data\LocalityInterface
     */
    public function setCountryName(string $countryName): LocalityInterface;

    /**
     * @return bool
     */
    public function getIsActive(): bool;

    /**
     * @param bool $isActive
     * @return \AuroraExtensions\ShippingFilters\Api\Data\LocalityInterface
     */
    public function setIsActive(bool $isActive): LocalityInterface;
}
