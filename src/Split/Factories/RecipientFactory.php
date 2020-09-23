<?php

namespace Mundipagg\Core\Split\Factories;

use Exception;
use Mundipagg\Core\Kernel\Abstractions\AbstractEntity;
use Mundipagg\Core\Kernel\Exceptions\InvalidParamException;
use Mundipagg\Core\Kernel\Helper\Hydrator;
use Mundipagg\Core\Kernel\Interfaces\FactoryCreateFromDbDataInterface;
use Mundipagg\Core\Split\Aggregates\Recipient;
use Mundipagg\Core\Split\ValueObjects\Id\RecipientId;
use Mundipagg\Core\Split\ValueObjects\StatusRecipient;
use ReflectionException;

class RecipientFactory implements FactoryCreateFromDbDataInterface
{
    /**
     * @param array $dbData
     * @return AbstractEntity|Recipient
     * @throws ReflectionException
     * @throws InvalidParamException
     * @throws Exception
     */
    public function createFromDbData($dbData)
    {
        /**
         * @var Recipient $recipient
         */
        $recipient = Hydrator::hidrator($dbData, new Recipient());

        $recipient->setStatus(new StatusRecipient($dbData['status']));
        $recipient->setMundipaggId(new RecipientId($dbData['mundipagg_id']));
        $recipient->setBankAccount($dbData['bank_account']);
        $recipient->setDocument($dbData['document']);
        $recipient->setTransferSettings($dbData['transfer_settings']);

        return $recipient;
    }
}
