<?php

namespace AppFinance\Infra\Mail;

use AppFinance\Protocols\EmailSender;

class EmailSenderMock implements EmailSender
{
    private string $name = '';
    private string $email_address = '';
    private string $email_body = '';
    private array $attachments = [];
    
    public function setEmailAddress(string $value)
    {
        $this->email_address = $value;
    }

    public function setName(string $value)
    {
        $this->name = $value;
    }

    public function setEmailBody(string $value)
    {
        $this->email_body = $value;
    }

    public function setAttachment(string $path)
    {
        $this->attachments[] = $path;
    }

    public function send()
    {

    }
}