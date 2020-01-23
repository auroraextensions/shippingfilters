<?php
/**
 * MessageManagerTrait.php
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

namespace AuroraExtensions\ShippingFilters\Component\Message;

trait MessageManagerTrait
{
    /** @property Magento\Framework\Message\ManagerInterface $messageManager */
    private $messageManager;

    /**
     * @param Phrase|string $message
     * @param string|null $group
     * @return void
     */
    private function addErrorMessage($message, string $group = null): void
    {
        $this->messageManager
            ->addErrorMessage($message, $group);
    }

    /**
     * @param Phrase|string $message
     * @param string|null $group
     * @return void
     */
    private function addNoticeMessage($message, string $group = null): void
    {
        $this->messageManager
            ->addNoticeMessage($message, $group);
    }

    /**
     * @param Phrase|string $message
     * @param string|null $group
     * @return void
     */
    private function addSuccessMessage($message, string $group = null): void
    {
        $this->messageManager
            ->addSuccessMessage($message, $group);
    }

    /**
     * @param Phrase|string $message
     * @param string|null $group
     * @return void
     */
    private function addWarningMessage($message, string $group = null): void
    {
        $this->messageManager
            ->addWarningMessage($message, $group);
    }
}
