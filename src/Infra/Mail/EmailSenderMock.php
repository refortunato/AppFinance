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

    public function send(): bool
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'http://o4d9z.mocklab.io/notify',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 15
        ]);
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);       
        curl_close($curl);
        if ($httpcode != '200') {
            return false;
        }
        if (empty($response)) {
            return false;
        }
        $responseDecoded = json_decode($response, true);
        if (! isset($responseDecoded['message']) || strtoupper($responseDecoded['message']) !== 'SUCCESS' ) {
            return false;
        }
        return true;
    }
}