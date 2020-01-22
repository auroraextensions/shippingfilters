<?php
/**
 * MassAction.php
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

namespace AuroraExtensions\ShippingFilters\Ui\Component\Control;

use Magento\Framework\{
    Data\Form\FormKey,
    View\Element\UiComponent\ContextInterface
};
use Magento\Ui\Component\Control\Action;

class MassAction extends Action
{
    /** @property FormKey $formKey */
    private $formKey;

    /**
     * @param ContextInterface $context
     * @param array $components
     * @param array $data
     * @param FormKey $formKey
     * @return void
     */
    public function __construct(
        ContextInterface $context,
        array $components = [],
        array $data = [],
        FormKey $formKey
    ) {
        parent::__construct(
            $context,
            $components,
            $data
        );
        $this->formKey = $formKey;
    }

    /**
     * @return void
     */
    public function prepare(): void
    {
        /** @var array $config */
        $config = $this->getConfiguration();

        /** @var ContextInterface $context */
        $context = $this->getContext();

        /** @var string $formKey */
        $formKey = $this->formKey
            ->getFormKey();

        $config['url'] = $context->getUrl(
            $config['actionPath'],
            ['form_key' => $formKey]
        );
        $this->setData('config', $config);
        parent::prepare();
    }
}
