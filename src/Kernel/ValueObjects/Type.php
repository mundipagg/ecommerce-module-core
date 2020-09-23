<?php

namespace Mundipagg\Core\Kernel\ValueObjects;

use Mundipagg\Core\Kernel\Abstractions\AbstractValueObject;

class Type extends AbstractValueObject
{
    const INDIVIDUAL = 'individual';
    const COMPANY = 'company';

    /** @var string */
    private $type;

    /**
     * Type constructor.
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @return Type
     */
    static public function individual()
    {
        return new self(self::INDIVIDUAL);
    }

    /**
     * @return Type
     */
    static public function company()
    {
        return new self(self::COMPANY);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->type;
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
        return $this->type === $object->getType();
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
        return $this->type;
    }
}