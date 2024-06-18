<?php

namespace EskayAmadeus\FrogNotificationChannel;

use EskayAmadeus\FrogNotificationChannel\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;

// TODO extract API into a different package in order to have multiple other endpoints we can use
class FrogApi
{
    public function __construct()
    {
        //
    }

    public function send(string $destination, string $message)
    {
        try {
            $baseUrl = config('frog-notification.base_url');

            $response = Http::withHeaders([
                    'API-KEY' => config('frog-notification.api_key'),
                    'USERNAME' => config('frog-notification.username')
                ])
                ->post(
                    stripslashes($baseUrl.'/sms/send'),
                    [
                        'senderid' => config('frog-notification.sender_id'),
                        // laravel appears to process each notification as an individual job
                        // therefore we only need to add the single notifiable in the destinations array
                        'destinations' => [
                            [
                                'destination' => $destination,
                                'message' => $message
                            ]
                        ]
                    ]
                );

            logger(json_encode($response->body()));

            // TODO for this app only, subtract from sms_credit when tihs class is called
            // count the number of destinations to know how many credits to subtract
            // $this->getSchool()->smsCredit()->
            // FrogSmsSent::dispatch($this->getSchool());

            return true;
        } catch (ClientException $e) {
            throw CouldNotSendNotification::serviceRespondedWithAnException($e);
        } catch (GuzzleException $e) {
            throw CouldNotSendNotification::unableToConnectError($e);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
