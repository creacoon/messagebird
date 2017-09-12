<?php

namespace NotificationChannels\Messagebird;

use Exception;
use GuzzleHttp\Client;
use NotificationChannels\Messagebird\Exceptions\CouldNotSendNotification;

class MessagebirdClient
{
    protected $client;
    protected $access_key;

    /**
     * MessagebirdClient constructor.
     * @param Client $client
     * @param $access_key string API Key from Messagebird API
     */
    public function __construct(Client $client, $access_key)
    {
        $this->client = $client;
        $this->access_key = $access_key;
    }

    /**
     * Send the Message.
     * @param MessagebirdMessage $message
     * @throws CouldNotSendNotification
     */
    public function send(MessagebirdMessage $message)
    {
        if (empty($message->originator)) {
            $message->setOriginator(config('services.messagebird.originator'));
        }
        if (empty($message->recipients)) {
            $message->setRecipients(config('services.messagebird.recipients'));
        }

        try {
            $response =  $this->client->request('POST', 'https://rest.messagebird.com/messages', [
                'body' => $message->toJson(),
                'headers' => [
                    'Authorization' => 'AccessKey '.$this->access_key,
                ],
                'http_errors' => false,
            ]);

            if ($response_processor_class = config('services.messagebird.response_processor_class')) {
                $r = new \ReflectionClass($response_processor_class);
                $response_processor = $r->newInstanceArgs([$message, $response]);
                $response_processor->run();
            }
        } catch (Exception $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        }
    }
}
