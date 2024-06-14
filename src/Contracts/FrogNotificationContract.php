<?php

namespace EskayAmadeus\FrogNotificationChannel\Contracts;

interface FrogNotificationContract
{
    /**
     * Get the phone number identifier for the user.
     *
     * @return string
     */
    public function getFrogPhoneNumber(): string;
}
