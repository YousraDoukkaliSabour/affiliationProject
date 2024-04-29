<?php

namespace App\Message;

use App\Message\NotificationMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NotificationMessageHandler implements MessageHandlerInterface
{
    public function __invoke(NotificationMessage $message)
    {
        // Here you can implement the logic to handle the notification message
        // For demonstration purposes, we'll just dump the message
        dump($message->getMessage());
    }

}