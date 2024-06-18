<?php

namespace EskayAmadeus\FrogNotificationChannel\Traits;

trait UsesFrogNotification
{
    /**
     * Get the phone number for receiving SMS notifications.
     *
     * @return string
     */
    public function getFrogPhoneNumber() :string {
        return $this->phone;
    }
}
