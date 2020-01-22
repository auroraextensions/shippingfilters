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

namespace AuroraExtensions\ShippingFilters\Controller\Adminhtml\PostalCode\Index;

use Exception;
use AuroraExtensions\ShippingFilters\{
    Api\AbstractCollectionInterface,
    Exception\ExceptionFactory,
    Model\ResourceModel\PostalCode\Collection,
    Model\ResourceModel\PostalCode\CollectionFactory
};
use Magento\Backend\{
    App\Action,
    App\Action\Context
};
use Magento\Framework\{
    App\ResponseInterface,
    App\RequestInterface,
    App\Request\InvalidRequestException,
    Controller\ResultFactory,
    Controller\ResultInterface,
    Data\Form\FormKey\Validator as FormKeyValidator
};
use Magento\Ui\Component\MassAction\Filter;

abstract class AbstractMassAction extends Action
{
    /** @constant string ADMIN_RESOURCE */
    public const ADMIN_RESOURCE = 'AuroraExtensions_ShippingFilters::shippingfilters_postal_code';

    /** @property CollectionFactory $collectionFactory */
    protected $collectionFactory;

    /** @property ExceptionFactory $exceptionFactory */
    protected $exceptionFactory;

    /** @property Filter $filter */
    protected $filter;

    /** @property FormKeyValidator $formKeyValidator */
    protected $formKeyValidator;

    /** @property string $redirectUrl */
    protected $redirectUrl = '*/*/index';

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param ExceptionFactory $exceptionFactory
     * @param Filter $filter
     * @param FormKeyValidator $formKeyValidator
     * @return void
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        ExceptionFactory $exceptionFactory,
        Filter $filter,
        FormKeyValidator $formKeyValidator
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->exceptionFactory = $exceptionFactory;
        $this->filter = $filter;
        $this->formKeyValidator = $formKeyValidator;
    }

    /**
     * @return Redirect
     * @throws LocalizedException|Exception
     */
    public function execute()
    {
        try {
            if (!$this->formKeyValidator->validate($this->getRequest())) {
                /** @var Exception $exception */
                $exception = $this->exceptionFactory->create();

                throw $exception;
            }

            /** @var AbstractCollectionInterface $collection */
            $collection = $this->filter
                ->getCollection($this->collectionFactory->create());

            return $this->massAction($collection);
        } catch (Exception $e) {
            $this->messageManager
                ->addErrorMessage($e->getMessage());

            /** @var Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory
                ->create(ResultFactory::TYPE_REDIRECT);

            return $resultRedirect->setPath($this->redirectUrl);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createCsrfValidationException(
        RequestInterface $request
    ): ?InvalidRequestException
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }

    /**
     * @return string
     */
    protected function getComponentRefererUrl()
    {
        return $this->filter->getComponentRefererUrl() ?: 'shippingfilters/postalcode/index';
    }

    /**
     * @param AbstractCollectionInterface $collection
     * @return ResponseInterface|ResultInterface
     */
    abstract protected function massAction(AbstractCollectionInterface $collection);
}
