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
    public function addCountryFilter(string $code = 'US'): AbstractCollectionInterface
    {
        return $this->addCountriesFilter([$code]);
    }

    /**
     * @param array $codes
     * @return $this
     */
    public function addCountriesFilter(array $codes = []): AbstractCollectionInterface
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
    public function addRegionFilter(string $code): AbstractCollectionInterface
    {
        return $this->addRegionsFilter([$code]);
    }

    /**
     * @param array $codes
     * @return $this
     */
    public function addRegionsFilter(array $codes = []): AbstractCollectionInterface
    {
        if (!empty($codes)) {
            $this->addFieldToFilter('main_table.region_code', ['in' => $codes]);
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
