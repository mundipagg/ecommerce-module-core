<?php

namespace Mundipagg\Core\Webhook\Services;

use Mundipagg\Core\Kernel\Exceptions\InvalidParamException;
use Mundipagg\Core\Kernel\Interfaces\ChargeInterface;
use Mundipagg\Core\Kernel\Services\APIService;
use Mundipagg\Core\Kernel\Services\ChargeService;
use Mundipagg\Core\Kernel\Services\MoneyService;
use Mundipagg\Core\Kernel\ValueObjects\ChargeStatus;
use Mundipagg\Core\Webhook\Aggregates\Webhook;
use Mundipagg\Core\Kernel\Aggregates\Charge;

class ExceptionOrderHandlerService
{
    public function handlePaymentFailed(Webhook $webhook)
    {
        /** @var Charge $charge  */
        $charge = $webhook->getEntity();

        $chargeService = new ChargeService();
        $apiService = new APIService();

        $order = $apiService->getOrder($charge->getOrderId());

        $chargeList = $chargeService->checkHasChargesPaidBetweenFailed(
            $order->getCharges()
        );

        $listResponse = [];
        foreach ($chargeList as $charge) {
            $listResponse = $chargeService->cancelJustInMundiPagg($charge);
        }
        
        return $listResponse;
    }
}
