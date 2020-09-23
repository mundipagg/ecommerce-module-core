<?php

namespace Mundipagg\Core\Split\ValueObjects;

use Mundipagg\Core\Kernel\Abstractions\AbstractValueObject;

class TransferInterval extends AbstractValueObject
{
    const DAILY = 'daily';
    const WEEKLY = 'weekly';
    const MONTHLY = 'monthly';

    /** @var string */
    private $interval;

    /**
     * TransferInterval constructor.
     * @param string $interval
     */
    public function __construct($interval)
    {
        $this->interval = $interval;
    }

    /**
     * @return $this
     */
    public static function daily()
    {
        return new self(self::DAILY);
    }

    /**
     * @return $this
     */
    public static function weekly()
    {
        return new self(self::WEEKLY);
    }

    /**
     * @return $this
     */
    public static function monthly()
    {
        return new self(self::MONTHLY);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->interval;
    }

    /**
     * To check the structural equality of value objects,
     * this method should be implemented in this class children.
     *
     * @param  TransferInterval|$object
     * @return bool
     */
    protected function isEqual($object)
    {
        return $this->interval === $object->getValue();
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->interval;
    }
}
