<?php
/**
 * Locality.php
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

namespace AuroraExtensions\ShippingFilters\Model\System\Config\Source;

use AuroraExtensions\ShippingFilters\{
    Csi\Filter\CountryFilterInterface,
    Csi\Filter\RegionFilterInterface,
    Model\ResourceModel\Locality\Collection,
    Model\ResourceModel\Locality\CollectionFactory
};
use Magento\Directory\Api\CountryInformationAcquirerInterface;
use Magento\Framework\{
    Data\OptionSourceInterface,
    Exception\NoSuchEntityException
};
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Locality implements OptionSourceInterface
{
    /** @property CollectionFactory $collectionFactory */
    protected $collectionFactory;

    /** @property CountryFilterInterface $countryFilter */
    protected $countryFilter;

    /** @property CountryInformationAcquirerInterface $countryInfo */
    protected $countryInfoAcquirer;

    /** @property LoggerInterface $logger */
    protected $logger;

    /** @property RegionFilterInterface $regionFilter */
    protected $regionFilter;

    /** @property StoreManagerInterface $storeManager */
    protected $storeManager;

    /**
     * @param CollectionFactory $collectionFactory
     * @param CountryFilterInterface $countryFilter
     * @param CountryInformationAcquirerInterface $countryInfoAcquirer
     * @param LoggerInterface $logger
     * @param RegionFilterInterface $regionFilter
     * @param StoreManagerInterface $storeManager
     * @return void
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        CountryFilterInterface $countryFilter,
        CountryInformationAcquirerInterface $countryInfoAcquirer,
        LoggerInterface $logger,
        RegionFilterInterface $regionFilter,
        StoreManagerInterface $storeManager
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->countryFilter = $countryFilter;
        $this->countryInfoAcquirer = $countryInfoAcquirer;
        $this->logger = $logger;
        $this->regionFilter = $regionFilter;
        $this->storeManager = $storeManager;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        /** @var array $options */
        $options = [];

        /** @var array $regions */
        $regions = $this->regionFilter
            ->getRegions();

        /** @var string $code */
        foreach ($regions as $code) {
            /** @var array $optgroup */
            $optgroup = $this->getLocalitiesOptgroup($code);

            if (!empty($optgroup)) {
                $options[] = [
                    'label' => __($this->getRegionNameByCode($code)),
                    'value' => $optgroup,
                ];
            }
        }

        return $options;
    }

    /**
     * @param string $code
     * @return string
     */
    protected function getRegionNameByCode(string $code): string
    {
        try {
            /** @var array $countries */
            $countries = $this->countryFilter
                ->getCountries();

            /** @var LocalityInterface $locality */
            $locality = $this->collectionFactory
                ->create()
                ->addCountryCodesFilter($countries)
                ->addRegionIdFilter($code)
                ->getFirstItem();

            return $locality->getRegionName();
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
        }

        return $code;
    }

    /**
     * @param string $code
     * @return array
     */
    protected function getLocalitiesOptgroup(string $code): array
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
            ->optimizeLoad();

        return $localities->toOptionArray();
    }
}
