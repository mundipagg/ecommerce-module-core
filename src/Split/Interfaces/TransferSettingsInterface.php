<?php

namespace Mundipagg\Core\Split\Interfaces;

use Mundipagg\Core\Split\Aggregates\TransferSettings;
use Mundipagg\Core\Split\Exceptions\InvalidTransferConfigurationException;
use Mundipagg\Core\Split\ValueObjects\TransferInterval;

interface TransferSettingsInterface
{
    /**
     * @param string $recipientId
     * @return TransferInterval
     */
    public function setRecipientId($recipientId);

    /**
     * @return string
     */
    public function getRecipientId();

    /**
     * @return TransferInterval
     */
    public function getTransferInterval();

    /**
     * @param TransferInterval $transferInterval
     */
    public function setTransferInterval(TransferInterval $transferInterval);

    /**
     * @return int
     */
    public function getTransferDay();

    /**
     * @param int $transferDay
     * @throws InvalidTransferConfigurationException
     */
    public function setTransferDay($transferDay);

    /**
     * @return bool
     */
    public function isTransferEnabled();

    /**
     * @param bool $transferEnabled
     * @return TransferSettings
     */
    public function setTransferEnabled($transferEnabled);
}
