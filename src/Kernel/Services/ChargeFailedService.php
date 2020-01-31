<?php

namespace Mundipagg\Core\Kernel\Services;

use Exception;
use Mundipagg\Core\Kernel\Aggregates\ChargeFailed;
use Mundipagg\Core\Kernel\Repositories\ChargeFailedRepository;

class ChargeFailedService
{
    /** @var LogService  */
    protected $logService;

    /**
     * @var ChargeFailedRepository
     */
    protected $chargeFailedRepo;

    public function __construct()
    {
        $this->logService = new LogService(
            'ChargeFailedService',
            true
        );

        $this->chargeFailedRepo = new ChargeFailedRepository();
    }

    /**
     * @param ChargeFailed $chargeFailed
     * @return bool
     */
    public function persistChargeFailed(ChargeFailed $chargeFailed)
    {
        try {
            $this->chargeFailedRepo->save($chargeFailed);
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param $code
     * @return array|null
     * @throws Exception
     */
    public function findByCode($code)
    {
        try {
            return $this->chargeFailedRepo->findByCode($code);
        } catch (Exception $exception) {
            throw new Exception($exception, $exception->getCode());
        }
    }
}
