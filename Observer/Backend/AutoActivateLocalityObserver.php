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
    Api\Data\ShippingPostalCodeInterface,
    Api\ShippingLocalityRepositoryInterface,
    Api\ShippingPostalCodeRepositoryInterface,
    Component\Message\MessageManagerTrait,
    Component\System\ModuleConfigTrait,
    Csi\System\ModuleConfigInterface,
    Model\ResourceModel\Locality\Collection,
    Model\ResourceModel\Locality\CollectionFactory
};
use Magento\Framework\{
    Event\Observer,
    Event\ObserverInterface,
    Message\ManagerInterface as MessageManagerInterface,
    UrlInterface
};

class AutoActivateLocalityObserver implements ObserverInterface
{
    /**
     * @property MessageManagerInterface $messageManager
     * @method void addErrorMessage()
     * @method void addNoticeMessage()
     * @method void addSuccessMessage()
     * @method void addWarningMessage()
     * @method void addComplexErrorMessage()
     * @method void addComplexNoticeMessage()
     * @method void addComplexSuccessMessage()
     * @method void addComplexWarningMessage()
     * ---
     * @property ModuleConfigInterface $moduleConfig
     * @method ModuleConfigInterface getModuleConfig()
     */
    use MessageManagerTrait, ModuleConfigTrait;

    /** @constant string CONFIG_PATH */
    public const CONFIG_PATH = 'adminhtml/system_config/edit/section/shippingfilters';

    /** @constant string NOTICE_TMPL */
    public const NOTICE_TMPL = 'autoActivateSuccessMessage';

    /** @property CollectionFactory $collectionFactory */
    protected $collectionFactory;

    /** @property ShippingLocalityRepositoryInterface $localityRepository */
    protected $localityRepository;

    /** @property ShippingPostalCodeRepositoryInterface $postalCodeRepository */
    protected $postalCodeRepository;

    /** @property UrlInterface $urlBuilder */
    protected $urlBuilder;

    /**
     * @param CollectionFactory $collectionFactory
     * @param ShippingLocalityRepositoryInterface $localityRepository
     * @param MessageManagerInterface $messageManager
     * @param ModuleConfigInterface $moduleConfig
     * @param ShippingPostalCodeRepositoryInterface $postalCodeRepository
     * @param UrlInterface $urlBuilder
     * @return void
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ShippingLocalityRepositoryInterface $localityRepository,
        MessageManagerInterface $messageManager,
        ModuleConfigInterface $moduleConfig,
        ShippingPostalCodeRepositoryInterface $postalCodeRepository,
        UrlInterface $urlBuilder
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->localityRepository = $localityRepository;
        $this->messageManager = $messageManager;
        $this->moduleConfig = $moduleConfig;
        $this->postalCodeRepository = $postalCodeRepository;
        $this->urlBuilder = $urlBuilder;
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
                /** @var ShippingPostalCodeInterface $postalCode */
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
                    ->addFieldToFilter('region_id', ['eq' => $regionId])
                    ->optimizeLoad();

                $this->activate($localities);
            }

            /** @var string $configUrl */
            $configUrl = $this->urlBuilder
                ->getUrl(
                    static::CONFIG_PATH,
                    ['_secure' => true]
                );

            $this->addComplexSuccessMessage(static::NOTICE_TMPL, [
                'config_url' => $configUrl,
            ]);
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
        /** @var ShippingLocalityInterface $locality */
        foreach ($collection->getCacheItems() as $locality) {
            $locality->setIsActive(true);
            $this->localityRepository->save($locality);
        }
    }
}
