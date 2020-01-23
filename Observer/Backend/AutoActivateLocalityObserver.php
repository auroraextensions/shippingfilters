<?php
/**
 * AutoActivateLocalityObserver.php
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

namespace AuroraExtensions\ShippingFilters\Observer\Backend;

use Exception;
use AuroraExtensions\ShippingFilters\{
    Api\AbstractCollectionInterface,
    Api\Data\PostalCodeInterface,
    Api\LocalityRepositoryInterface,
    Api\PostalCodeRepositoryInterface,
    Component\Message\MessageManagerTrait,
    Component\System\ModuleConfigTrait,
    Csi\System\ModuleConfigInterface,
    Model\ResourceModel\Locality\Collection,
    Model\ResourceModel\Locality\CollectionFactory
};
use Magento\Framework\{
    Event\Observer,
    Event\ObserverInterface,
    Message\ManagerInterface as MessageManagerInterface
};

class AutoActivateLocalityObserver implements ObserverInterface
{
    /**
     * @property MessageManagerInterface $messageManager
     * @method void addErrorMessage()
     * ---
     * @property ModuleConfigInterface $moduleConfig
     * @method ModuleConfigInterface getModuleConfig()
     */
    use MessageManagerTrait, ModuleConfigTrait;

    /** @property CollectionFactory $collectionFactory */
    protected $collectionFactory;

    /** @property LocalityRepositoryInterface $localityRepository */
    protected $localityRepository;

    /** @property PostalCodeRepositoryInterface $postalCodeRepository */
    protected $postalCodeRepository;

    /**
     * @param CollectionFactory $collectionFactory
     * @param LocalityRepositoryInterface $localityRepository
     * @param MessageManagerInterface $messageManager
     * @param ModuleConfigInterface $moduleConfig
     * @param PostalCodeRepositoryInterface $postalCodeRepository
     * @return void
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        LocalityRepositoryInterface $localityRepository,
        MessageManagerInterface $messageManager,
        ModuleConfigInterface $moduleConfig,
        PostalCodeRepositoryInterface $postalCodeRepository
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->localityRepository = $localityRepository;
        $this->messageManager = $messageManager;
        $this->moduleConfig = $moduleConfig;
        $this->postalCodeRepository = $postalCodeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        /** @var ModuleConfigInterface $moduleConfig */
        $moduleConfig = $this->getModuleConfig();

        /** @var int $storeId */
        $storeId = (int) $observer->getEvent()
            ->getStoreId();

        if (!$moduleConfig->getAutoActivateLocality($storeId)) {
            return $this;
        }

        try {
            /** @var AbstractCollectionInterface $collection */
            $collection = $observer->getEvent()
                ->getCollection();

            /** @var int|string $postalCodeId */
            foreach ($collection->getAllIds() as $postalCodeId) {
                /** @var PostalCodeInterface $postalCode */
                $postalCode = $this->postalCodeRepository
                    ->getById((int) $postalCodeId);

                /** @var string $cityName */
                $cityName = $postalCode->getLocalityName();

                /** @var int $regionId */
                $regionId = $postalCode->getRegionId();

                /** @var AbstractCollectionInterface $localities */
                $localities = $this->collectionFactory
                    ->create()
                    ->addFieldToFilter('locality_name', ['eq' => $cityName])
                    ->addFieldToFilter('region_id', ['eq' => $regionId]);

                $this->activate($localities);
            }

            $this->addSuccessMessage(
                __('Auto-activated required localities. To disable auto-activation, click <a href="%1">here</a>.', '#')
            );
        } catch (Exception $e) {
            /* No action required. */
        }

        return $this;
    }

    /**
     * @param AbstractCollectionInterface $collection
     * @return void
     */
    private function activate(AbstractCollectionInterface $collection): void
    {
        /** @var LocalityInterface $locality */
        foreach ($collection as $locality) {
            $locality->setIsActive(true);
            $this->localityRepository->save($locality);
        }
    }
}
