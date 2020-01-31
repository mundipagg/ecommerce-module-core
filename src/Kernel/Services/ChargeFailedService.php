<?php

namespace Mundipagg\Core\Kernel\Services;

use Mundipagg\Core\Kernel\Aggregates\ChargeFailed;
use Mundipagg\Core\Kernel\Repositories\ChargeFailedRepository;
use Mundipagg\Core\Kernel\ValueObjects\Id\OrderId;

class ChargeFailedService
{
    /** @var LogService  */
    protected $logService;

    /**
     * @var ChargeFailedRepository
     */
    protected $chargeFailedRepository;

    public function __construct()
    {
        $this->logService = new LogService(
            'ChargeFailedService',
            true
        );

        $this->chargeFailedRepository = new ChargeFailedRepository();
    }

    /**
     * @param ChargeFailed $chargeFailed
     * @return bool
     */
    public function persistChargeFailed(ChargeFailed $chargeFailed)
    {
        try {
            $this->chargeFailedRepository->save($chargeFailed);
        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param OrderId $orderId
     * @return \Mundipagg\Core\Kernel\Abstractions\AbstractEntity|ChargeFailed|null
     * @throws \Exception
     */
    public function findByOrderId(OrderId $orderId)
    {
        try {
            return $this->chargeFailedRepository->findByOrderId($orderId);
        } catch (\Exception $exception) {
            throw new \Exception($exception, $exception->getCode());
        }
    }

    /**
     * @param string $code
     * @return \Mundipagg\Core\Kernel\Abstractions\AbstractEntity|ChargeFailed|null
     * @throws \Exception
     */
    public function findByCode($code)
    {
        try {
            return $this->chargeFailedRepository->findByCode($code);
        } catch (\Exception $exception) {
            throw new \Exception($exception, $exception->getCode());
        }
    }


    /**
     * @param \Mundipagg\Core\Kernel\Aggregates\ChargeFailed[] $listChargeFailed
     * @return \Mundipagg\Core\Kernel\Aggregates\ChargeFailed[]|array
     */
    public function checkHasChargesPaidBetweenFailed(array $listChargeFailed)
    {
        $existStatusFailed = null;
        $listChargesPaid = [];

        $existStatusFailed = array_filter(
            $listChargeFailed,
            function (ChargeFailed $chargeFailed) {
                return $chargeFailed->getStatus()->getStatus() == 'failed';
            }
        );

        if ($existStatusFailed != null) {
            $listChargesPaid = array_filter(
                $listChargeFailed,
                function (ChargeFailed $chargeFailed) {
                    return (
                        $chargeFailed->getStatus()->getStatus() == 'paid' ||
                        $chargeFailed->getStatus()->getStatus() == 'underpaid'
                    );
                });
        }

        return $listChargesPaid;
    }
}
