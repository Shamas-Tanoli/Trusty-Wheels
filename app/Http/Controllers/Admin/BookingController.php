<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Http;

class BookingController extends Controller
{

    public function send()
{
    return $this->sendFcmToToken('eIVKnQ6XRAGrqqd9-ajBwz:APA91bGQPbB8p_FD7j9yNUl5IPuNXeRqI3MD8ONiAmQmvVXYUAWO7yQaUAnurz1qA1Io5nkj5EyDlz1jdEu5_WryhyA1mi-4Vus_gEgXiG0Z0H8UOO-LOuQ');
}

  public  function base64UrlEncode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

 public function getAccessToken()
{
    $serviceAccount = json_decode(file_get_contents(storage_path('app/firebase.json')), true);

    $header = $this->base64UrlEncode(json_encode([
        'alg' => 'RS256',
        'typ' => 'JWT'
    ]));

    $now = time();
    $payload =  $this->base64UrlEncode(json_encode([
        'iss'   => $serviceAccount['client_email'],
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        'aud'   => 'https://oauth2.googleapis.com/token',
        'iat'   => $now,
        'exp'   => $now + 3600
    ]));

    $signatureInput = "$header.$payload";
    openssl_sign($signatureInput, $signature, $serviceAccount['private_key'], 'SHA256');
    $jwt = "$signatureInput." . $this->base64UrlEncode($signature);

    // Exchange JWT for access token
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://oauth2.googleapis.com/token',
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        CURLOPT_POSTFIELDS => http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ])
    ]);

    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);

    return $result['access_token'];
}

 public function sendFcmToToken($fcmToken)
{
    $accessToken = $this->getAccessToken();
    $projectId = 'trustwheels';

    $payload = [
        "message" => [
            "token" => $fcmToken,
            "notification" => [
                "title" => "Hello 👋",
                "body" => "HTTP v1 without any library"
            ],
            "data" => [
                "screen" => "details",
                "id" => "99"
            ]
        ]
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "https://fcm.googleapis.com/v1/projects/$projectId/messages:send",
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}


    public function index()
    {
        return view('admin.content.pages.booking.list');
    }



    public function list(Request $request)
    {
        // Get all bookings with passengers and customer
        $bookings = Booking::with(['passengers', 'user', 'plan', 'service', 'serviceTime', 'town']);

        return DataTables::of($bookings)
            ->addIndexColumn() // Serial Number
            ->addColumn('customer_name', function ($row) {
                return $row->user->customer->name ?? 'N/A';
            })
            ->addColumn('plan_name', function ($row) {
                return $row->plan->name ?? 'N/A';
            })
            ->addColumn('service_name', function ($row) {
                return $row->service->name ?? 'N/A';
            })
            ->addColumn('service_time', function ($row) {
                return $row->serviceTime->timing ?? $row->serviceTime->timing . ' - ' . $row->serviceTime->timing;
            })
            ->addColumn('town_name', function ($row) {
                return $row->town->name ?? 'N/A';
            })
            ->addColumn('passengers_count', function ($row) {
                return $row->passengers->count();
            })
            ->addColumn('status', function ($row) {
                return $row->status;
            })
            ->addColumn('action', function ($row) {
                return '
    <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ti ti-dots-vertical"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end m-0">
   
        <a href="javascript:void(0)"
       data-bs-target="#passengerModal" data-bs-toggle="modal" data-bs-dismiss="modal"
        class="dropdown-item view-passengers" data-id="' . $row->id . '">View Passengers</a>


        <a href="javascript:void(0)"
       data-bs-target="#addPermissionModal" data-bs-toggle="modal" data-bs-dismiss="modal"
        class="dropdown-item viewstatus edit-btn editbtnnnn" id="editbtnnnn" data-status="' . $row->status . '" data-id="' . $row->id . '">Change Status</a>
        
    </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function bookingdetail($id)
    {

        $booking = Booking::with('passengers')->find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $booking->passengers
        ]);
    }


    public function bookingstatus(Request $request)
    {
        $bookingId = $request->input('booking_id');
        $status = $request->input('status');

        $booking = Booking::find($bookingId);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        $booking->status = $status;
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated successfully'
        ]);
    }
}
