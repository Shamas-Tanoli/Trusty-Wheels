<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/firebase.json'));

        $this->messaging = $factory->createMessaging();
    }




    public function sendToToken($token, $title = 'Hello 👋', $body = 'Notification body', $data = [])
    {
       $message = CloudMessage::new()
        ->withNotification(['title' => $title, 'body' => $body])
        ->withData($data)
        ->toToken($token); // correct

        return $this->messaging->send($message);
    }
}
