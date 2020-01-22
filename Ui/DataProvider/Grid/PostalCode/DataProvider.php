<?php
/**
 * DataProvider.php
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

namespace AuroraExtensions\ShippingFilters\Ui\DataProvider\Grid\PostalCode;

use Countable;
use AuroraExtensions\ShippingFilters\{
    Component\Ui\DataProvider\Modifier\ModifierPoolTrait,
    Model\ResourceModel\PostalCode\Collection,
    Model\ResourceModel\PostalCode\CollectionFactory
};
use Magento\Framework\{
    Api\Filter,
    View\Element\UiComponent\DataProvider\DataProviderInterface
};
use Magento\Ui\{
    DataProvider\AbstractDataProvider,
    DataProvider\AddFieldToCollectionInterface,
    DataProvider\AddFilterToCollectionInterface
};

class DataProvider extends AbstractDataProvider implements
    Countable,
    DataProviderInterface
{
    /**
     * @property PoolInterface $modifierPool
     * @method PoolInterface getModifierPool()
     * @method ModifierInterface[] getModifiers()
     */
    use ModifierPoolTrait;

    /** @property AddFieldToCollectionInterface[] $addFieldStrategies */
    protected $addFieldStrategies;

    /** @property AddFilterToCollectionInterface[] $addFilterStrategies */
    protected $addFilterStrategies;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     * @param AddFieldToCollectionInterface[] $addFieldStrategies
     * @param AddFilterToCollectionInterface[] $addFilterStrategies
     * @param CollectionFactory $collectionFactory
     * @return void
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = [],
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        CollectionFactory $collectionFactory
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
        $this->addFieldStrategies = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;
        $this->collection = $collectionFactory->create();
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->getCollection()->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function addField($field, $alias = null)
    {
        if (isset($this->addFieldStrategies[$field])) {
            $this->addFieldStrategies[$field]
                ->addField($this->getCollection(), $field, $alias);
        } else {
            parent::addField($field, $alias);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter(Filter $filter)
    {
        /** @var string $field */
        $field = $filter->getField();

        if (isset($this->addFilterStrategies[$field])) {
            $this->addFilterStrategies[$field]
                ->addFilter(
                    $this->getCollection(),
                    $field,
                    [$filter->getConditionType() => $filter->getValue()]
                );
        } else {
            parent::addFilter($filter);
        }
    }
}
