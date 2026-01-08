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
        $token = 'eIVKnQ6XRAGrqqd9-ajBwz:APA91bGQPbB8p_FD7j9yNUl5IPuNXeRqI3MD8ONiAmQmvVXYUAWO7yQaUAnurz1qA1Io5nkj5EyDlz1jdEu5_WryhyA1mi-4Vus_gEgXiG0Z0H8UOO-LOuQ';

        $response = $this->firebase->sendToToken(
            $token,
            'Hello 👋',
            'Welcome to professional notifications!',
            ['screen' => 'details', 'id' => '99']
        );

        return response()->json(['success' => true, 'response' => $response]);
    }
}
