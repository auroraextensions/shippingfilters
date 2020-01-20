<?php
/**
 * Tokenizer.php
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

namespace AuroraExtensions\ShippingFilters\Model\Security;

class Tokenizer
{
    /** @constant string HASH_ALGO */
    public const HASH_ALGO = 'sha512';

    /** @constant string HEX_REGEX */
    public const HEX_REGEX = '/[^a-f0-9]/';

    /** @constant int MIN_BYTES */
    public const MIN_BYTES = 16;

    /**
     * @param int $length
     * @return string
     */
    public static function generate(int $length = 32): string
    {
        /* Enforce minimum length requirement. */
        $length = $length < static::MIN_BYTES
            ? static::MIN_BYTES
            : $length;

        return bin2hex(random_bytes($length));
    }

    /**
     * @param string $token
     * @return string
     */
    public static function getHash(string $token): string
    {
        return hash(static::HASH_ALGO, $token);
    }

    /**
     * @param string $a
     * @param string $b
     * @return bool
     */
    public static function isEqual(string $a, string $b): bool
    {
        return hash_equals($a, $b);
    }

    /**
     * @param string $token
     * @return bool
     */
    public static function isHex(string $token): bool
    {
        return !preg_match(static::HEX_REGEX, $token);
    }
}
