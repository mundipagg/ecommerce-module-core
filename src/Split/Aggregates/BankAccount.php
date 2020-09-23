<?php

namespace Mundipagg\Core\Split\Aggregates;

use MundiAPILib\Models\CreateBankAccountRequest;
use MundiAPILib\Models\UpdateRecipientBankAccountRequest;
use Mundipagg\Core\Kernel\Abstractions\AbstractEntity;
use Mundipagg\Core\Kernel\ValueObjects\Type;
use Mundipagg\Core\Split\ValueObjects\TypeBankAccount;
use Mundipagg\Core\Split\Interfaces\BankAccountInterface;

class BankAccount extends AbstractEntity implements BankAccountInterface
{
    /**
     * @var string
     */
    private $holderName;

    /**
     * @var TypeBankAccount
     */
    private $type;

    /**
     * @var string
     */
    private $holderDocument;

    /**
     * @var string
     */
    private $bank;

    /**
     * @var string
     */
    private $branchNumber;

    /**
     * @var string
     */
    private $branchCheckDigit;

    /**
     * @var string
     */
    private $accountNumber;

    /**
     * @var string
     */
    private $accountCheckDigit;

    /**
     * @var string[]
     */
    private $metaData;

    /**
     * @var Type
     */
    private $holderType;

    /**
     * @var string
     */
    private $recipientId;

    /**
     * @param string $recipientId
     * @return $this|BankAccountInterface
     */
    public function setRecipientId($recipientId)
    {
        $this->recipientId = $recipientId;
        return $this;
    }

    public function getRecipientId()
    {
        return $this->recipientId;
    }

    /**
     * @param string $holderName
     * @return BankAccount
     */
    public function setHolderName($holderName)
    {
        $this->holderName = $holderName;
        return $this;
    }

    /**
     * @return string
     */
    public function getHolderName()
    {
        return $this->holderName;
    }

    /**
     * @param Type $type
     * @return BankAccount
     */
    public function setHolderType(Type $holderType)
    {
        $this->holderType = $holderType;
        return $this;
    }

    /**
     * @return Type
     */
    public function getHolderType()
    {
        return $this->holderType;
    }

    /**
     * @param string $holderDocument
     * @return $this
     */
    public function setHolderDocument($holderDocument)
    {
        $this->holderDocument = $holderDocument;
        return $this;
    }

    /**
     * @return string
     */
    public function getHolderDocument()
    {
        return $this->holderDocument;
    }

    /**
     * @param string $bank
     * @return BankAccount
     */
    public function setBank($bank)
    {
        $this->bank = $bank;
        return $this;
    }

    /**
     * @return string
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * @param string $branchNumber
     * @return BankAccount
     */
    public function setBranchNumber($branchNumber)
    {
        $this->branchNumber = $branchNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getBranchNumber()
    {
        return $this->branchNumber;
    }

    /**
     * @param string $branchCheckDigit
     * @return BankAccount
     */
    public function setBranchCheckDigit($branchCheckDigit)
    {
        $this->branchCheckDigit = $branchCheckDigit;
        return $this;
    }

    /**
     * @return string
     */
    public function getBranchCheckDigit()
    {
        return $this->branchCheckDigit;
    }

    /**
     * @param string
     * @return BankAccount
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @param string
     * @return BankAccount
     */
    public function setAccountCheckDigit($accountCheckDigit)
    {
        $this->accountCheckDigit = $accountCheckDigit;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountCheckDigit()
    {
        return $this->accountCheckDigit;
    }

    /**
     * @param TypeBankAccount
     * @return BankAccount
     */
    public function setType(TypeBankAccount $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return TypeBankAccount
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string[]
     * @return BankAccount
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

    /**
     * @return mixed|string[]
     */
    public function jsonSerialize()
    {
        return ["testebank" => "bankarray"];
    }

    /**
     * @return UpdateRecipientBankAccountRequest
     */
    public function convertToSdkRequest()
    {
        $updateRecipientBankAccountRequest = new UpdateRecipientBankAccountRequest();

        $createBankAccountRequest = new CreateBankAccountRequest();
        $createBankAccountRequest->holderName = $this->getHolderName();
        $createBankAccountRequest->holderType = $this->getHolderType()->getValue();
        $createBankAccountRequest->holderDocument = $this->getHolderDocument();
        $createBankAccountRequest->bank = $this->getBank();
        $createBankAccountRequest->branchNumber = $this->getBranchNumber();
        $createBankAccountRequest->branchCheckDigit = $this->getBranchCheckDigit();
        $createBankAccountRequest->accountNumber = $this->getAccountNumber();
        $createBankAccountRequest->accountCheckDigit = $this->getAccountCheckDigit();
        $createBankAccountRequest->type = $this->getType()->getValue();
        $createBankAccountRequest->metadata = $this->getMetadata();

        $updateRecipientBankAccountRequest->bankAccount = $createBankAccountRequest;

        return $updateRecipientBankAccountRequest;
    }

    /**
     * @param BankAccountInterface|AbstractEntity $entity
     * @return bool
     */
    public function equals(AbstractEntity $entity)
    {
        if (
            $this->getBank() == $entity->getBank() &&
            $this->getBranchNumber() == $entity->getBranchNumber() &&
            $this->getBranchCheckDigit() == $entity->getBranchCheckDigit() &&
            $this->getAccountNumber() == $entity->getAccountNumber() &&
            $this->getAccountCheckDigit() == $entity->getAccountCheckDigit()
        ) {
            return true;
        }

        return false;
    }
}
