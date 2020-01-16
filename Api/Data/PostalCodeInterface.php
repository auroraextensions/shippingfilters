<?php
/**
 * PostalCodeInterface.php
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

interface PostalCodeInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \AuroraExtensions\ShippingFilters\Api\Data\PostalCodeInterface
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return \AuroraExtensions\ShippingFilters\Api\Data\PostalCodeInterface
     */
    public function setCreatedAt($createdAt): PostalCodeInterface;

    /**
     * @return string
     */
    public function getPostalCode(): string;

    /**
     * @param string $postalCode
     * @return \AuroraExtensions\ShippingFilters\Api\Data\PostalCodeInterface
     */
    public function setPostalCode(string $postalCode): PostalCodeInterface;

    /**
     * @return string
     */
    public function getPostalName(): string;

    /**
     * @param string $postalName
     * @return \AuroraExtensions\ShippingFilters\Api\Data\PostalCodeInterface
     */
    public function setPostalName(string $postalName): PostalCodeInterface;

    /**
     * @return int
     */
    public function getRegionId(): int;

    /**
     * @param int $regionId
     * @return \AuroraExtensions\ShippingFilters\Api\Data\PostalCodeInterface
     */
    public function setRegionId(int $regionId): PostalCodeInterface;

    /**
     * @return string
     */
    public function getRegionCode(): string;

    /**
     * @param string $regionCode
     * @return \AuroraExtensions\ShippingFilters\Api\Data\PostalCodeInterface
     */
    public function setRegionCode(string $regionCode): PostalCodeInterface;

    /**
     * @return string
     */
    public function getRegionName(): string;

    /**
     * @param string $regionName
     * @return \AuroraExtensions\ShippingFilters\Api\Data\PostalCodeInterface
     */
    public function setRegionName(string $regionName): PostalCodeInterface;

    /**
     * @return string
     */
    public function getCountryCode(): string;

    /**
     * @param string $countryCode
     * @return \AuroraExtensions\ShippingFilters\Api\Data\PostalCodeInterface
     */
    public function setCountryCode(string $countryCode): PostalCodeInterface;

    /**
     * @return string
     */
    public function getCountryName(): string;

    /**
     * @param string $countryName
     * @return \AuroraExtensions\ShippingFilters\Api\Data\PostalCodeInterface
     */
    public function setCountryName(string $countryName): PostalCodeInterface;
}
