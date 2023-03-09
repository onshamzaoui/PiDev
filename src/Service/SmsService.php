<?php
namespace App\Service;

use Twilio\Rest\Client;

class SmsService
{
    private $twilioClient;
    private $fromNumber;
    private $twilioAccountSid='AC3c9d8191146d6c4eea22ad16d93e4743';

    public function __construct(string $twilioAccountSid, string $twilioAuthToken, string $fromNumber)
    {
        $this->twilioAccountSid = $twilioAccountSid='AC3c9d8191146d6c4eea22ad16d93e4743';
        $this->twilioClient = new Client($twilioAccountSid, $twilioAuthToken);
        $this->fromNumber = $fromNumber;
    }

    public function sendSms(string $toNumber, string $message): void
    {
        $message = $this->twilioClient->messages->create(
            $toNumber,
            [
                'from' => $this->fromNumber,
                'body' => $message,
            ]
        );

        // Check if the message was successfully sent
        if ($message->errorCode !== null) {
            throw new \RuntimeException(sprintf('Failed to send SMS: %s', $message->errorMessage));
        }
    }

    public function getTwilioAccountSid(): string
    {
        return $this->twilioAccountSid;
    }
}
