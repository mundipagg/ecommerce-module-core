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
use Mundipagg\Core\Split\Interfaces\TransferSettingsInterface;
use Mundipagg\Core\Split\ValueObjects\StatusRecipient;
use Mundipagg\Core\Split\Interfaces\RecipientInterface;
use Mundipagg\Core\Split\Interfaces\RecipientServiceInterface;
use Mundipagg\Core\Split\Repositories\RecipientRepository;
use Mundipagg\Core\Split\ValueObjects\Id\RecipientId;
use ReflectionException;

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
     * @throws ReflectionException
     */
    public function save(RecipientInterface $recipient)
    {
        if ($recipient->getId() === null) {
            $this->create($recipient);
        }

        if ($recipient->getId() !== null) {
            $this->update($recipient);
        }

        return $this->recipientRepository->find($recipient->getId());
    }

    /**
     * @param RecipientInterface|AbstractEntity $recipient
     * @throws APIException
     * @throws InvalidParamException
     */
    public function create(RecipientInterface $recipient)
    {
        $result = $this->createRecipientAtMundipagg($recipient);
        $recipient->setMundipaggId(new RecipientId($result->id));
        $recipient->setStatus(new StatusRecipient($result->status));

        $this->recipientRepository->save($recipient);
    }

    /**
     * @param RecipientInterface|AbstractEntity $recipient
     * @throws APIException
     * @throws InvalidParamException
     * @throws ReflectionException
     */
    public function update(RecipientInterface $recipient)
    {
        $recipientPrevious = $this->recipientRepository->find($recipient->getId());

        $this->updateRecipientAtMundipagg($recipient);

        if (!$recipientPrevious->getBankAccount()->equals($recipient->getBankAccount())) {
            $this->updateRecipientBankAccountAtMundipagg(
                $recipient->getBankAccount(),
                $recipient->getMundipaggId()
            );
        }

//        $this->updateRecipientTransferSettingsAtMundipagg(
//            $recipient->getTransferSettings(),
//            $recipient->getMundipaggId()
//        );

        $this->recipientRepository->save($recipient);
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
        $bankAccountRequest = $recipientBankAccount->convertToSdkRequest();
        return $this->mundiAPIClient->getRecipients()->updateRecipientDefaultBankAccount(
            $mundipaggRecipientId->getValue(),
            $bankAccountRequest
        );
    }

    /**
     * @param TransferSettingsInterface $transferSettings
     * @param RecipientId|AbstractValidString $mundipaggId
     * @return mixed
     * @throws APIException
     */
    public function updateRecipientTransferSettingsAtMundipagg(
        TransferSettingsInterface $transferSettings,
        RecipientId $mundipaggId
    ) {
        $transferSettingsRequest = $transferSettings->convertToSdkRequestUpdate();
        return $this->mundiAPIClient->getRecipients()->updateRecipientTransferSettings(
            $mundipaggId->getValue(),
            $transferSettingsRequest
        );
    }
}
