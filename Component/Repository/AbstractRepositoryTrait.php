<?php
/**
 * AbstractRepositoryTrait.php
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

namespace AuroraExtensions\ShippingFilters\Component\Repository;

use AuroraExtensions\ShippingFilters\Api\AbstractCollectionInterface;
use Magento\Framework\{
    Api\SearchCriteriaInterface,
    Api\SearchResultsInterface,
    Api\Search\FilterGroup,
    Api\SortOrder,
    Exception\NoSuchEntityException
};

trait AbstractRepositoryTrait
{
    /**
     * @param FilterGroup $group
     * @param AbstractCollectionInterface $collection
     * @return void
     */
    public function addFilterGroupToCollection(
        FilterGroup $group,
        AbstractCollectionInterface $collection
    ): void
    {
        /** @var array $fields */
        $fields = [];

        /** @var array $params */
        $params = [];

        foreach ($group->getFilters() as $filter) {
            /** @var string $param */
            $param = $filter->getConditionType() ?: 'eq';

            /** @var string $field */
            $field = $filter->getField();

            /** @var mixed $value */
            $value = $filter->getValue();

            $fields[] = $field;
            $params[] = [
                $param => $value,
            ];
        }

        $collection->addFieldToFilter($fields, $params);
    }

    /**
     * Get sort order direction.
     *
     * @param string $direction
     * @return string
     */
    public function getDirection(
        string $direction = SortOrder::SORT_DESC
    ): string
    {
        return $direction === SortOrder::SORT_ASC
            ? SortOrder::SORT_ASC
            : SortOrder::SORT_DESC;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): SearchResultsInterface
    {
        /** @var AbstractCollectionInterface $collection */
        $collection = $this->collectionFactory->create();

        foreach ($criteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }

        foreach ((array) $criteria->getSortOrders() as $sortOrder) {
            /** @var string $field */
            $field = $sortOrder->getField();

            $collection->addOrder(
                $field,
                $this->getDirection($sortOrder->getDirection())
            );
        }

        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $collection->load();

        /** @var SearchResultsInterface $results */
        $results = $this->searchResultsFactory->create();
        $results->setSearchCriteria($criteria);

        /** @var array $items */
        $items = [];

        foreach ($collection as $item) {
            $items[] = $item;
        }

        $results->setItems($items);
        $results->setTotalCount($collection->getSize());

        return $results;
    }
}
