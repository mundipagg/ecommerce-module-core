<?php

namespace Mundipagg\Core\Split\ValueObjects;

use Mundipagg\Core\Kernel\Abstractions\AbstractValueObject;

class TypeBankAccount extends AbstractValueObject
{
    const CHECKING = 'checking';
    const SAVINGS = 'savings';
    const CONJUNCT_SAVINGS = 'conjunct_savings';
    const CONJUNCT_CHECKING = 'conjunct_checking';

    /** @var stringy */
    private $typeBank;

    public function __construct($typeBank)
    {
        $this->typeBank = $typeBank;
    }

    public static function checking()
    {
        return new self(self::CHECKING);
    }

    public static function savings()
    {
        return new self(self::SAVINGS);
    }

    public static function conjunctSavings()
    {
        return new self(self::CONJUNCT_SAVINGS);
    }

    public static function conjunctChecking()
    {
        return new self(self::CONJUNCT_CHECKING);
    }

    /**
     * @return stringt
     */
    public function getValue()
    {
        return $this->typeBank;
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
        return $this->typeBank === $object->getType();
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
        return $this->typeBank;
    }
}
