<?php
/**
 * Country.php
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

namespace AuroraExtensions\ShippingFilters\Block\Directory;

use AuroraExtensions\ShippingFilters\Csi\Filter\CountryFilterInterface;
use Magento\Directory\{
    Helper\Data as DirectoryHelper,
    Model\ResourceModel\Country\Collection as CountryCollection,
    Model\ResourceModel\Country\CollectionFactory as CountryCollectionFactory,
    Model\ResourceModel\Region\Collection as RegionCollection,
    Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory
};
use Magento\Framework\{
    App\Cache\Type\Config as ConfigCacheType,
    Json\EncoderInterface as JsonEncoderInterface,
    View\Element\Html\Select,
    View\Element\Template\Context
};

class Country extends Data
{
    /** @constant string FIELD_COUNTRY_NAME */
    public const FIELD_COUNTRY_NAME = 'country_id';

    /** @constant string FIELD_COUNTRY_ID */
    public const FIELD_COUNTRY_ID = 'country';

    /** @constant string FIELD_COUNTRY_TITLE */
    public const FIELD_COUNTRY_TITLE = 'Country';

    /** @property CountryFilterInterface $countryFilter */
    protected $countryFilter;

    /**
     * @param Context $context
     * @param DirectoryHelper $directoryHelper
     * @param JsonEncoderInterface $jsonEncoder
     * @param ConfigCacheType $configCacheType
     * @param RegionCollectionFactory $regionCollectionFactory
     * @param CountryCollectionFactory $countryCollectionFactory
     * @param array $data
     * @param CountryFilterInterface $countryFilter
     * @return void
     */
    public function __construct(
        Context $context,
        DirectoryHelper $directoryHelper,
        JsonEncoderInterface $jsonEncoder,
        ConfigCacheType $configCacheType,
        RegionCollectionFactory $regionCollectionFactory,
        CountryCollectionFactory $countryCollectionFactory,
        array $data = [],
        CountryFilterInterface $countryFilter
    ) {
        parent::__construct(
            $context,
            $directoryHelper,
            $jsonEncoder,
            $configCacheType,
            $regionCollectionFactory,
            $countryCollectionFactory
            $data
        );
        $this->countryFilter = $countryFilter;
    }

    /**
     * @param string|null $default
     * @param string $name
     * @param string $id
     * @param string $title
     * @return string
     * @see parent::getCountryHtmlSelect()
     */
    public function getWhitelistCountryHtmlSelect(
        string $default = null,
        string $name = self::FIELD_COUNTRY_NAME,
        string $id = self::FIELD_COUNTRY_ID,
        string $title = self::FIELD_COUNTRY_TITLE
    ): string
    {
        /** @var string $value */
        $value = $default ?? $this->getCountryId();

        /** @var array $options */
        $options = $this->countryFilter
            ->getOptions();

        /** @var string $html */
        $html = $this->getLayout()
            ->createBlock(Select::class)
            ->setName($name)
            ->setId($id)
            ->setTitle(__($title))
            ->setValue($value)
            ->setOptions($options)
            ->setExtraParams('data-validate="{\'validate-select\':true}"')
            ->getHtml();

        return $html;
    }
}
