<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    private $accountSid;
    private $authToken;
    private $fromNumber;
    private $client;

    public function __construct(string $accountSid, string $authToken, string $fromNumber)
    {
        $this->accountSid = $accountSid;
        $this->authToken = $authToken;
        $this->fromNumber = $fromNumber;
        $this->client = new Client($this->accountSid, $this->authToken);
    }

    public function sendSms(string $toNumber, string $message)
    {
        $this->client->messages->create(
            $toNumber,
            array(
                'from' => $this->fromNumber,
                'body' => $message
            )
        );
    }
}
