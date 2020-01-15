<?php
/**
 * CountryFilter.php
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
    Csi\System\ModuleConfigInterface
};
use Magento\Directory\{
    Model\ResourceModel\Country\Collection,
    Model\ResourceModel\Country\CollectionFactory
};
use Magento\Store\Model\StoreManagerInterface;

class CountryFilter implements CountryFilterInterface
{
    /**
     * @property ModuleConfigInterface $moduleConfig
     * @method ModuleConfigInterface getModuleConfig()
     */
    use ModuleConfigTrait;

    /** @property CollectionFactory $collectionFactory */
    protected $collectionFactory;

    /** @property StoreManagerInterface $storeManager */
    protected $storeManager;

    /**
     * @param CollectionFactory $collectionFactory
     * @param ModuleConfigInterface $moduleConfig
     * @param StoreManagerInterface $storeManager
     * @return void
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ModuleConfigInterface $moduleConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->moduleConfig = $moduleConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountries(): array
    {
        /** @var StoreInterface $store */
        $store = $this->storeManager->getStore();

        /** @var string|null $whitelist */
        $whitelist = $this->getModuleConfig()
            ->getCountryWhitelist((int) $store->getId());

        /** @var array $countries */
        $countries = array_filter(
            array_map(
                'trim',
                explode(',', $whitelist)
            ),
            'strlen'
        );

        return $countries;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        /** @var StoreInterface $store */
        $store = $this->storeManager->getStore();

        /** @var Collection $countries */
        $countries = $this->collectionFactory
            ->create()
            ->loadByStore($store)
            ->addFieldToFilter('country_id', ['in' => $this->getCountries()]);

        return $countries->toOptionArray();
    }
}
