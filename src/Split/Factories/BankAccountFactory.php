<?php

namespace Mundipagg\Core\Split\Factories;

use Mundipagg\Core\Kernel\Abstractions\AbstractEntity;
use Mundipagg\Core\Kernel\Helper\Hydrator;
use Mundipagg\Core\Kernel\Interfaces\FactoryCreateFromDbDataInterface;
use Mundipagg\Core\Kernel\ValueObjects\Type;
use Mundipagg\Core\Split\Aggregates\BankAccount;
use Mundipagg\Core\Split\ValueObjects\TypeBankAccount;
use ReflectionException;

class BankAccountFactory implements FactoryCreateFromDbDataInterface
{
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
        $bankAccount = Hydrator::hidrator($dbData, new BankAccount());
        $bankAccount->setType(new TypeBankAccount($dbData['type']));
        $bankAccount->setHolderType(new Type($dbData['holder_type']));

        return $bankAccount;
    }
}
