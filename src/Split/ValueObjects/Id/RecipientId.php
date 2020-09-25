<?php

namespace Mundipagg\Core\Split\ValueObjects\Id;

use Mundipagg\Core\Kernel\Exceptions\InvalidParamException;
use Mundipagg\Core\Kernel\ValueObjects\AbstractValidString;

class RecipientId extends AbstractValidString
{
    /**
     * RecipientId constructor.
     * @param string $mundipaggId
     * @throws InvalidParamException
     */
    public function __construct($mundipaggId)
    {
        parent::__construct($mundipaggId);
    }

    protected function validateValue($value)
    {
        return preg_match('/^rp_\w{16}$/', $value) === 1;
    }
}
