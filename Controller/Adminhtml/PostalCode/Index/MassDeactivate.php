<?php
/**
 * MassDeactivate.php
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

use AuroraExtensions\ShippingFilters\{
    Api\AbstractCollectionInterface,
    Api\PostalCodeRepositoryInterface,
    Exception\ExceptionFactory,
    Model\ResourceModel\PostalCode\Collection,
    Model\ResourceModel\PostalCode\CollectionFactory
};
use Magento\Backend\App\Action\Context;
use Magento\Framework\{
    App\Action\HttpPostActionInterface,
    App\CsrfAwareActionInterface,
    Controller\ResultFactory,
    Data\Form\FormKey\Validator as FormKeyValidator
};
use Magento\Ui\Component\MassAction\Filter;

class MassDeactivate extends AbstractMassAction implements
    HttpPostActionInterface,
    CsrfAwareActionInterface
{
    /** @constant string ADMIN_RESOURCE */
    public const ADMIN_RESOURCE = 'AuroraExtensions_ShippingFilters::shippingfilters_postal_code';

    /** @property PostalCodeRepositoryInterface $postalCodeRepository */
    protected $postalCodeRepository;

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param ExceptionFactory $exceptionFactory
     * @param Filter $filter
     * @param FormKeyValidator $formKeyValidator
     * @param PostalCodeRepositoryInterface $postalCodeRepository
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        ExceptionFactory $exceptionFactory,
        Filter $filter,
        FormKeyValidator $formKeyValidator,
        PostalCodeRepositoryInterface $postalCodeRepository
    ) {
        parent::__construct(
            $context,
            $collectionFactory,
            $exceptionFactory,
            $filter,
            $formKeyValidator
        );
        $this->postalCodeRepository = $postalCodeRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function massAction(AbstractCollectionInterface $collection)
    {
        /** @var int $count */
        $count = 0;

        /** @var int|string $postalCodeId */
        foreach ($collection->getAllIds() as $postalCodeId) {
            /** @var PostalCodeInterface $postalCode */
            $postalCode = $this->postalCodeRepository
                ->getById((int) $postalCodeId);

            $postalCode->setIsActive(false);
            $this->postalCodeRepository->save($postalCode);
            $count++;
        }

        if ($count) {
            $this->messageManager
                ->addSuccessMessage(__('A total of %1 record(s) were deactivated.', $count));
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory
            ->create(ResultFactory::TYPE_REDIRECT)
            ->setPath($this->getComponentRefererUrl());

        return $resultRedirect;
    }
}
