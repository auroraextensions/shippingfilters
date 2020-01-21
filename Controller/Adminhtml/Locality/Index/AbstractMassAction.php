<?php
/**
 * AbstractMassAction.php
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

namespace AuroraExtensions\ShippingFilters\Controller\Adminhtml\Locality\Index;

use Exception;
use AuroraExtensions\ShippingFilters\{
    Api\AbstractCollectionInterface,
    Model\ResourceModel\Locality\Collection,
    Model\ResourceModel\Locality\CollectionFactory
};
use Magento\Backend\{
    App\Action,
    App\Action\Context
};
use Magento\Framework\{
    App\ResponseInterface,
    Controller\ResultFactory,
    Controller\ResultInterface
};
use Magento\Ui\Component\MassAction\Filter;

abstract class AbstractMassAction extends Action
{
    /** @constant string ADMIN_RESOURCE */
    public const ADMIN_RESOURCE = 'AuroraExtensions_ShippingFilters::shippingfilters_locality';

    /** @property CollectionFactory $collectionFactory */
    protected $collectionFactory;

    /** @property Filter $filter */
    protected $filter;

    /** @property string $redirectUrl */
    protected $redirectUrl = '*/*/index';

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     * @return void
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        Filter $filter
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
    }

    /**
     * @return Redirect
     * @throws LocalizedException|Exception
     */
    public function execute()
    {
        try {
            /** @var AbstractCollectionInterface $collection */
            $collection = $this->filter
                ->getCollection($this->collectionFactory->create());

            return $this->massAction($collection);
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());

            /** @var Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory
                ->create(ResultFactory::TYPE_REDIRECT);

            return $resultRedirect->setPath($this->redirectUrl);
        }
    }

    /**
     * @param AbstractCollectionInterface $collection
     * @return ResponseInterface|ResultInterface
     */
    abstract protected function massAction(AbstractCollectionInterface $collection);
}
