<?php
/**
 * ShippingLocalityRepository.php
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

namespace AuroraExtensions\ShippingFilters\Model\Repository;

use AuroraExtensions\ShippingFilters\{
    Api\AbstractCollectionInterface,
    Api\ShippingLocalityRepositoryInterface,
    Api\Data\ShippingLocalityInterface,
    Api\Data\ShippingLocalityInterfaceFactory,
    Component\Repository\AbstractRepositoryTrait,
    Exception\ExceptionFactory,
    Model\ResourceModel\Locality as LocalityResource,
    Model\ResourceModel\Locality\Collection,
    Model\ResourceModel\Locality\CollectionFactory
};
use Magento\Framework\{
    Api\SearchResultsInterface,
    Api\SearchResultsInterfaceFactory,
    Exception\NoSuchEntityException
};

class ShippingLocalityRepository implements ShippingLocalityRepositoryInterface
{
    /**
     * @method void addFilterGroupToCollection()
     * @method string getDirection()
     * @method SearchResultsInterface getList()
     */
    use AbstractRepositoryTrait;

    /** @property CollectionFactory $collectionFactory */
    protected $collectionFactory;

    /** @property ExceptionFactory $exceptionFactory */
    protected $exceptionFactory;

    /** @property ShippingLocalityInterfaceFactory $localityFactory */
    protected $localityFactory;

    /** @property LocalityResource $localityResource */
    protected $localityResource;

    /** @property SearchResultsInterfaceFactory $searchResultsFactory */
    protected $searchResultsFactory;

    /**
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param ExceptionFactory $exceptionFactory
     * @param ShippingLocalityInterfaceFactory $localityFactory
     * @param LocalityResource $localityResource
     * @return void
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        ExceptionFactory $exceptionFactory,
        ShippingLocalityInterfaceFactory $localityFactory,
        LocalityResource $localityResource
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->exceptionFactory = $exceptionFactory;
        $this->localityFactory = $localityFactory;
        $this->localityResource = $localityResource;
    }

    /**
     * @param int $id
     * @return ShippingLocalityInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): ShippingLocalityInterface
    {
        /** @var ShippingLocalityInterface $locality */
        $locality = $this->localityFactory->create();
        $this->localityResource->load($locality, $id);

        if (!$locality->getId()) {
            /** @var NoSuchEntityException $exception */
            $exception = $this->exceptionFactory->create(
                NoSuchEntityException::class,
                __('Unable to locate postal code information.')
            );

            throw $exception;
        }

        return $locality;
    }

    /**
     * @param ShippingLocalityInterface $locality
     * @return int
     */
    public function save(ShippingLocalityInterface $locality): int
    {
        $this->localityResource->save($locality);
        return (int) $locality->getId();
    }

    /**
     * @param ShippingLocalityInterface $locality
     * @return bool
     */
    public function delete(ShippingLocalityInterface $locality): bool
    {
        return $this->deleteById((int) $locality->getId());
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        /** @var ShippingLocalityInterface $locality */
        $locality = $this->localityFactory->create();
        $locality->setId($id);

        return (bool) $this->localityResource->delete($locality);
    }
}
