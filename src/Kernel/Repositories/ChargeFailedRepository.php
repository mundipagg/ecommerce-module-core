<?php

namespace Mundipagg\Core\Kernel\Repositories;

use Mundipagg\Core\Kernel\Abstractions\AbstractDatabaseDecorator;
use Mundipagg\Core\Kernel\Abstractions\AbstractEntity;
use Mundipagg\Core\Kernel\Abstractions\AbstractRepository;
use Mundipagg\Core\Kernel\Factories\ChargeFailedFactory;
use Mundipagg\Core\Kernel\ValueObjects\AbstractValidString;
use Mundipagg\Core\Kernel\ValueObjects\Id\OrderId;

final class ChargeFailedRepository extends AbstractRepository
{
    /**
     * @param  \Mundipagg\Core\Kernel\Aggregates\ChargeFailed $object
     * @throws \Exception
     */
    protected function create(AbstractEntity &$object)
    {
        $chargeFailedTable = $this->db->getTable(
            AbstractDatabaseDecorator::TABLE_CHARGE_FAILED
        );

        $query = "
          INSERT INTO 
            {$chargeFailedTable} 
            (
                mundipagg_id, 
                order_id, 
                code, 
                amount, 
                status
            )
          VALUES 
        ";

        $query .= "
            (
                '{$object->getMundipaggId()->getValue()}',
                '{$object->getOrderId()->getValue()}',
                '{$object->getCode()}',
                {$object->getAmount()},
                '{$object->getStatus()->getStatus()}'           
            );
        ";

        $this->db->query($query);
    }

    protected function update(AbstractEntity &$object)
    {
        // TODO: Implement update() method.
    }

    public function delete(AbstractEntity $object)
    {
        // TODO: Implement delete() method.
    }

    public function find($objectId)
    {
        // TODO: Implement find() method.
    }

    public function listEntities($limit, $listDisabled)
    {
        // TODO: Implement listEntities() method.
    }

    public function findByMundipaggId(AbstractValidString $mundipaggId)
    {

    }

    /**
     * @param string $code
     * @return array|null
     * @throws \Mundipagg\Core\Kernel\Exceptions\InvalidParamException
     */
    public function findByCode($code)
    {
        $chargeFailedTable = $this->db->getTable(
            AbstractDatabaseDecorator::TABLE_CHARGE_FAILED
        );

        $query = "SELECT * FROM `{$chargeFailedTable}` ";
        $query .= "WHERE code = '{$code}';";

        $result = $this->db->fetch($query);

        if ($result->num_rows === 0) {
            return null;
        }

        $factory = new ChargeFailedFactory();
        $chargeListFailed = [];
        foreach ($result->rows as $chargeFailedDb) {
            $chargeListFailed[] = $factory->createFromDbData($chargeFailedDb);
        }
        return $chargeListFailed;
    }

    /**
     * @param OrderId $orderId
     * @return AbstractEntity|\Mundipagg\Core\Kernel\Aggregates\ChargeFailed|null
     * @throws \Mundipagg\Core\Kernel\Exceptions\InvalidParamException
     */
    public function findByOrderId(OrderId $orderId)
    {
        $chargeFailedTable = $this->db->getTable(
            AbstractDatabaseDecorator::TABLE_CHARGE_FAILED
        );

        $query = "SELECT * FROM `{$chargeFailedTable}` ";
        $query .= "WHERE order_id = '{$orderId->getValue()}';";

        $result = $this->db->fetch($query);

        if ($result->num_rows === 0) {
            return null;
        }

        $factory = new ChargeFailedFactory();
        $chargeListFailed = [];
        foreach ($result->rows as $chargeFailedDb) {
            $chargeListFailed[] = $factory->createFromDbData($chargeFailedDb);
        }
        return $chargeListFailed;
    }
}
