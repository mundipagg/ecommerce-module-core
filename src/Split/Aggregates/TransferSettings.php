<?php

namespace Mundipagg\Core\Split\Aggregates;

use MundiAPILib\Models\UpdateTransferSettingsRequest;
use Mundipagg\Core\Kernel\Abstractions\AbstractEntity;
use Mundipagg\Core\Split\Exceptions\InvalidTransferConfigurationException;
use Mundipagg\Core\Split\Interfaces\BankAccountInterface;
use Mundipagg\Core\Split\Interfaces\TransferSettingsInterface;
use Mundipagg\Core\Split\ValueObjects\TransferInterval;

class TransferSettings extends AbstractEntity implements TransferSettingsInterface
{
    /**
     * @var bool
     */
    private $transferEnabled;

    /**
     * @var TransferInterval
     */
    private $transferInterval;

    /**
     * @var int
     */
    private $transferDay;

    /**
     * @var int
     */
    private $recipientId;

    /**
     * @param string $recipientId
     * @return $this|TransferSettingsInterface
     */
    public function setRecipientId($recipientId)
    {
        $this->recipientId = $recipientId;
        return $this;
    }

    public function getRecipientId()
    {
        return $this->recipientId;
    }

    /**
     * @return bool
     */
    public function isTransferEnabled()
    {
        return $this->transferEnabled;
    }

    /**
     * @param bool $transferEnabled
     * @return TransferSettings
     */
    public function setTransferEnabled($transferEnabled)
    {
        $this->transferEnabled = $transferEnabled;
        return $this;
    }

    /**
     * @return TransferInterval
     */
    public function getTransferInterval()
    {
        return $this->transferInterval;
    }

    /**
     * @param TransferInterval $transferInterval
     * @return TransferSettings
     */
    public function setTransferInterval(TransferInterval $transferInterval)
    {
        $this->transferInterval = $transferInterval;
        return $this;
    }

    /**
     * @return int
     */
    public function getTransferDay()
    {
        return $this->transferDay;
    }

    /**
     * @param int $transferDay
     * @return TransferSettings
     * @throws InvalidTransferConfigurationException
     */
    public function setTransferDay($transferDay)
    {
        $days = range(1, 31);

        if ($this->getTransferInterval() === null) {
            throw new InvalidTransferConfigurationException(
                "First need set transferInterval",
                null,
                null
            );
        }

        if (
            $this->getTransferInterval()->equals(TransferInterval::monthly()) &&
            !in_array($transferDay, $days)
        ) {
            throw new InvalidTransferConfigurationException(
                null,
                TransferInterval::monthly(),
                $transferDay
            );
        }

        $days = range(1, 5);

        if (
            $this->getTransferInterval()->equals(TransferInterval::weekly()) &&
            !in_array($transferDay, $days)
        ) {
            throw new InvalidTransferConfigurationException(
                null,
                TransferInterval::weekly(),
                $transferDay
            );
        }

        $this->transferDay = $transferDay;
        return $this;
    }

    /**
     * @return UpdateTransferSettingsRequest
     */
    public function convertToSdkRequestUpdate()
    {
        $updateTransferSettings = new UpdateTransferSettingsRequest();

        $updateTransferSettings->transferEnabled = $this->isTransferEnabled();
        $updateTransferSettings->transferInterval = $this->getTransferInterval()->getValue();
        $updateTransferSettings->transferDay = $this->getTransferDay();

        return $updateTransferSettings;
    }

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}
