<?php

namespace Mundipagg\Core\Kernel\Services;

use MundiAPILib\Models\GetChargeResponse;
use Mundipagg\Core\Kernel\Aggregates\Charge;
use Mundipagg\Core\Kernel\Repositories\ChargeRepository;
use Mundipagg\Core\Kernel\Repositories\OrderRepository;
use Mundipagg\Core\Kernel\Responses\ServiceResponse;
use Mundipagg\Core\Kernel\ValueObjects\ChargeStatus;
use Mundipagg\Core\Kernel\ValueObjects\Id\ChargeId;
use Mundipagg\Core\Kernel\ValueObjects\Id\OrderId;
use Mundipagg\Core\Kernel\ValueObjects\OrderState;
use Mundipagg\Core\Kernel\ValueObjects\OrderStatus;
use Mundipagg\Core\Payment\Services\ResponseHandlers\OrderHandler;
use Mundipagg\Core\Webhook\Services\ChargeHandlerService;
use Unirest\Exception;

class ChargeFailedService
{
    /** @var LogService  */
    protected $logService;

    public function __construct()
    {
        $this->logService = new LogService(
            'ChargeFailedService',
            true
        );
    }

    public function persistChargeFailed($response) {

    }
}
