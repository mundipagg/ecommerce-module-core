<?php

namespace Mundipagg\Core\Split\Factories;

use Mundipagg\Core\Kernel\Abstractions\AbstractEntity;
use Mundipagg\Core\Kernel\Helper\Hydrator;
use Mundipagg\Core\Kernel\Interfaces\FactoryCreateFromDbDataInterface;
use Mundipagg\Core\Split\Aggregates\TransferSettings;
use Mundipagg\Core\Split\ValueObjects\TransferInterval;
use ReflectionException;

class TransferSettingFactory implements FactoryCreateFromDbDataInterface
{
    /**
     * @param array $dbData
     * @return TransferSettings|AbstractEntity
     * @throws ReflectionException
     */
    public function createFromDbData($dbData)
    {
        /**
         * @var $transferSettings TransferSettings
         */
        $transferSettings = Hydrator::hidrator($dbData, new TransferSettings());
        $transferSettings->setTransferInterval(
            new TransferInterval($dbData['transfer_interval'])
        );
        $transferSettings->setTransferEnabled((bool)$dbData['transfer_enabled']);

        return $transferSettings;
    }
}
