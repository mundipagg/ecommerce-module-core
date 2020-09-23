<?php

namespace Mundipagg\Core\Split\Repositories;

use Exception;
use Mundipagg\Core\Kernel\Abstractions\AbstractDatabaseDecorator;
use Mundipagg\Core\Kernel\Abstractions\AbstractEntity;
use Mundipagg\Core\Kernel\Abstractions\AbstractRepository;
use Mundipagg\Core\Kernel\Exceptions\InvalidParamException;
use Mundipagg\Core\Kernel\ValueObjects\AbstractValidString;
use Mundipagg\Core\Split\Aggregates\Recipient;
use Mundipagg\Core\Split\Aggregates\TransferSettings;
use Mundipagg\Core\Split\Factories\RecipientFactory;
use Mundipagg\Core\Split\Interfaces\BankAccountInterface;
use Mundipagg\Core\Split\Interfaces\RecipientInterface;
use ReflectionException;

class RecipientRepository extends AbstractRepository
{
    /**
     * @var string
     */
    private $table;

    /**
     * RecipientRepository constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->db->getTable(
            AbstractDatabaseDecorator::TABLE_SPLIT_RECIPIENT
        );
    }

    /**
     * @param RecipientInterface|AbstractEntity $object
     * @throws Exception
     */
    protected function create(AbstractEntity &$object)
    {
        $query = "
          INSERT INTO 
            {$this->table} 
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

        $recipientId = $this->db->getLastId();
        $object->setId($recipientId);

        /**
         * @var $bankAccount AbstractEntity|BankAccountInterface
         */
        $bankAccount = $object->getBankAccount();
        $bankAccount->setRecipientId($recipientId);

        $recipientBankAccountRepository = new RecipientBankAccountRepository();
        $recipientBankAccountRepository->save($bankAccount);

        /**
         * @var $transferSettings AbstractEntity|TransferSettings
         */
        $transferSettings = $object->getTransferSettings();
        $transferSettings->setRecipientId($recipientId);
        $transferSettingBankAccountRepository = new TransferSettingRepository();
        $transferSettingBankAccountRepository->save($transferSettings);
    }

    /**
     * @param RecipientInterface|AbstractEntity $object
     * @throws Exception
     */
    protected function update(AbstractEntity &$object)
    {
        $metaData = json_encode($object->getMetadata());

        $query = "
            UPDATE {$this->table} SET
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

        if ($object->getTransferSettings() !== null) {

            /**
             * @var $transferSettings AbstractEntity|TransferSettings
             */
            $transferSettings = $object->getTransferSettings();
            $transferSettingBankAccountRepository = new TransferSettingRepository();
            $transferSettingBankAccountRepository->save($transferSettings);
        }
    }

    public function delete(AbstractEntity $object)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param $id
     * @return AbstractEntity|Recipient|null
     * @throws ReflectionException
     * @throws InvalidParamException
     * @throws Exception
     */
    public function find($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = {$id}";
        $result = $this->db->fetch($query);

        if ($result->num_rows === 0) {
            return null;
        }

        $recipientBankAccountRepository = new RecipientBankAccountRepository();
        $bankAccount = $recipientBankAccountRepository->findByRecipientId($id);
        $result->row['bank_account'] = $bankAccount;

        $transferSettingBankAccountRepository = new TransferSettingRepository();
        $transferSettings = $transferSettingBankAccountRepository->findByRecipientId($id);
        $result->row['transfer_settings'] = $transferSettings;

        $factory = new RecipientFactory();
        return $factory->createFromDbData($result->row);
    }

    public function findByMundipaggId(AbstractValidString $mundipaggId)
    {
        // TODO: Implement findByMundipaggId() method.
    }

    /**
     * @return Recipient[]|null
     * @throws InvalidParamException
     * @throws ReflectionException
     * @throws Exception
     */
    public function getMarketplaceUser()
    {
        $query = "SELECT * FROM {$this->table} WHERE is_marketplace = TRUE";

        $result = $this->db->fetch($query);

        if ($result->num_rows === 0) {
            return null;
        }

        $recipientList = [];
        foreach ($result->rows as $row) {
            $recipientBankAccountRepository = new RecipientBankAccountRepository();
            $bankAccount = $recipientBankAccountRepository->findByRecipientId($row['id']);
            $result->row['bank_account'] = $bankAccount;

            $transferSettingBankAccountRepository = new TransferSettingRepository();
            $transferSettings = $transferSettingBankAccountRepository->findByRecipientId(
                $row['id']
            );
            $result->row['transfer_settings'] = $transferSettings;

            $factory = new RecipientFactory();
            $recipientList[] = $factory->createFromDbData($result->row);
        }

        return $recipientList;
    }

    public function listEntities($limit, $listDisabled)
    {
        // TODO: Implement listEntities() method.
    }
}
