<?php

namespace Mundipagg\Core\Split\ValueObjects\Id;

use Mundipagg\Core\Kernel\ValueObjects\AbstractValidString;

class RecipientId extends AbstractValidString
{
    protected function validateValue($value)
    {
        return preg_match('/^rp_\w{16}$/', $value) === 1;
    }
}