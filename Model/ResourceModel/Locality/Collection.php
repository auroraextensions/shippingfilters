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
    Model\Data\ShippingLocality,
    Model\ResourceModel\Locality as LocalityResource
};
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection implements AbstractCollectionInterface
{
    /** @constant array SELECT_FIELDS */
    public const SELECT_FIELDS = [
        'locality_id',
        'locality_name',
        'country_code',
        'region_id',
    ];

    /** @property string $_idFieldName */
    protected $_idFieldName = 'locality_id';

    /** @var Generator $generator */
    protected $generator;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            ShippingLocality::class,
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
        $this->_beforeLoad();
        $this->_renderFilters()
            ->_renderOrders()
            ->_renderLimit();
        $this->resetData();

        if (!$this->generator) {
            $this->generator = new CacheGenerator($this->optimizer());
        }

        $this->_setIsLoaded();
        $this->_afterLoad();
        return $this;
    }

    /**
     * @return Generator
     */
    private function optimizer()
    {
        /** @var AdapterInterface $adapter */
        $adapter = $this->getConnection();

        /** @var array $rows */
        $rows = $adapter->fetchAll($this->getSelect());

        /** @var array $row */
        foreach ($rows as $row) {
            /** @var DataObject $item */
            $item = $this->getNewEmptyItem();

            if ($this->getIdFieldName()) {
                $item->setIdFieldName($this->getIdFieldName());
            }

            $item->addData($row);
            $this->beforeAddLoadedItem($item);
            yield $item;
        }
    }

    /**
     * @return Generator
     */
    public function getCacheItems()
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

        /** @var DataObject $item */
        foreach ($this->getCacheItems() as $item) {
            /** @var string $localityName */
            $localityName = $item->getLocalityName();

            $options[] = [
                'label' => $localityName,
                'value' => $item->getId(),
                'country_id' => $item->getCountryCode(),
                'region_id' => $item->getRegionId(),
                'locality_name' => $localityName,
            ];
        }

        return $options;
    }
}
