<?php

namespace Mundipagg\Core\Split\Repositories;

use Exception;
use Mundipagg\Core\Kernel\Abstractions\AbstractDatabaseDecorator;
use Mundipagg\Core\Kernel\Abstractions\AbstractEntity;
use Mundipagg\Core\Kernel\Abstractions\AbstractRepository;
use Mundipagg\Core\Kernel\ValueObjects\AbstractValidString;
use Mundipagg\Core\Split\Aggregates\BankAccount;
use Mundipagg\Core\Split\Factories\BankAccountFactory;
use Mundipagg\Core\Split\Interfaces\BankAccountInterface;
use ReflectionException;

class RecipientBankAccountRepository extends AbstractRepository
{
    /**
     * @param BankAccountInterface|AbstractEntity $object
     * @throws Exception
     */
    protected function create(AbstractEntity &$object)
    {
        $recipientBankAccountTable = $this->db->getTable(
            AbstractDatabaseDecorator::TABLE_SPLIT_RECIPIENT_BANK_ACCOUNT
        );

        $query = "
          INSERT INTO 
            {$recipientBankAccountTable} 
            (
                recipient_id,
                holder_name,
                holder_type,
                holder_document,
                bank,
                branch_number,
                branch_check_digit,
                account_number,
                account_check_digit,
                type
            )
          VALUES
        ";

        $query .= "
            (
                '{$object->getRecipientId()}',
                '{$object->getHolderName()}',
                '{$object->getHolderType()->getValue()}',
                '{$object->getHolderDocument()}',
                '{$object->getBank()}',
                '{$object->getBranchNumber()}',
                '{$object->getBranchCheckDigit()}',
                '{$object->getAccountNumber()}',
                '{$object->getAccountCheckDigit()}',
                '{$object->getType()->getValue()}'
            );
        ";

        $this->db->query($query);
    }

    /**
     * @param BankAccount|AbstractEntity $object
     * @throws Exception
     */
    protected function update(AbstractEntity &$object)
    {
        $table = $this->db->getTable(
            AbstractDatabaseDecorator::TABLE_SPLIT_RECIPIENT_BANK_ACCOUNT
        );

        $metaData = json_encode($object->getMetadata());

        $query = "
            UPDATE {$table} SET
                  recipient_id = '{$object->getRecipientId()}',
                  holder_name = '{$object->getHolderName()}',
                  holder_type = '{$object->getHolderType()->getValue()}',
                  holder_document = '{$object->getHolderDocument()}',
                  bank = '{$object->getBank()}',
                  branch_number = '{$object->getBranchNumber()}',
                  branch_check_digit = '{$object->getBranchCheckDigit()}',
                  account_number = '{$object->getAccountNumber()}',
                  account_check_digit = '{$object->getAccountCheckDigit()}',
                  type = '{$object->getType()->getValue()}'                  
             WHERE id = {$object->getId()}
        ";

        $this->db->query($query);
    }

    public function delete(AbstractEntity $object)
    {
        // TODO: Implement delete() method.
    }

    public function find($objectId)
    {
        // TODO: Implement find() method.
    }

    /**
     * @param $recipientId
     * @return AbstractEntity|BankAccount|null
     * @throws ReflectionException
     */
    public function findByRecipientId($recipientId)
    {
        $table = $this->db->getTable(
            AbstractDatabaseDecorator::TABLE_SPLIT_RECIPIENT_BANK_ACCOUNT
        );

        $query = "SELECT * FROM {$table} WHERE id = {$recipientId}";
        $result = $this->db->fetch($query);

        if ($result->num_rows === 0) {
            return null;
        }

        $bankAccountFactory = new BankAccountFactory();
        return $bankAccountFactory->createFromDbData($result->row);
    }

    public function findByMundipaggId(AbstractValidString $mundipaggId)
    {
        // TODO: Implement findByMundipaggId() method.
    }

    public function listEntities($limit, $listDisabled)
    {
        // TODO: Implement listEntities() method.
    }
}
