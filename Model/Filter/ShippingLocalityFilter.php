<?php
/**
 * ShippingLocalityFilter.php
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

namespace AuroraExtensions\ShippingFilters\Model\Filter;

use AuroraExtensions\ShippingFilters\{
    Component\System\ModuleConfigTrait,
    Component\Utils\ArrayTrait,
    Csi\Filter\CountryFilterInterface,
    Csi\Filter\ShippingLocalityFilterInterface,
    Csi\Filter\RegionFilterInterface,
    Csi\System\ModuleConfigInterface,
    Model\ResourceModel\Locality\Collection,
    Model\ResourceModel\Locality\CollectionFactory
};
use Magento\Store\Model\StoreManagerInterface;

class ShippingLocalityFilter implements ShippingLocalityFilterInterface
{
    /**
     * @method array flattenArray()
     * ---
     * @property ModuleConfigInterface $moduleConfig
     * @method ModuleConfigInterface getModuleConfig()
     */
    use ArrayTrait, ModuleConfigTrait;

    /** @property CollectionFactory $collectionFactory */
    protected $collectionFactory;

    /** @property CountryFilterInterface $countryFilter */
    protected $countryFilter;

    /** @property RegionFilterInterface $regionFilter */
    protected $regionFilter;

    /** @property StoreManagerInterface $storeManager */
    protected $storeManager;

    /**
     * @param CollectionFactory $collectionFactory
     * @param CountryFilterInterface $countryFilter
     * @param ModuleConfigInterface $moduleConfig
     * @param RegionFilterInterface $regionFilter
     * @param StoreManagerInterface $storeManager
     * @return void
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        CountryFilterInterface $countryFilter,
        ModuleConfigInterface $moduleConfig,
        RegionFilterInterface $regionFilter,
        StoreManagerInterface $storeManager
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->countryFilter = $countryFilter;
        $this->moduleConfig = $moduleConfig;
        $this->regionFilter = $regionFilter;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocalities(): array
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory
            ->create()
            ->addFieldToSelect(static::PRIMARY_KEY)
            ->addFieldToFilter('is_active', ['eq' => '1']);

        return $this->flattenArray($collection->toArray()['items'] ?? []);
    }

    /**
     * @param string $code
     * @return array
     */
    public function getOptionsByRegionCode(string $code): array
    {
        /** @var array $countries */
        $countries = $this->countryFilter
            ->getCountries();

        /** @var Collection $localities */
        $localities = $this->collectionFactory
            ->create()
            ->addMinimalFieldsToSelect()
            ->addCountryCodesFilter($countries)
            ->addRegionIdFilter($code)
            ->addLocalityIdsFilter($this->getLocalities())
            ->optimizeLoad();

        return $localities->toOptionArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        /** @var array $countries */
        $countries = $this->countryFilter
            ->getCountries();

        /** @var array $regions */
        $regions = $this->regionFilter
            ->getRegions();

        /** @var Collection $localities */
        $localities = $this->collectionFactory
            ->create()
            ->addMinimalFieldsToSelect()
            ->addCountryCodesFilter($countries)
            ->addRegionIdsFilter($regions)
            ->addLocalityIdsFilter($this->getLocalities())
            ->optimizeLoad();

        return $localities->toOptionArray();
    }
}
