<?php

namespace EskayAmadeus\FrogNotificationChannel;

use EskayAmadeus\FrogNotificationChannel\Exceptions\CouldNotSendNotification;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;

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

            Http::withHeaders([
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
