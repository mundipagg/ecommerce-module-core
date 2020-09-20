<?php

namespace Mundipagg\Core\Split\Factories;

use Mundipagg\Core\Kernel\Abstractions\AbstractEntity;
use Mundipagg\Core\Kernel\Helper\Hydrator;
use Mundipagg\Core\Kernel\Interfaces\FactoryInterface;
use Mundipagg\Core\Split\Aggregates\BankAccount;
use Mundipagg\Core\Split\ValueObjects\Id\RecipientId;
use ReflectionException;

class BankAccountFactory implements FactoryInterface
{
    public function createFromPostData($postData)
    {
        // TODO: Implement createFromPostData() method.
    }

    /**
     * @param array $dbData
     * @return AbstractEntity|BankAccount
     * @throws ReflectionException
     */
    public function createFromDbData($dbData)
    {
        /**
         * @var BankAccount $bankAccount
         */
        return Hydrator::hidrator($dbData, new BankAccount());
    }
}
