<?php
/**
 * Collection.php
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

namespace AuroraExtensions\ShippingFilters\Model\ResourceModel\Locality;

use AuroraExtensions\ShippingFilters\{
    Api\AbstractCollectionInterface,
    Model\Cache\CacheGenerator,
    Model\Data\Locality,
    Model\ResourceModel\Locality as LocalityResource
};
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection implements AbstractCollectionInterface
{
    /** @constant array SELECT_FIELDS */
    private const SELECT_FIELDS = [
        'locality_id',
        'locality_name',
        'country_code',
        'region_id',
    ];

    /** @var Generator $generator */
    private $generator;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            Locality::class,
            LocalityResource::class
        );
    }

    /**
     * @param string $code
     * @return AbstractCollectionInterface
     */
    public function addCountryCodeFilter(string $code = 'US'): AbstractCollectionInterface
    {
        return $this->addCountryCodesFilter([$code]);
    }

    /**
     * @param array $codes
     * @return AbstractCollectionInterface
     */
    public function addCountryCodesFilter(array $codes = []): AbstractCollectionInterface
    {
        if (!empty($codes)) {
            $this->addFieldToFilter('main_table.country_code', ['in' => $codes]);
        }

        return $this;
    }

    /**
     * @param string $code
     * @return AbstractCollectionInterface
     */
    public function addRegionCodeFilter(string $code): AbstractCollectionInterface
    {
        return $this->addRegionCodesFilter([$code]);
    }

    /**
     * @param array $codes
     * @return AbstractCollectionInterface
     */
    public function addRegionCodesFilter(array $codes = []): AbstractCollectionInterface
    {
        if (!empty($codes)) {
            $this->addFieldToFilter('main_table.region_code', ['in' => $codes]);
        }

        return $this;
    }

    /**
     * @param string $regionId
     * @return AbstractCollectionInterface
     */
    public function addRegionIdFilter(string $regionId): AbstractCollectionInterface
    {
        return $this->addRegionIdsFilter([$regionId]);
    }

    /**
     * @param array $regionIds
     * @return AbstractCollectionInterface
     */
    public function addRegionIdsFilter(array $regionIds = []): AbstractCollectionInterface
    {
        if (!empty($regionIds)) {
            $this->addFieldToFilter('main_table.region_id', ['in' => $regionIds]);
        }

        return $this;
    }

    /**
     * @param string $localityId
     * @return AbstractCollectionInterface
     */
    public function addLocalityIdFilter(string $localityId): AbstractCollectionInterface
    {
        return $this->addLocalityIdsFilter([$localityId]);
    }

    /**
     * @param array $localityIds
     * @return AbstractCollectionInterface
     */
    public function addLocalityIdsFilter(array $localityIds = []): AbstractCollectionInterface
    {
        if (!empty($localityIds)) {
            $this->addFieldToFilter('main_table.locality_id', ['in' => $localityIds]);
        }

        return $this;
    }

    /**
     * @return AbstractCollectionInterface
     */
    public function addMinimalFieldsToSelect(): AbstractCollectionInterface
    {
        $this->addFieldToSelect(static::SELECT_FIELDS);
        return $this;
    }

    /**
     * @return AbstractCollectionInterface
     */
    public function optimizeLoad(): AbstractCollectionInterface
    {
        $this->resetData();

        if (!$this->generator) {
            $this->generator = new CacheGenerator($this->optimizer());
        }

        $this->_setIsLoaded();
        return $this;
    }

    /**
     * @return Generator
     */
    private function optimizer()
    {
        /** @var AdapterInterface $adapter */
        $adapter = $this->getConnection();

        /** @var array $items */
        $items = $adapter->fetchAll($this->getSelect());

        /** @var array $item */
        foreach ($items as $item) {
            yield $item;
        }
    }

    /**
     * @return Generator
     */
    private function getCacheItems()
    {
        return $this->generator
            ->generator();
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        /** @var array $options */
        $options = [];

        /** @var array $item */
        foreach ($this->getCacheItems() as $item) {
            /** @var string $localityName */
            $localityName = $item['locality_name'];

            $options[] = [
                'label' => $localityName,
                'value' => $item['locality_id'],
                'country_id' => $item['country_code'],
                'region_id' => $item['region_id'],
                'locality_name' => $localityName,
            ];
        }

        return $options;
    }
}
