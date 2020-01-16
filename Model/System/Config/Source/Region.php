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

namespace AuroraExtensions\ShippingFilters\Model\System\Config\Source;

use AuroraExtensions\ShippingFilters\{
    Csi\Filter\CountryFilterInterface,
    Csi\Filter\RegionFilterInterface
};
use Magento\Directory\{
    Api\CountryInformationAcquirerInterface,
    Model\ResourceModel\Region\Collection,
    Model\ResourceModel\Region\CollectionFactory
};
use Magento\Framework\{
    Data\OptionSourceInterface,
    Exception\NoSuchEntityException
};
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Region implements OptionSourceInterface
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

        /** @var array $countries */
        $countries = $this->countryFilter
            ->getCountries();

        /** @var string $code */
        foreach ($countries as $code) {
            /** @var array $optgroup */
            $optgroup = $this->getRegionsOptgroup($code);

            if (!empty($optgroup)) {
                $options[] = [
                    'label' => $this->getCountryNameByCode($code),
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
    protected function getCountryNameByCode(string $code): string
    {
        try {
            /** @var CountryInformationInterface $countryInfo */
            $countryInfo = $this->countryInfoAcquirer
                ->getCountryInfo($code);

            return $countryInfo->getFullNameLocale();
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
        }

        return $code;
    }

    /**
     * @param string $code
     * @return array
     */
    protected function getRegionsOptgroup(string $code): array
    {
        /** @var Collection $regions */
        $regions = $this->collectionFactory
            ->create()
            ->addCountryFilter($code)
            ->load();

        /** @var array $options */
        $options = $regions->toOptionArray();

        if (!empty($options)) {
            array_shift($options);
        }

        return $options;
    }
}
