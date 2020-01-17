<?php
/**
 * CacheGenerator.php
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
 * @link          https://www.php.net/manual/en/language.generators.syntax.php#116577
 */
declare(strict_types=1);

namespace AuroraExtensions\ShippingFilters\Model\Cache;

use Generator;

class CacheGenerator
{
    /** @property array $cache */
    protected $cache = [];

    /** @property Generator $generator */
    protected $generator;

    /**
     * @param Generator $generator
     * @return void
     */
    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @return Generator
     */
    public function generator()
    {
        foreach($this->cache as $item) {
            yield $item;
        }

        while ($this->generator->valid()) {
            /** @var array $current */
            $current = $this->generator->current();

            $this->cache[] = $current;
            $this->generator->next();

            yield $current;
        }
    }
}
