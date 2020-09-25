<?php

namespace Mundipagg\Core\Split\ValueObjects;

use Mundipagg\Core\Kernel\Abstractions\AbstractValueObject;

class StatusRecipient extends AbstractValueObject
{
    const ACTIVE = 'active';
    const CANCELED = 'canceled';

    /** @var string */
    private $status;

    /**
     * StatusRecipient constructor.
     * @param string $status
     */
    public function __construct($status)
    {
        $this->status = $status;
    }

    /**
     * @return StatusRecipient
     */
    public static function active()
    {
        return new self(self::ACTIVE);
    }

    /**
     * @return StatusRecipient
     */
    public static function canceled()
    {
        return new self(self::CANCELED);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->status;
    }

    /**
     * To check the structural equality of value objects,
     * this method should be implemented in this class children.
     *
     * @param  $object
     * @return bool
     */
    protected function isEqual($object)
    {
        return $this->status === $object->getType();
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
        return $this->status;
    }
}
