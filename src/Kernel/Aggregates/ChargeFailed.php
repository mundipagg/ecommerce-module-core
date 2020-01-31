<?php

namespace Mundipagg\Core\Kernel\Aggregates;

use Mundipagg\Core\Kernel\Abstractions\AbstractEntity;
use Mundipagg\Core\Kernel\Exceptions\InvalidParamException;
use Mundipagg\Core\Kernel\ValueObjects\ChargeStatus;
use Mundipagg\Core\Kernel\ValueObjects\Id\OrderId;

final class ChargeFailed extends AbstractEntity
{
    /**
     * @var OrderId 
     */
    private $orderId;

    /**
     * @var int 
     */
    private $amount;

    /**
     * @var string 
     */
    private $code;

    /**
     * @var ChargeStatus 
     */
    private $status;

    /**
     * @return OrderId
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param  OrderId $orderId
     * @return Charge
     */
    public function setOrderId(OrderId $orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return Charge
     * @throws InvalidParamException
     */
    public function setAmount($amount)
    {
        if (!is_numeric($amount)) {
            throw new InvalidParamException(
                "Amount should be an integer!",
                $amount
            );
        }

        if ($amount < 0) {
            throw new InvalidParamException(
                "Amount should be greater or equal to 0!",
                $amount
            );
        }

        $this->amount = $amount;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param  string $code
     * @return Charge
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     *
     * @return ChargeStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *
     * @param  ChargeStatus $status
     * @return Charge
     */
    public function setStatus(ChargeStatus $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed|\stdClass
     */
    public function jsonSerialize()
    {
        $obj = new \stdClass();

        $obj->id = $this->getId();
        $obj->mundipaggId = $this->getMundipaggId();
        $obj->orderId = $this->getOrderId();
        $obj->amount = $this->getAmount();
        $obj->code = $this->getCode();
        $obj->status = $this->getStatus();

        return $obj;
    }
}
