<?php
/**
 * ImportUnitedStatesLocalities.php
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

namespace AuroraExtensions\ShippingFilters\Setup\Patch\Data;

use Exception;
use AuroraExtensions\ShippingFilters\{
    Api\ShippingLocalityRepositoryInterface,
    Api\Data\ShippingLocalityInterface,
    Api\Data\ShippingLocalityInterfaceFactory
};
use Magento\Directory\{
    Model\ResourceModel\Region\Collection,
    Model\ResourceModel\Region\CollectionFactory
};
use Magento\Framework\{
    File\Csv as CsvReader,
    Module\Dir as ModuleDir,
    Module\Dir\Reader as ModuleReader,
    Setup\ModuleDataSetupInterface,
    Setup\Patch\DataPatchInterface
};
use Psr\Log\LoggerInterface;

class ImportUnitedStatesLocalities implements DataPatchInterface
{
    /** @constant string COUNTRY_CODE */
    public const COUNTRY_CODE = 'US';

    /** @constant string COUNTRY_NAME */
    public const COUNTRY_NAME = 'United States';

    /** @constant string IMPORT_FILE */
    public const IMPORT_FILE = 'US.csv';

    /** @constant string MODULE_NAME */
    public const MODULE_NAME = 'AuroraExtensions_ShippingFilters';

    /** @property ModuleDataSetupInterface $moduleDataSetup */
    protected $moduleDataSetup;

    /** @var array $cache */
    private $cache = [];

    /** @property CollectionFactory $collectionFactory */
    protected $collectionFactory;

    /** @property CsvReader $csvReader */
    protected $csvReader;

    /** @property LoggerInterface $logger */
    protected $logger;

    /** @property ModuleReader $moduleReader */
    protected $moduleReader;

    /** @property ShippingLocalityInterfaceFactory $localityFactory */
    protected $localityFactory;

    /** @property ShippingLocalityRepositoryInterface $localityRepository */
    protected $localityRepository;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CollectionFactory $collectionFactory
     * @param CsvReader $csvReader
     * @param LoggerInterface $logger
     * @param ModuleReader $moduleReader
     * @param ShippingLocalityInterfaceFactory $localityFactory
     * @param ShippingLocalityRepositoryInterface $localityRepository
     * @return void
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CollectionFactory $collectionFactory,
        CsvReader $csvReader,
        LoggerInterface $logger,
        ModuleReader $moduleReader,
        ShippingLocalityInterfaceFactory $localityFactory,
        ShippingLocalityRepositoryInterface $localityRepository
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->collectionFactory = $collectionFactory;
        $this->csvReader = $csvReader;
        $this->logger = $logger;
        $this->moduleReader = $moduleReader;
        $this->localityFactory = $localityFactory;
        $this->localityRepository = $localityRepository;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup
            ->getConnection()
            ->startSetup();

        try {
            /** @var string $filePath */
            $filePath = $this->getEtcFilePath(static::IMPORT_FILE);

            /** @var array $fileData */
            $fileData = $this->csvReader
                ->getData($filePath);

            /** @var array $fileRow */
            foreach ($fileData as $fileRow) {
                /** @var string $locality */
                /** @var string $countyName */
                /** @var string $regionName */
                /** @var string $regionCode */
                [
                    $locality,
                    $countyName,
                    $regionName,
                    $regionCode,
                ] = $fileRow;

                if (!isset($this->cache[$regionCode])) {
                    $this->cache[$regionCode] = $this->getRegionIdByCode($regionCode);
                }

                /** @var ShippingLocalityInterface $entity */
                $entity = $this->localityFactory->create();
                $entity->addData([
                    'locality_name' => $locality,
                    'region_id' => $this->cache[$regionCode],
                    'region_code' => $regionCode,
                    'region_name' => $regionName,
                    'country_code' => static::COUNTRY_CODE,
                    'country_name' => static::COUNTRY_NAME,
                ]);
                $this->localityRepository->save($entity);
            }
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }

        $this->moduleDataSetup
            ->getConnection()
            ->endSetup();
    }

    /**
     * @param string $filename
     * @param string $directory
     * @return string
     */
    private function getEtcFilePath(
        string $filename,
        string $directory = 'import/locality'
    ): string
    {
        /** @var string $modulePath */
        $modulePath = $this->moduleReader
            ->getModuleDir(
                ModuleDir::MODULE_ETC_DIR,
                static::MODULE_NAME
            );

        return $modulePath . '/' . $directory . '/' . $filename;
    }

    /**
     * @param string $regionCode
     * @return int
     */
    private function getRegionIdByCode(string $regionCode): int
    {
        /** @var Region $region */
        $region = $this->collectionFactory
            ->create()
            ->addCountryFilter(static::COUNTRY_CODE)
            ->addRegionCodeFilter($regionCode)
            ->getFirstItem();

        return (int) $region->getId();
    }
}
