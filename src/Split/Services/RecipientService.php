<?php

namespace Mundipagg\Core\Split\Services;

use MundiAPILib\APIException;
use MundiAPILib\Models\GetRecipientResponse;
use MundiAPILib\MundiAPIClient;
use Mundipagg\Core\Kernel\Abstractions\AbstractEntity;
use Mundipagg\Core\Kernel\Abstractions\AbstractValueObject;
use Mundipagg\Core\Kernel\Exceptions\InvalidParamException;
use Mundipagg\Core\Kernel\Services\LogService;
use Mundipagg\Core\Kernel\ValueObjects\AbstractValidString;
use Mundipagg\Core\Split\Interfaces\BankAccountInterface;
use Mundipagg\Core\Split\ValueObjects\StatusRecipient;
use Mundipagg\Core\Split\Interfaces\RecipientInterface;
use Mundipagg\Core\Split\Interfaces\RecipientServiceInterface;
use Mundipagg\Core\Split\Repositories\RecipientRepository;
use Mundipagg\Core\Split\ValueObjects\Id\RecipientId;

class RecipientService implements RecipientServiceInterface
{
    /**
     * @var LogService
     */
    private $logService;

    /**
     * @var RecipientRepository
     */
    private $recipientRepository;

    /**
     * @var MundiAPIClient
     */
    private $mundiAPIClient;

    public function __construct(
        LogService $logService,
        RecipientRepository $recipientRepository,
        MundiAPIClient $mundiAPIClient
    ) {
        $this->logService = $logService;
        $this->recipientRepository = $recipientRepository;
        $this->mundiAPIClient = $mundiAPIClient;
    }

    /**
     * @param RecipientInterface|AbstractEntity $recipient
     * @return AbstractEntity|RecipientInterface
     * @throws APIException
     * @throws InvalidParamException
     */
    public function save(RecipientInterface $recipient)
    {
        if ($recipient->getId() === null) {
            $result = $this->createRecipientAtMundipagg($recipient);
            $recipient->setMundipaggId(new RecipientId($result->id));
            $recipient->setStatus(new StatusRecipient($result->status));
        }

        if ($recipient->getId() !== null) {
            $this->updateRecipientAtMundipagg($recipient);
            $this->updateRecipientBankAccountAtMundipagg($recipient->getBankAccount(), $recipient->getMundipaggId());
            //update contabancaria aqui
        }

        $this->recipientRepository->save($recipient);

        return $this->recipientRepository->find($recipient->getId());
    }

    /**
     * @param RecipientInterface $recipient
     * @return GetRecipientResponse
     * @throws APIException
     */
    public function createRecipientAtMundipagg(RecipientInterface $recipient)
    {
        $recipientRequest = $recipient->convertToSdkRequest();
        return $this->mundiAPIClient->getRecipients()->createRecipient($recipientRequest);
    }

    /**
     * @param RecipientInterface $recipient
     * @return GetRecipientResponse
     * @throws APIException
     */
    public function updateRecipientAtMundipagg(RecipientInterface $recipient)
    {
        $recipientRequest = $recipient->convertToSdkRequestUpdate();
        return $this->mundiAPIClient->getRecipients()->updateRecipient(
            $recipient->getMundipaggId()->getValue(),
            $recipientRequest
        );
    }

    /**
     * @param BankAccountInterface $recipientBankAccount
     * @param AbstractValueObject|RecipientId $mundipaggRecipientId
     * @return mixed
     * @throws APIException
     */
    public function updateRecipientBankAccountAtMundipagg(
        BankAccountInterface $recipientBankAccount,
        RecipientId $mundipaggRecipientId
    ) {
        $recipientBankAccountRequest = $recipientBankAccount->convertToSdkRequest();
        return $this->mundiAPIClient->getRecipients()->updateRecipientDefaultBankAccount(
            $mundipaggRecipientId->getValue(),
            $recipientBankAccountRequest
        );
    }
}
