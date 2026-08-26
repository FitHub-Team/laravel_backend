<?php

namespace App\Services\Auth;

use GuzzleHttp\Client;


class MailService
{

    protected $client;
    protected $apiKey;
    protected $apiSecret;

    public function __construct()
    {
        $this->apiKey = config('services.mailjet.apikey');
        $this->apiSecret = config('services.mailjet.apisecret');
        $this->client = new Client();
    }
    public function send($to, $subject, $htmlBody, $textBody = null)
    {
        $response = $this->client->post('https://api.mailjet.com/v3.1/send', [
            'auth' => [$this->apiKey, $this->apiSecret],
            'json' => [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => config('mail.from.address'),
                            'Name' => config('mail.from.name'),
                        ],
                        'To' => [
                            [
                                'Email' => $to,
                            ],
                        ],
                        'Subject' => $subject,
                        'HTMLPart' => $htmlBody,
                        'TextPart' => $textBody ?? $subject,
                    ],
                ],
            ],
        ]);
        return json_decode($response->getBody(), true);
    }
}
