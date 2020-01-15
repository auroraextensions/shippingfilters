<?php
/**
 * ModuleConfig.php
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

namespace AuroraExtensions\ShippingFilters\Model\System;

use AuroraExtensions\ShippingFilters\Csi\System\ModuleConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\{
    Model\ScopeInterface as StoreScopeInterface,
    Model\Store
};

class ModuleConfig implements ModuleConfigInterface
{
    /** @constant string XML_PATH_FILTERS_COUNTRY_WHITELIST */
    public const XML_PATH_FILTERS_COUNTRY_WHITELIST = 'shippingfilters/country/whitelist';

    /** @constant string XML_PATH_FILTERS_REGION_WHITELIST */
    public const XML_PATH_FILTERS_REGION_WHITELIST = 'shippingfilters/region/whitelist';

    /** @property ScopeConfigInterface $scopeConfig */
    protected $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @return void
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param int $store
     * @param string $scope
     * @return string|null
     */
    public function getCountryWhitelist(
        int $store = Store::DEFAULT_STORE_ID,
        string $scope = StoreScopeInterface::SCOPE_STORE
    ): ?string
    {
        return $this->scopeConfig->getValue(
            static::XML_PATH_FILTERS_COUNTRY_WHITELIST,
            $scope,
            $store
        );
    }

    /**
     * @param int $store
     * @param string $scope
     * @return string|null
     */
    public function getRegionWhitelist(
        int $store = Store::DEFAULT_STORE_ID,
        string $scope = StoreScopeInterface::SCOPE_STORE
    ): ?string
    {
        return $this->scopeConfig->getValue(
            static::XML_PATH_FILTERS_REGION_WHITELIST,
            $scope,
            $store
        );
    }
}
