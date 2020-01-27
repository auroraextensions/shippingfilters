<?php
/**
 * PostalCodeRepository.php
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
    Api\PostalCodeRepositoryInterface,
    Api\Data\PostalCodeInterface,
    Api\Data\PostalCodeInterfaceFactory,
    Component\Repository\AbstractRepositoryTrait,
    Exception\ExceptionFactory,
    Model\Data\PostalCode,
    Model\ResourceModel\PostalCode as PostalCodeResource,
    Model\ResourceModel\PostalCode\Collection,
    Model\ResourceModel\PostalCode\CollectionFactory
};
use Magento\Directory\Api\Data\RegionInformationInterface;
use Magento\Framework\{
    Api\SearchResultsInterface,
    Api\SearchResultsInterfaceFactory,
    Exception\NoSuchEntityException
};

class PostalCodeRepository implements PostalCodeRepositoryInterface
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

    /** @property PostalCodeInterfaceFactory $postalCodeFactory */
    protected $postalCodeFactory;

    /** @property PostalCodeResource $postalCodeResource */
    protected $postalCodeResource;

    /** @property SearchResultsInterfaceFactory $searchResultsFactory */
    protected $searchResultsFactory;

    /**
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param ExceptionFactory $exceptionFactory
     * @param PostalCodeInterfaceFactory $postalCodeFactory
     * @param PostalCodeResource $postalCodeResource
     * @return void
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        ExceptionFactory $exceptionFactory,
        PostalCodeInterfaceFactory $postalCodeFactory,
        PostalCodeResource $postalCodeResource
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->exceptionFactory = $exceptionFactory;
        $this->postalCodeFactory = $postalCodeFactory;
        $this->postalCodeResource = $postalCodeResource;
    }

    /**
     * @param RegionInformationInterface $region
     * @return PostalCodeInterface
     * @throws NoSuchEntityException
     */
    public function get(RegionInformationInterface $region): PostalCodeInterface
    {
        /** @var PostalCodeInterface $postalCode */
        $postalCode = $this->postalCodeFactory->create();
        $this->postalCodeResource->load(
            $postalCode,
            $region->getId(),
            'region_id'
        );

        if (!$postalCode->getId()) {
            /** @var NoSuchEntityException $exception */
            $exception = $this->exceptionFactory->create(
                NoSuchEntityException::class,
                __('Unable to locate postal code information.')
            );

            throw $exception;
        }

        return $postalCode;
    }

    /**
     * @param int $id
     * @return PostalCodeInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): PostalCodeInterface
    {
        /** @var PostalCodeInterface $postalCode */
        $postalCode = $this->postalCodeFactory->create();
        $this->postalCodeResource->load($postalCode, $id);

        if (!$postalCode->getId()) {
            /** @var NoSuchEntityException $exception */
            $exception = $this->exceptionFactory->create(
                NoSuchEntityException::class,
                __('Unable to locate postal code information.')
            );

            throw $exception;
        }

        return $postalCode;
    }

    /**
     * @param PostalCodeInterface $postalCode
     * @return int
     */
    public function save(PostalCodeInterface $postalCode): int
    {
        $this->postalCodeResource->save($postalCode);
        return (int) $postalCode->getId();
    }

    /**
     * @param PostalCodeInterface $postalCode
     * @return bool
     */
    public function delete(PostalCodeInterface $postalCode): bool
    {
        return $this->deleteById((int) $postalCode->getId());
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        /** @var PostalCodeInterface $postalCode */
        $postalCode = $this->postalCodeFactory->create();
        $postalCode->setId($id);

        return (bool) $this->postalCodeResource->delete($postalCode);
    }
}
