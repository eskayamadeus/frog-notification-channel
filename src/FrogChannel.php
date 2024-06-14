<?php

namespace EskayAmadeus\FrogNotificationChannel;

use EskayAmadeus\FrogNotificationChannel\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;
use Exception;
use Illuminate\Support\Facades\Http;

class FrogChannel
{
    protected int $minCharacterCount = 2;
    protected int $maxCharacterCount = 100;
    protected FrogMessage $frogMessage;

    public function __construct(protected FrogApi $frogClient)
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

        // use debug phone number when in development
        if (config('frog-notification.development_mode') && !is_null(config('frog-notification.debugging_contact'))) {
            $phone = config('frog-notification.debugging_contact');
            logger('about to send development sms message');

            // return true;
        }

        $this->frogClient->send($phone, $this->frogMessage->content);

        // logger(json_encode($res->body()));

        // TODO for this app only, subtract from sms_credit when tihs class is called
        // count the number of destinations to know how many credits to subtract
        // $this->getSchool()->smsCredit()->
        // FrogSmsSent::dispatch($this->getSchool());

        return true;
    }
}
