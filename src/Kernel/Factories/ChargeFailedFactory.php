<?php

namespace Mundipagg\Core\Kernel\Factories;

use Mundipagg\Core\Kernel\Abstractions\AbstractEntity;
use Mundipagg\Core\Kernel\Aggregates\ChargeFailed;
use Mundipagg\Core\Kernel\Exceptions\InvalidParamException;
use Mundipagg\Core\Kernel\Interfaces\FactoryCreateFromDbDataInterface;
use Mundipagg\Core\Kernel\ValueObjects\ChargeStatus;
use Mundipagg\Core\Kernel\ValueObjects\Id\ChargeId;
use Mundipagg\Core\Kernel\ValueObjects\Id\OrderId;

/**
 * Class ChargeFailedFactory
 * @package Mundipagg\Core\Kernel\Factories
 */
class ChargeFailedFactory implements FactoryCreateFromDbDataInterface
{
    /**
     * @param $postData
     * @param OrderId $orderId
     * @return ChargeFailed
     * @throws InvalidParamException
     */
    public function createFromPostWithOrderIdData($postData, OrderId $orderId)
    {
        $chargeFailed = new ChargeFailed();
        $chargeFailed->setMundipaggId(new ChargeId($postData['id']));

        $this->checkStatusChargeFailed($postData['status']);

        $chargeFailed->setStatus(ChargeStatus::{$postData['status']}());
        $chargeFailed->setCode($postData['code']);
        $chargeFailed->setAmount($postData['amount']);
        $chargeFailed->setOrderId($orderId);

        return $chargeFailed;
    }

    /**
     * @param array $dbData
     * @return AbstractEntity|ChargeFailed
     * @throws InvalidParamException
     */
    public function createFromDbData($dbData)
    {
        $chargeFailed = new ChargeFailed();
        $chargeFailed->setId($dbData['id']);
        $chargeFailed->setMundipaggId(new ChargeId($dbData['mundipagg_id']));

        $this->checkStatusChargeFailed($dbData['status']);

        $chargeFailed->setStatus(ChargeStatus::{$dbData['status']}());
        $chargeFailed->setCode($dbData['code']);
        $chargeFailed->setAmount($dbData['amount']);
        $chargeFailed->setOrderId(new OrderId($dbData['order_id']));

        return $chargeFailed;
    }

    /**
     * @param string $status
     * @throws InvalidParamException
     */
    private function checkStatusChargeFailed($status)
    {
        try {
            ChargeStatus::{$status}();
        } catch (\Exception $e) {
            throw new InvalidParamException(
                "Invalid charge status!",
                $status
            );
        } catch (\Throwable $e) {
            throw new InvalidParamException(
                "Invalid charge status!",
                $status
            );
        }
    }
}
