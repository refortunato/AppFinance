<?php

namespace AppFinance\Domain\Events\Handler;

use AppFinance\Protocols\EmailSender;
use AppFinance\Protocols\EventHandler;
use AppFinance\Shared\Exceptions\TransferException;

class CompletedTransferSendMailHandler implements EventHandler
{
    private EmailSender $emailSender;

    public function __construct(EmailSender $emailSender)
    {
        $this->emailSender = $emailSender;
    }

    /**
     * @param AppFinance\Domain\Events\CompletedTransfer $event
     */
    public function notify($event)
    {
        $transfer_data = [
            'email_destiny' => $event->getTransfer()->getDestinyAccount()->getEmail()->getEmailAddress(),
            'name_destiny' => $event->getTransfer()->getDestinyAccount()->getName(),
            'value' => number_format($event->getTransfer()->getValue(), 2, ',', '.'),
            'name_origin' => $event->getTransfer()->getOriginAccount()->getName()
        ];
        $body = 'Olá,  '.$transfer_data['name_destiny'].'!<br>'.$transfer_data['name_origin'].' transferiu '.$transfer_data['value'].' para sua conta.<br>Tchau tchau!';
        $this->emailSender->setName($transfer_data['name_destiny']);
        $this->emailSender->setEmailAddress($transfer_data['email_destiny']);
        $this->emailSender->setEmailBody($body);
        if (! $this->emailSender->send()) {
            throw new TransferException("Não foi possível notificar o usuário");
        }
    }
}