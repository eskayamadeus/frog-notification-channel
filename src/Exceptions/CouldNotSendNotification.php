<?php

namespace EskayAmadeus\FrogNotificationChannel\Exceptions;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnException(ClientException $e)
    {
        return new static('Service responded with an exception: ' . $e->getMessage(), $e->getCode(), $e);
    }

    public static function invalidContentLengthError($minChars, $maxChars)
    {
        return new static('Content may only be 3 - 100 characters', 422);
    }

    public static function unableToConnectError(GuzzleException $e)
    {
        return new static('Unable to connect to Frog server.' . $e->getMessage(), $e->getCode(), $e);
    }
}
