<?php

namespace AppFinance\Infra\TransferAuthorizer;

use AppFinance\Application\UseCases\MakeTransfer\ITransferAuthorizer;

class TransferAuthorizerMock implements ITransferAuthorizer
{
    public function verify(): bool
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6',
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
        if (! isset($responseDecoded['message']) || strtoupper($responseDecoded['message']) !== 'AUTORIZADO' ) {
            return false;
        }
        return true;
    }
}