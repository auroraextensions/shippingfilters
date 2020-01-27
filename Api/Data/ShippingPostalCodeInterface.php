<?php
/**
 * ShippingPostalCodeInterface.php
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

interface ShippingPostalCodeInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingPostalCodeInterface
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingPostalCodeInterface
     */
    public function setCreatedAt($createdAt): ShippingPostalCodeInterface;

    /**
     * @return string
     */
    public function getPostalCode(): string;

    /**
     * @param string $postalCode
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingPostalCodeInterface
     */
    public function setPostalCode(string $postalCode): ShippingPostalCodeInterface;

    /**
     * @return string
     */
    public function getLocalityName(): string;

    /**
     * @param string $localityName
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingPostalCodeInterface
     */
    public function setLocalityName(string $localityName): ShippingPostalCodeInterface;

    /**
     * @return int
     */
    public function getRegionId(): int;

    /**
     * @param int $regionId
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingPostalCodeInterface
     */
    public function setRegionId(int $regionId): ShippingPostalCodeInterface;

    /**
     * @return string
     */
    public function getRegionCode(): string;

    /**
     * @param string $regionCode
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingPostalCodeInterface
     */
    public function setRegionCode(string $regionCode): ShippingPostalCodeInterface;

    /**
     * @return string
     */
    public function getRegionName(): string;

    /**
     * @param string $regionName
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingPostalCodeInterface
     */
    public function setRegionName(string $regionName): ShippingPostalCodeInterface;

    /**
     * @return string
     */
    public function getCountryCode(): string;

    /**
     * @param string $countryCode
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingPostalCodeInterface
     */
    public function setCountryCode(string $countryCode): ShippingPostalCodeInterface;

    /**
     * @return string
     */
    public function getCountryName(): string;

    /**
     * @param string $countryName
     * @return \AuroraExtensions\ShippingFilters\Api\Data\ShippingPostalCodeInterface
     */
    public function setCountryName(string $countryName): ShippingPostalCodeInterface;
}
