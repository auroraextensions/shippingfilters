<?php
/**
 * AddCustomFiltersConfig.php
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

namespace AuroraExtensions\ShippingFilters\Plugin\Checkout\Provider;

use AuroraExtensions\ShippingFilters\{
    Component\System\ModuleConfigTrait,
    Csi\System\ModuleConfigInterface
};
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;

class AddCustomFiltersConfig
{
    /**
     * @property ModuleConfigInterface $moduleConfig
     * @method ModuleConfigInterface getModuleConfig()
     */
    use ModuleConfigTrait;

    /**
     * @param ModuleConfigInterface $moduleConfig
     * @return void
     */
    public function __construct(
        ModuleConfigInterface $moduleConfig
    ) {
        $this->moduleConfig = $moduleConfig;
    }

    /**
     * @param LayoutProcessorInterface $subject
     * @param array $result
     * @return array
     */
    public function afterProcess(
        LayoutProcessorInterface $subject,
        array $result
    ): array
    {
        /** @var array $filters */
        $filters = &$result['components']['checkoutProvider']['customFilters'];

        if (!isset($filters)) {
            $filters = [];
        }

        /** @var array $customFilters */
        $customFilters = $this->getModuleConfig()
            ->getContainer()
            ->getCustomFilters();

        /** @var array $countries */
        $countries = array_values(
            $customFilters['countries'] ?? []
        );

        $filters += [
            'countries' => $countries,
        ];

        return $result;
    }
}
