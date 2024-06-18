<?php

namespace EskayAmadeus\FrogNotificationChannel;

use Illuminate\Support\Arr;

class FrogMessage
{
    /**
     * The message content.
     *
     * @var string
     */
    public $content;

    /**
     * Create a message object.
     * @param string $content
     * @return static
     */
    public static function create(string $content = ''): self
    {
        return new static($content);
    }

    /**
     * Create a new message instance.
     *
     * @param  string $content
     */
    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the message content.
     *
     * @param  string $content
     * @return $this
     */
    public function content(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the Sender ID.
     *
     * @return string|null
     */
    public function getSenderID(): ?string
    {
        return config('frog-notification.sender_id');
    }
}
