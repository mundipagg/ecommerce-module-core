<?php

namespace Mundipagg\Core\Split\Exceptions;

use Mundipagg\Core\Kernel\Exceptions\AbstractMundipaggCoreException;

class InvalidTransferConfigurationException extends AbstractMundipaggCoreException
{
    /**
     * InvalidTransferConfigurationException constructor.
     * @param string $message
     * @param string $interval
     * @param int $day
     */
    public function __construct($message, $interval, $day)
    {
        $message .= " Passed value: {$interval} && day {$day} no match";
        parent::__construct($message, 400, null);
    }
}
