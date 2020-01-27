<?php
/**
 * ImportUnitedStatesPostalCodes.php
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
    Api\PostalCodeRepositoryInterface,
    Api\Data\PostalCodeInterface,
    Api\Data\PostalCodeInterfaceFactory
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

class ImportUnitedStatesPostalCodes implements DataPatchInterface
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

    /** @property PostalCodeInterfaceFactory $postalCodeFactory */
    protected $postalCodeFactory;

    /** @property PostalCodeRepositoryInterface $postalCodeRepository */
    protected $postalCodeRepository;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CollectionFactory $collectionFactory
     * @param CsvReader $csvReader
     * @param LoggerInterface $logger
     * @param ModuleReader $moduleReader
     * @param PostalCodeInterfaceFactory $postalCodeFactory
     * @param PostalCodeRepositoryInterface $postalCodeRepository
     * @return void
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CollectionFactory $collectionFactory,
        CsvReader $csvReader,
        LoggerInterface $logger,
        ModuleReader $moduleReader,
        PostalCodeInterfaceFactory $postalCodeFactory,
        PostalCodeRepositoryInterface $postalCodeRepository
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->collectionFactory = $collectionFactory;
        $this->csvReader = $csvReader;
        $this->logger = $logger;
        $this->moduleReader = $moduleReader;
        $this->postalCodeFactory = $postalCodeFactory;
        $this->postalCodeRepository = $postalCodeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [
            ImportUnitedStatesLocalities::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [
            AddUnitedStatesPostalCodes::class,
        ];
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
                /** @var string $postalCode */
                /** @var string $localityName */
                /** @var string $regionName */
                /** @var string $regionCode */
                [
                    $postalCode,
                    $localityName,
                    $regionName,
                    $regionCode,
                ] = $fileRow;

                if (!isset($this->cache[$regionCode])) {
                    $this->cache[$regionCode] = $this->getRegionIdByCode($regionCode);
                }

                /** @var PostalCodeInterface $entity */
                $entity = $this->postalCodeFactory->create();
                $entity->addData([
                    'postal_code' => $postalCode,
                    'locality_name' => $localityName,
                    'region_id' => $this->cache[$regionCode],
                    'region_code' => $regionCode,
                    'region_name' => $regionName,
                    'country_code' => static::COUNTRY_CODE,
                    'country_name' => static::COUNTRY_NAME,
                ]);
                $this->postalCodeRepository->save($entity);
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
        string $directory = 'import/postalcode'
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
