<?php
/**
 * RegionFilter.php
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
    Csi\Filter\CountryFilterInterface,
    Csi\Filter\RegionFilterInterface,
    Csi\System\ModuleConfigInterface
};
use Magento\Directory\{
    Model\ResourceModel\Region\Collection,
    Model\ResourceModel\Region\CollectionFactory
};
use Magento\Store\Model\StoreManagerInterface;

class RegionFilter implements RegionFilterInterface
{
    /**
     * @property ModuleConfigInterface $moduleConfig
     * @method ModuleConfigInterface getModuleConfig()
     */
    use ModuleConfigTrait;

    /** @property CollectionFactory $collectionFactory */
    protected $collectionFactory;

    /** @property CountryFilterInterface $countryFilter */
    protected $countryFilter;

    /** @property StoreManagerInterface $storeManager */
    protected $storeManager;

    /**
     * @param CollectionFactory $collectionFactory
     * @param CountryFilterInterface $countryFilter
     * @param ModuleConfigInterface $moduleConfig
     * @param StoreManagerInterface $storeManager
     * @return void
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        CountryFilterInterface $countryFilter,
        ModuleConfigInterface $moduleConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->countryFilter = $countryFilter;
        $this->moduleConfig = $moduleConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getRegions(): array
    {
        /** @var StoreInterface $store */
        $store = $this->storeManager->getStore();

        /** @var string|null $whitelist */
        $whitelist = $this->getModuleConfig()
            ->getRegionWhitelist((int) $store->getId());

        /** @var array $regions */
        $regions = array_filter(
            array_map(
                'trim',
                explode(',', $whitelist)
            ),
            'strlen'
        );

        return $regions;
    }

    /**
     * @param string $code
     * @return array
     */
    public function getOptionsByCountryCode(string $code): array
    {
        /** @var Collection $regions */
        $regions = $this->collectionFactory
            ->create()
            ->addCountryFilter($code)
            ->addFieldToFilter(
                'main_table.region_id',
                ['in' => $this->getRegions()]
            )
            ->load();

        return $regions->toOptionArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        /** @var array $countries */
        $countries = $this->countryFilter
            ->getCountries();

        /** @var Collection $regions */
        $regions = $this->collectionFactory
            ->create()
            ->addCountryFilter($countries)
            ->addFieldToFilter(
                'main_table.region_id',
                ['in' => $this->getRegions()]
            )
            ->load();

        return $regions->toOptionArray();
    }
}
