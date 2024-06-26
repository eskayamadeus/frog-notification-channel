<?php

namespace EskayAmadeus\FrogNotificationChannel;

use EskayAmadeus\FrogNotificationChannel\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;
use Exception;

class FrogChannel
{
    protected int $minCharacterCount = 2;
    protected int $maxCharacterCount = 100;
    protected FrogMessage $frogMessage;
    protected FrogApi $frogClient;

    public function __construct()
    {
        // Initialisation code here
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \EskayAmadeus\FrogNotificationChannel\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $phone = $notifiable->getFrogPhoneNumber();

        $this->frogMessage = $notification->toFrog($notifiable);

        if (! $phone) {
            throw new Exception('Unable to send notification');
            return;
        }

        // pre-validate content length
        $strLength = str($this->frogMessage->content)->length();

        if ($strLength < $this->minCharacterCount || $strLength > $this->maxCharacterCount) {
            throw CouldNotSendNotification::invalidContentLengthError($this->minCharacterCount, $this->maxCharacterCount);
        }

        if (config('frog-notification.dry_run')) {
            // do not send
            sleep(3);
            logger('simulating message sender');
            return true;
        }

        // use debug phone number when in development
        if (config('frog-notification.development_mode') && !is_null(config('frog-notification.debugging_contact'))) {
            $phone = config('frog-notification.debugging_contact');
            logger('about to send development sms message');
        }

        $this->frogClient->send($phone, $this->frogMessage->content);

        return true;
    }
}
