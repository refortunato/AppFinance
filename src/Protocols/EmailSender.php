<?php

namespace AppFinance\Protocols;

interface EmailSender
{
    public function setEmailAddress(string $value);
    public function setName(string $value);
    public function setEmailBody(string $value);
    public function setAttachment(string $path);
    public function send(): bool;
}