<?php

namespace Mundipagg\Core\Split\Aggregates;

use MundiAPILib\Models\CreateBankAccountRequest;
use MundiAPILib\Models\CreateRecipientRequest;
use MundiAPILib\Models\UpdateRecipientRequest;
use Mundipagg\Core\Kernel\Abstractions\AbstractEntity;
use Mundipagg\Core\Split\ValueObjects\StatusRecipient;
use Mundipagg\Core\Payment\Aggregates\Customer;
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
     * @var \Mundipagg\Core\Kernel\ValueObjects\Type
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
     * @param string $email
     * @return Customer
     * @throws \Exception
     */
    public function setEmail($email)
    {
        $this->email = substr($email, 0, 64);

        if (empty($this->email)) {

            $message = $this->i18n->getDashboard(
                "The %s should not be empty!",
                "email"
            );

            throw new \Exception($message, 400);
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
     * @throws \Exception
     */
    public function setDocument($document)
    {
        $this->document = substr($document, 0, 16);

        if (empty($this->document)) {

            $inputName = $this->i18n->getDashboard('document');
            $message = $this->i18n->getDashboard(
                "The %s should not be empty!",
                $inputName
            );

            throw new \Exception($message, 400);
        }

        return $this;
    }

    /**
     * @return \Mundipagg\Core\Kernel\ValueObjects\Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \Mundipagg\Core\Kernel\ValueObjects\Type $type
     * @return Recipient
     */
    public function setType(\Mundipagg\Core\Kernel\ValueObjects\Type $type)
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
        return ["teste" => "ok"];
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

        $createBankAccountRequest = new CreateBankAccountRequest();
        $createBankAccountRequest->holderName = $this->getBankAccount()->getHolderName();
        $createBankAccountRequest->holderType = $this->getBankAccount()->getHolderType()->getValue();
        $createBankAccountRequest->holderDocument = $this->getBankAccount()->getHolderDocument();
        $createBankAccountRequest->bank = $this->getBankAccount()->getBank();
        $createBankAccountRequest->branchNumber = $this->getBankAccount()->getBranchNumber();
        $createBankAccountRequest->branchCheckDigit = $this->getBankAccount()->getBranchCheckDigit();
        $createBankAccountRequest->accountNumber = $this->getBankAccount()->getAccountNumber();
        $createBankAccountRequest->accountCheckDigit = $this->getBankAccount()->getAccountCheckDigit();
        $createBankAccountRequest->type = $this->getBankAccount()->getType()->getValue();
        $createBankAccountRequest->metadata = $this->getBankAccount()->getMetadata();

        $recipientRequest->defaultBankAccount = $createBankAccountRequest;
        $recipientRequest->metadata = $this->getMetadata();

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
