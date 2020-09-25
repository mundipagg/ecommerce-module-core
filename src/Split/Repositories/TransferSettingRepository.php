<?php

namespace Mundipagg\Core\Split\Repositories;

use Exception;
use Mundipagg\Core\Kernel\Abstractions\AbstractDatabaseDecorator;
use Mundipagg\Core\Kernel\Abstractions\AbstractEntity;
use Mundipagg\Core\Kernel\Abstractions\AbstractRepository;
use Mundipagg\Core\Kernel\ValueObjects\AbstractValidString;
use Mundipagg\Core\Split\Aggregates\TransferSettings;
use Mundipagg\Core\Split\Factories\TransferSettingFactory;

class TransferSettingRepository extends AbstractRepository
{
    /**
     * @var string
     */
    private $table;

    /**
     * TransferSettingRepository constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->db->getTable(
            AbstractDatabaseDecorator::TABLE_SPLIT_TRANSFER_SETTING
        );
    }

    /**
     * @param AbstractEntity|TransferSettings $object
     * @throws Exception
     */
    protected function create(AbstractEntity &$object)
    {
        $query = "
          INSERT INTO 
            {$this->table} 
            (
                recipient_id,
                transfer_enabled,
                transfer_interval,
                transfer_day              
            )
          VALUES
        ";

        $query .= "
            (
                '{$object->getRecipientId()}',
                '{$object->isTransferEnabled()}',
                '{$object->getTransferInterval()->getValue()}',
                '{$object->getTransferDay()}'                
            );
        ";

        $this->db->query($query);
    }

    /**
     * @param TransferSettings|AbstractEntity $object
     * @throws Exception
     */
    protected function update(AbstractEntity &$object)
    {
        $query = "
            UPDATE {$this->table} SET                  
                  transfer_enabled = '{$object->isTransferEnabled()}',
                  transfer_interval = '{$object->getTransferInterval()->getValue()}',
                  transfer_day = '{$object->getTransferDay()}'                    
             WHERE id = {$object->getId()}
        ";

        $this->db->query($query);
    }

    /**
     * @param int $recipientId
     * @return AbstractEntity|TransferSettings|null
     * @throws Exception
     */
    public function findByRecipientId($recipientId)
    {
        $query = "SELECT * FROM {$this->table} WHERE recipient_id = {$recipientId}";
        $result = $this->db->fetch($query);

        if ($result->num_rows === 0) {
            return null;
        }

        $bankAccountFactory = new TransferSettingFactory();
        return $bankAccountFactory->createFromDbData($result->row);
    }

    public function delete(AbstractEntity $object)
    {
        // TODO: Implement delete() method.
    }

    public function find($objectId)
    {
        // TODO: Implement find() method.
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