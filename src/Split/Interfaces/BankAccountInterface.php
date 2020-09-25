<?php


namespace Mundipagg\Core\Split\Interfaces;


use Mundipagg\Core\Split\ValueObjects\TypeBankAccount;

interface BankAccountInterface
{
    /**
     * @param string $recipientId
     * @return BankAccountInterface
     */
    public function setRecipientId($recipientId);

    /**
     * @return string
     */
    public function getRecipientId();

    /**
     * @param string $holderName
     * @return BankAccountInterface
     */
    public function setHolderName($holderName);

    /**
     * @return string
     */
    public function getHolderName();

    /**
     * @param \Mundipagg\Core\Kernel\ValueObjects\DocumentType $type
     * @return BankAccountInterface
     */
    public function setHolderType(\Mundipagg\Core\Kernel\ValueObjects\DocumentType $type);

    /**
     * @return \Mundipagg\Core\Kernel\ValueObjects\DocumentType
     */
    public function getHolderType();

    /**
     * @param $holderDocument
     * @return $this
     */
    public function setHolderDocument($holderDocument);

    /**
     * @return string
     */
    public function getHolderDocument();

    /**
     * @param string
     * @return BankAccountInterface
     */
    public function setBank($bank);

    /**
     * @return string
     */
    public function getBank();

    /**
     * @param string
     * @return BankAccountInterface
     */
    public function setBranchNumber($branchNumber);

    /**
     * @return string
     */
    public function getBranchNumber();

    /**
     * @param string
     * @return BankAccountInterface
     */
    public function setBranchCheckDigit($branchCheckDigit);

    /**
     * @return string
     */
    public function getBranchCheckDigit();

    /**
     * @param string
     * @return BankAccountInterface
     */
    public function setAccountNumber($accountNumber);

    /**
     * @return string
     */
    public function getAccountNumber();

    /**
     * @param string
     * @return BankAccountInterface
     */
    public function setAccountCheckDigit($accountCheckDigit);

    /**
     * @return string
     */
    public function getAccountCheckDigit();

    /**
     * @param TypeBankAccount
     * @return BankAccountInterface
     */
    public function setType(TypeBankAccount $type);

    /**
     * @return TypeBankAccount
     */
    public function getType();

    /**
     * @param string[]
     * @return BankAccountInterface
     */
    public function setMetadata($metaData);

    /**
     * @return string[]
     */
    public function getMetadata();
}
