<?php

namespace Mundipagg\Core\Split\Repositories;

use Exception;
use Mundipagg\Core\Kernel\Abstractions\AbstractDatabaseDecorator;
use Mundipagg\Core\Kernel\Abstractions\AbstractEntity;
use Mundipagg\Core\Kernel\Abstractions\AbstractRepository;
use Mundipagg\Core\Kernel\ValueObjects\AbstractValidString;
use Mundipagg\Core\Split\Factories\RecipientFactory;
use Mundipagg\Core\Split\Interfaces\BankAccountInterface;
use Mundipagg\Core\Split\Interfaces\RecipientInterface;

class RecipientRepository extends AbstractRepository
{
    /**
     * @param RecipientInterface|AbstractEntity $object
     * @throws Exception
     */
    protected function create(AbstractEntity &$object)
    {
        $recipientTable = $this->db->getTable(
            AbstractDatabaseDecorator::TABLE_SPLIT_RECIPIENT
        );

        $query = "
          INSERT INTO 
            {$recipientTable} 
            (
                name,
                email,
                description,
                document,
                external_recipient_id,
                mundipagg_id,     
                is_marketplace,                                                             
                status,
                meta_data
            )
          VALUES
        ";

        $metaData = json_encode($object->getMetadata());

        $query .= "
            (
                '{$object->getName()}',
                '{$object->getEmail()}',
                '{$object->getDescription()}',
                '{$object->getDocument()}',
                '{$object->getExternalRecipientId()}',
                '{$object->getMundipaggId()->getValue()}',                
                '{$object->isMarketPlace()}',
                '{$object->getStatus()->getValue()}',
                '{$metaData}'
            );
        ";

        $this->db->query($query);

        /**
         * @var $bankAccount AbstractEntity|BankAccountInterface
         */
        $bankAccount = $object->getBankAccount();
        $bankAccount->setRecipientId($this->db->getLastId());

        $recipientBankAccountRepository = new RecipientBankAccountRepository();
        $recipientBankAccountRepository->save($bankAccount);
    }

    /**
     * @param RecipientInterface|AbstractEntity $object
     * @throws Exception
     */
    protected function update(AbstractEntity &$object)
    {
        $table = $this->db->getTable(
            AbstractDatabaseDecorator::TABLE_SPLIT_RECIPIENT
        );

        $metaData = json_encode($object->getMetadata());

        $query = "
            UPDATE {$table} SET
                name = '{$object->getName()}',
                email = '{$object->getEmail()}',
                description = '{$object->getDescription()}',
                document = '{$object->getDocument()}',
                external_recipient_id = '{$object->getExternalRecipientId()}',
                mundipagg_id = '{$object->getMundipaggId()->getValue()}',     
                is_marketplace = '{$object->isMarketPlace()}',                                                             
                status = '{$object->getStatus()->getValue()}',
                meta_data = '{$metaData}'              
            WHERE id = {$object->getId()}
        ";

        $this->db->query($query);

        if ($object->getBankAccount() !== null) {
            /**
             * @var $bankAccount AbstractEntity|BankAccountInterface
             */
            $bankAccount = $object->getBankAccount();
            $bankAccount->setRecipientId($object->getId());

            $recipientBankAccountRepository = new RecipientBankAccountRepository();
            $recipientBankAccountRepository->save($bankAccount);
        }
    }

    public function delete(AbstractEntity $object)
    {
        // TODO: Implement delete() method.
    }

    public function find($id)
    {
        $table = $this->db->getTable(
            AbstractDatabaseDecorator::TABLE_SPLIT_RECIPIENT
        );

        $query = "SELECT * FROM {$table} WHERE id = {$id}";
        $result = $this->db->fetch($query);

        if ($result->num_rows === 0) {
            return null;
        }

        $recipientBankAccountRepository = new RecipientBankAccountRepository();
        $bankAccount = $recipientBankAccountRepository->findByRecipientId($id);
        $result->row['bank_account'] = $bankAccount;

        $factory = new RecipientFactory();
        return $factory->createFromDbData($result->row);
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
