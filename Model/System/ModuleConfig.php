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

use AuroraExtensions\ShippingFilters\{
    Component\Data\Container\DataContainerTrait,
    Csi\System\ModuleConfigInterface
};
use Magento\Framework\{
    App\Config\ScopeConfigInterface,
    DataObject,
    DataObject\Factory as DataObjectFactory
};
use Magento\Store\{
    Model\ScopeInterface as StoreScopeInterface,
    Model\Store
};

class ModuleConfig implements ModuleConfigInterface
{
    /**
     * @property DataObject $container
     * @method DataObject|null getContainer()
     */
    use DataContainerTrait;

    /** @constant string XML_PATH_FILTERS_COUNTRY_WHITELIST */
    public const XML_PATH_FILTERS_COUNTRY_WHITELIST = 'shippingfilters/country/whitelist';

    /** @constant string XML_PATH_FILTERS_REGION_WHITELIST */
    public const XML_PATH_FILTERS_REGION_WHITELIST = 'shippingfilters/region/whitelist';

    /** @constant string XML_PATH_FILTERS_LOCALITY_AUTO_ACTIVATE_POSTAL */
    public const XML_PATH_FILTERS_LOCALITY_AUTO_ACTIVATE_POSTAL = 'shippingfilters/locality/auto_activate_postal_code';

    /** @constant string XML_PATH_FILTERS_POSTAL_AUTO_ACTIVATE_LOCALITY */
    public const XML_PATH_FILTERS_POSTAL_AUTO_ACTIVATE_LOCALITY = 'shippingfilters/postal/auto_activate_locality';

    /** @property ScopeConfigInterface $scopeConfig */
    protected $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param DataObjectFactory $dataObjectFactory
     * @param array $data
     * @return void
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        DataObjectFactory $dataObjectFactory,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->container = $dataObjectFactory->create($data);
    }

    /**
     * @param int $store
     * @param string $scope
     * @return string
     */
    public function getCountryWhitelist(
        int $store = Store::DEFAULT_STORE_ID,
        string $scope = StoreScopeInterface::SCOPE_STORE
    ): string
    {
        return $this->scopeConfig->getValue(
            static::XML_PATH_FILTERS_COUNTRY_WHITELIST,
            $scope,
            $store
        ) ?? '';
    }

    /**
     * @param int $store
     * @param string $scope
     * @return string
     */
    public function getRegionWhitelist(
        int $store = Store::DEFAULT_STORE_ID,
        string $scope = StoreScopeInterface::SCOPE_STORE
    ): string
    {
        return $this->scopeConfig->getValue(
            static::XML_PATH_FILTERS_REGION_WHITELIST,
            $scope,
            $store
        ) ?? '';
    }

    /**
     * @param int $store
     * @param string $scope
     * @return bool
     */
    public function getAutoActivatePostalCode(
        int $store = Store::DEFAULT_STORE_ID,
        string $scope = StoreScopeInterface::SCOPE_STORE
    ): bool
    {
        return $this->scopeConfig->isSetFlag(
            static::XML_PATH_FILTERS_LOCALITY_AUTO_ACTIVATE_POSTAL,
            $scope,
            $store
        );
    }

    /**
     * @param int $store
     * @param string $scope
     * @return bool
     */
    public function getAutoActivateLocality(
        int $store = Store::DEFAULT_STORE_ID,
        string $scope = StoreScopeInterface::SCOPE_STORE
    ): bool
    {
        return $this->scopeConfig->isSetFlag(
            static::XML_PATH_FILTERS_POSTAL_AUTO_ACTIVATE_LOCALITY,
            $scope,
            $store
        );
    }
}
