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

namespace AuroraExtensions\ShippingFilters\Model\ResourceModel\PostalCode;

use AuroraExtensions\ShippingFilters\{
    Api\AbstractCollectionInterface,
    Model\Data\PostalCode,
    Model\ResourceModel\PostalCode as PostalCodeResource
};
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection implements AbstractCollectionInterface
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            PostalCode::class,
            PostalCodeResource::class
        );
    }

    /**
     * @param string $code
     * @return $this
     */
    public function addCountryCodeFilter(string $code = 'US'): AbstractCollectionInterface
    {
        return $this->addCountryCodesFilter([$code]);
    }

    /**
     * @param array $codes
     * @return $this
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
     * @return $this
     */
    public function addRegionCodeFilter(string $code): AbstractCollectionInterface
    {
        return $this->addRegionCodesFilter([$code]);
    }

    /**
     * @param array $codes
     * @return $this
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
     * @return $this
     */
    public function addRegionIdFilter(string $regionId): AbstractCollectionInterface
    {
        return $this->addRegionIdsFilter([$regionId]);
    }

    /**
     * @param array $regionIds
     * @return $this
     */
    public function addRegionIdsFilter(array $regionIds = []): AbstractCollectionInterface
    {
        if (!empty($codes)) {
            $this->addFieldToFilter('main_table.region_id', ['in' => $regionIds]);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        /** @var array $options */
        $options = [];

        /** @var array $fieldsMap */
        $fieldsMap = [
            'value' => 'postal_code_id',
            'title' => 'postal_name',
            'country_id' => 'country_code',
            'region_id' => 'region_id',
        ];

        /** @var PostalCodeInterface $item */
        foreach ($this as $item) {
            /** @var array $option */
            $option = [];

            /** @var string $code */
            /** @var string $field */
            foreach ($fieldsMap as $code => $field) {
                $option[$code] = $item->getData($field);
            }

            $option['label'] = sprintf(
                '%s [%s]',
                $item->getPostalName(),
                $item->getPostalCode()
            );
            $options[] = $option;
        }

        return $options;
    }
}
