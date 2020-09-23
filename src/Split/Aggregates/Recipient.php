<?php

namespace Mundipagg\Core\Split\Aggregates;

use Exception;
use MundiAPILib\Models\CreateBankAccountRequest;
use MundiAPILib\Models\CreateRecipientRequest;
use MundiAPILib\Models\CreateTransferSettingsRequest;
use MundiAPILib\Models\UpdateRecipientRequest;
use Mundipagg\Core\Kernel\Abstractions\AbstractEntity;
use Mundipagg\Core\Kernel\ValueObjects\Type;
use Mundipagg\Core\Split\Interfaces\TransferSettingsInterface;
use Mundipagg\Core\Split\ValueObjects\StatusRecipient;
use Mundipagg\Core\Split\Interfaces\BankAccountInterface;
use Mundipagg\Core\Split\Interfaces\RecipientInterface;

class Recipient extends AbstractEntity implements RecipientInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Type
     */
    private $type;

    /**
     * @var BankAccountInterface
     */
    private $bankAccount;

    /**
     * @var string[]
     */
    private $metaData;

    /**
     * @var int
     */
    private $externalRecipientId;

    /**
     * @var StatusRecipient
     */
    private $status;

    /**
     * @var bool
     */
    private $isMarketplace = false;

    /**
     * @var string
     */
    private $document;

    /**
     * @var TransferSettingsInterface
     */
    private $transferSettings;

    /**
     * @return int
     */
    public function getExternalRecipientId()
    {
        return $this->externalRecipientId;
    }

    /**
     * @param int $externalRecipientId
     * @return Recipient
     */
    public function setExternalRecipientId($externalRecipientId)
    {
        $this->externalRecipientId = $externalRecipientId;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Recipient
     */
    public function setName($name)
    {
        $this->name = substr($name, 0, 64);
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     * @return $this|RecipientInterface
     * @throws Exception
     */
    public function setEmail($email)
    {
        $this->email = substr($email, 0, 64);

        if (empty($this->email)) {
            $message = "The email should not be empty!";
            throw new Exception($message, 400);
        }

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $description
     * @return $this|RecipientInterface
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param $document
     * @return $this
     * @throws Exception
     */
    public function setDocument($document)
    {
        $this->document = substr($document, 0, 16);

        if (empty($this->document)) {
            throw new Exception("The %s should not be empty!", 400);
        }

        return $this;
    }

    /**
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param Type $type
     * @return Recipient
     */
    public function setType(Type $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return BankAccountInterface
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    /**
     * @param BankAccountInterface $bankAccount
     * @return Recipient
     */
    public function setBankAccount(BankAccountInterface $bankAccount)
    {
        $this->bankAccount = $bankAccount;
        return $this;
    }

    /**
     * @return TransferSettingsInterface
     */
    public function getTransferSettings()
    {
        return $this->transferSettings;
    }

    /**
     * @param TransferSettingsInterface $transferSettings
     * @return Recipient
     */
    public function setTransferSettings(TransferSettingsInterface $transferSettings)
    {
        $this->transferSettings = $transferSettings;
        return $this;
    }

    /**
     * @return StatusRecipient
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param StatusRecipient $status
     * @return Recipient
     */
    public function setStatus(StatusRecipient $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param string[] $metaData
     * @return Recipient
     */
    public function setMetadata($metaData)
    {
        $this->metaData = $metaData;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getMetadata()
    {
        return $this->metaData;
    }

    public function jsonSerialize()
    {
        return ["name" => $this->getName()];
    }

    /**
     * @return CreateRecipientRequest
     */
    public function convertToSdkRequest()
    {
        $recipientRequest = new CreateRecipientRequest();
        $recipientRequest->name = $this->getName();
        $recipientRequest->email = $this->getEmail();
        $recipientRequest->description = $this->getDescription();
        $recipientRequest->document = $this->getDocument();
        $recipientRequest->type = $this->getType()->getValue();
        $recipientRequest->metadata = $this->getMetadata();

        $bankAccountRequest = new CreateBankAccountRequest();
        $bankAccountRequest->holderName = $this->getBankAccount()->getHolderName();
        $bankAccountRequest->holderType = $this->getBankAccount()->getHolderType()->getValue();
        $bankAccountRequest->holderDocument = $this->getBankAccount()->getHolderDocument();
        $bankAccountRequest->bank = $this->getBankAccount()->getBank();
        $bankAccountRequest->branchNumber = $this->getBankAccount()->getBranchNumber();
        $bankAccountRequest->branchCheckDigit = $this->getBankAccount()->getBranchCheckDigit();
        $bankAccountRequest->accountNumber = $this->getBankAccount()->getAccountNumber();
        $bankAccountRequest->accountCheckDigit = $this->getBankAccount()->getAccountCheckDigit();
        $bankAccountRequest->type = $this->getBankAccount()->getType()->getValue();
        $bankAccountRequest->metadata = $this->getBankAccount()->getMetadata();

        $transferSettingsRequest = new CreateTransferSettingsRequest();
        $transferSettingsRequest->transferInterval = $this->getTransferSettings()
            ->getTransferInterval()
            ->getValue();

        $transferSettingsRequest->transferEnabled = $this->getTransferSettings()
            ->isTransferEnabled();

        $transferSettingsRequest->transferDay = $this->getTransferSettings()
            ->getTransferDay();

        $recipientRequest->transferSettings = $transferSettingsRequest;
        $recipientRequest->defaultBankAccount = $bankAccountRequest;

        return $recipientRequest;
    }

    /**
     * @return UpdateRecipientRequest
     */
    public function convertToSdkRequestUpdate()
    {
        $recipientRequest = new UpdateRecipientRequest();

        $recipientRequest->name = $this->getName();
        $recipientRequest->email = $this->getEmail();
        $recipientRequest->description = $this->getDescription();
        $recipientRequest->type = $this->getType()->getValue();
        $recipientRequest->status = $this->getStatus()->getValue();
        $recipientRequest->metadata = $this->getMetadata();

        return $recipientRequest;
    }

    /**
     * @return bool
     */
    public function isMarketPlace()
    {
        return $this->isMarketplace;
    }

    /**
     * @param bool $isMarketplace
     * @return Recipient
     */
    public function setIsMarketPlace($isMarketplace)
    {
        $this->isMarketplace = $isMarketplace;
        return $this;
    }
}
