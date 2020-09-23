<?php

namespace Mundipagg\Core\Split\Interfaces;

use Mundipagg\Core\Kernel\ValueObjects\Type;
use Mundipagg\Core\Split\Aggregates\Recipient;
use Mundipagg\Core\Split\ValueObjects\StatusRecipient;

interface RecipientInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return RecipientInterface
     */
    public function setId($id);

    /**
     * @param int $id
     * @return RecipientInterface
     */
    public function setExternalRecipientId($id);

    /**
     * @return int
     */
    public function getExternalRecipientId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return RecipientInterface
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param $email
     * @return RecipientInterface
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param $description
     * @return RecipientInterface
     */
    public function setDescription($description);

    /**
     * @return string
     */
    public function getDocument();

    /**
     * @param $document
     * @return RecipientInterface
     */
    public function setDocument($document);

    /**
     * @return StatusRecipient
     */
    public function getStatus();

    /**
     * @param StatusRecipient $status
     * @return RecipientInterface
     */
    public function setStatus(StatusRecipient $status);

    /**
     * @return Type
     */
    public function getType();

    /**
     * @param Type $type
     * @return RecipientInterface
     */
    public function setType(Type $type);

    /**
     * @return \Mundipagg\Core\Split\Interfaces\BankAccountInterface
     */
    public function getBankAccount();

    /**
     * @param BankAccountInterface $bankAccount
     * @return RecipientInterface
     */
    public function setBankAccount(\Mundipagg\Core\Split\Interfaces\BankAccountInterface $bankAccount);

    /**
     * @param string[]
     * @return RecipientInterface
     */
    public function setMetadata($metaData);

    /**
     * @return string[]
     */
    public function getMetadata();

    /**
     * @return bool
     */
    public function isMarketPlace();

    /**
     * @param bool $isMarketplace
     * @return RecipientInterface
     */
    public function setIsMarketPlace($isMarketplace);

    /**
     * @return TransferSettingsInterface
     */
    public function getTransferSettings();

    /**
     * @param TransferSettingsInterface $transferSettings
     * @return Recipient
     */
    public function setTransferSettings(TransferSettingsInterface $transferSettings);
}
