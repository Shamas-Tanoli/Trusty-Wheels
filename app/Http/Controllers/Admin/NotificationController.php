<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;

class NotificationController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function send()
    {
        $token = 'cIp7RtPQSem4J6oHhA-lWn:APA91bFr8gh2MFaHnrNvKVk1kISiSt5Yy_wmm-zd7jm5eiGCe3lilmp9YrOBpWHLD8ZUrGRgtpO_S0YofYY2yyijdv8L6e87W5jeL0EUtb88okgb6JERjbc';

        $response = $this->firebase->sendToToken(
            $token,
            'Hello 👋',
            'Welcome to professional notifications!',
            ['screen' => 'details', 'id' => '99']
        );

        return response()->json(['success' => true, 'response' => $response]);
    }
}
