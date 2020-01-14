<?php
/**
 * Region.php
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

use AuroraExtensions\ShippingFilters\Csi\Filter\RegionFilterInterface;
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

class Region extends Data
{
    /** @constant string FIELD_REGION_NAME */
    public const FIELD_REGION_NAME = 'region';

    /** @constant string FIELD_REGION_ID */
    public const FIELD_REGION_ID = 'state';

    /** @constant string FIELD_REGION_TITLE */
    public const FIELD_REGION_TITLE = 'State/Province';

    /** @property CountryFilterInterface $regionFilter */
    protected $regionFilter;

    /**
     * @param Context $context
     * @param DirectoryHelper $directoryHelper
     * @param JsonEncoderInterface $jsonEncoder
     * @param ConfigCacheType $configCacheType
     * @param RegionCollectionFactory $regionCollectionFactory
     * @param CountryCollectionFactory $countryCollectionFactory
     * @param array $data
     * @param RegionFilterInterface $regionFilter
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
        RegionFilterInterface $regionFilter
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
        $this->regionFilter = $regionFilter;
    }

    /**
     * @param string|null $default
     * @param string $name
     * @param string $id
     * @param string $title
     * @return string
     * @see parent::getRegionSelect()
     */
    public function getWhitelistRegionSelect(
        string $default = null,
        string $name = self::FIELD_REGION_NAME,
        string $id = self::FIELD_REGION_ID,
        string $title = self::FIELD_REGION_TITLE
    ): string
    {
        /** @var string $value */
        $value = $default ?? $this->getRegionId();

        /** @var array $options */
        $options = $this->regionFilter
            ->getOptions();

        /** @var string $html */
        $html = $this->getLayout()
            ->createBlock(Select::class)
            ->setName($name)
            ->setId($id)
            ->setTitle(__($title))
            ->setClass('required-entry validate-state')
            ->setValue($value)
            ->setOptions($options)
            ->getHtml();

        return $html;
    }
}
