<?php
/**
 * Locality.php
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

namespace AuroraExtensions\ShippingFilters\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Locality extends AbstractDb
{
    /** @property string $_idFieldName */
    protected $_idFieldName = 'locality_id';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'shippingfilters_locality',
            'locality_id'
        );
    }
}
