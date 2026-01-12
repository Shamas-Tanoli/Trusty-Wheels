<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\BookingPassenger;
use App\Http\Controllers\Controller;

class BookingPassengerController extends Controller
{
   
    public function getpassenger(Request $request)
{
    $search = $request->input('search');

    $passengers = BookingPassenger::select('id', 'name', 'customer_id', 'dropoff_location')
        ->with('customer')
        ->when($search, function ($query, $search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        })
        ->limit(20)
        ->get()
        ->map(function ($passenger) {
            return [
                'id' => $passenger->id,
                'name' => $passenger->name . ' -Customer- ' . $passenger->customer->name . ' -Location- ' . $passenger->dropoff_location,
            ];
        });

    return response()->json($passengers);
}

}
