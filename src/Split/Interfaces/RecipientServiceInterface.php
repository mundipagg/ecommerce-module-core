<?php

namespace Mundipagg\Core\Split\Interfaces;

interface RecipientServiceInterface
{
    /**
     * @param RecipientInterface $recipient
     * @return mixed
     */
    public function save(RecipientInterface $recipient);

    /**
     * @param RecipientInterface $recipient
     * @return RecipientInterface
     */
    public function createRecipientAtMundipagg(RecipientInterface $recipient);
}
