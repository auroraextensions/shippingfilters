<?php
/**
 * PostalCode.php
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

namespace AuroraExtensions\ShippingFilters\Model\Data;

use AuroraExtensions\ShippingFilters\{
    Api\Data\PostalCodeInterface,
    Model\ResourceModel\PostalCode as PostalCodeResourceModel
};
use Magento\Framework\Model\AbstractModel;

class PostalCode extends AbstractModel implements PostalCodeInterface
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(PostalCodeResourceModel::class);
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->getData('created_at');
    }

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt): PostalCodeInterface
    {
        $this->setData('created_at', $createdAt);
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->getData('postal_code');
    }

    /**
     * @param string $postalCode
     * @return $this
     */
    public function setPostalCode(string $postalCode): PostalCodeInterface
    {
        $this->setData('postal_code', $postalCode);
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalName(): string
    {
        return $this->getData('postal_name');
    }

    /**
     * @param string $postalName
     * @return $this
     */
    public function setPostalName(string $postalName): PostalCodeInterface
    {
        $this->setData('postal_name', $postalName);
        return $this;
    }

    /**
     * @return int
     */
    public function getRegionId(): int
    {
        return (int) $this->getData('region_id');
    }

    /**
     * @param int $regionId
     * @return $this
     */
    public function setRegionId(int $regionId): PostalCodeInterface
    {
        $this->setData('region_id', $regionId);
        return $this;
    }

    /**
     * @return string
     */
    public function getRegionCode(): string
    {
        return $this->getData('region_code');
    }

    /**
     * @param string $regionCode
     * @return $this
     */
    public function setRegionCode(string $regionCode): PostalCodeInterface
    {
        $this->setData('region_code', $regionCode);
        return $this;
    }

    /**
     * @return string
     */
    public function getRegionName(): string
    {
        return $this->getData('region_name');
    }

    /**
     * @param string $regionName
     * @return $this
     */
    public function setRegionName(string $regionName): PostalCodeInterface
    {
        $this->setData('region_name', $regionName);
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->getData('country_code');
    }

    /**
     * @param string $countryCode
     * @return $this
     */
    public function setCountryCode(string $countryCode): PostalCodeInterface
    {
        $this->setData('country_code', $countryCode);
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryName(): string
    {
        return $this->getData('country_name');
    }

    /**
     * @param string $countryName
     * @return $this
     */
    public function setCountryName(string $countryName): PostalCodeInterface
    {
        $this->setData('country_name', $countryName);
        return $this;
    }
}
