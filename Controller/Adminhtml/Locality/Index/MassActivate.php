<?php
/**
 * MassActivate.php
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

use AuroraExtensions\ShippingFilters\{
    Api\AbstractCollectionInterface,
    Api\LocalityRepositoryInterface,
    Exception\ExceptionFactory,
    Model\ResourceModel\Locality\Collection,
    Model\ResourceModel\Locality\CollectionFactory
};
use Magento\Backend\App\Action\Context;
use Magento\Framework\{
    App\Action\HttpPostActionInterface,
    Controller\ResultFactory,
    Data\Form\FormKey\Validator as FormKeyValidator
};
use Magento\Ui\Component\MassAction\Filter;

class MassActivate extends AbstractMassAction implements HttpPostActionInterface
{
    /** @constant string ADMIN_RESOURCE */
    public const ADMIN_RESOURCE = 'AuroraExtensions_ShippingFilters::shippingfilters_locality';

    /** @property LocalityRepositoryInterface $localityRepository */
    protected $localityRepository;

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param ExceptionFactory $exceptionFactory
     * @param Filter $filter
     * @param FormKeyValidator $formKeyValidator
     * @param LocalityRepositoryInterface $localityRepository
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        ExceptionFactory $exceptionFactory,
        Filter $filter,
        FormKeyValidator $formKeyValidator,
        LocalityRepositoryInterface $localityRepository
    ) {
        parent::__construct(
            $context,
            $collectionFactory,
            $exceptionFactory,
            $filter,
            $formKeyValidator
        );
        $this->localityRepository = $localityRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function massAction(AbstractCollectionInterface $collection)
    {
        /** @var int $count */
        $count = 0;

        foreach ($collection->getAllIds() as $localityId) {
            /** @var LocalityInterface $locality */
            $locality = $this->localityRepository
                ->getById((int) $localityId);

            $locality->setIsActive(true);
            $this->localityRepository->save($locality);
            $count++;
        }

        if ($count) {
            $this->messageManager
                ->addSuccessMessage(__('A total of %1 record(s) were activated.', $count));
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory
            ->create(ResultFactory::TYPE_REDIRECT)
            ->setPath($this->redirectUrl);

        return $resultRedirect;
    }
}
