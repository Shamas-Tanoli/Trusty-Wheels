<?php

namespace App\Http\Controllers\Api;

use App\Models\Plan;
use App\Models\Town;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Discount;
use App\Models\ServiceTime;
use Illuminate\Http\Request;
use App\Models\BookingPassenger;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceJobPassenger;
use App\Http\Controllers\Controller;


class BookingController extends Controller
{

    public function addChildren(Request $request, $id)
    {

        $booking = Booking::with('passengers')->find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }


        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'pickup_time' => 'required',
            'dropoff_time' => 'required',
            'pickup_latitude' => 'required|numeric',
            'pickup_longitude' => 'required|numeric',
            'dropoff_latitude' => 'required|numeric',
            'dropoff_longitude' => 'required|numeric',
            'pickup_location' => 'required|string',
            'dropoff_location' => 'required|string',
            'plan_id' => 'required|exists:plans,id',
        ]);

        DB::beginTransaction();

        try {

            $passenger = $booking->passengers()->create([
                'plan_id' => $validated['plan_id'],
                'customer_id' => $booking->customer_id,
                'booking_id' => $booking->id,
                'name' => $validated['name'],
                'pickup_time' => $validated['pickup_time'],
                'dropoff_time' => $validated['dropoff_time'],
                'pickup_latitude' => $validated['pickup_latitude'],
                'pickup_longitude' => $validated['pickup_longitude'],
                'dropoff_latitude' => $validated['dropoff_latitude'],
                'dropoff_longitude' => $validated['dropoff_longitude'],
                'pickup_location' => $validated['pickup_location'],
                'dropoff_location' => $validated['dropoff_location'],

            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Passenger added successfully',
                'data' => $passenger
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to add passenger',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    public function passengerStatus(Request $request)
    {

        $validated = $request->validate([
            'passenger_id' => 'required|exists:service_job_passengers,passenger_id',
            'status'       => 'required|string',
        ]);
        $passenger = ServiceJobPassenger::where('passenger_id', $validated['passenger_id'])->first();

        if (!$passenger) {
            return response()->json([
                'success' => false,
                'message' => 'Passenger not found',
            ], 404);
        }

        $passenger->status = $validated['status'];
        $passenger->save();


        return response()->json([
            'success' => true,
            'message' => 'Passenger status updated successfully',
        ]);
    }

    public function customerbooking(Request $request)
    {
        $customerId = $request->user()->id;

        $bookings = Booking::where('customer_id', $customerId)
            ->with([
                'passengers.plan',
                'passengers.plan.areaFrom',
                'passengers.plan.areaTo',
                'passengers.plan.serviceTime',
                'service',
                'serviceTime',
                'town'
            ])
            ->get();

        return response()->json([
            'success' => true,
            'bookings' => $bookings,
            'message' => 'Customer bookings fetched successfully'
        ]);
    }

    public function bookingtype()
    {
        $bookingtype = DB::table('booking_types')->get();

        return response()->json([
            'success' => true,
            'booking_type' => $bookingtype,
            'messages' => 'booking types fetched successfully'
        ]);
    }


    public function areaToAreaFromServiceTimePlan(Request $request)
    {

        $request->validate([
            'area_to_id' => 'required|integer',
            'area_from_id' => 'required|integer',
            'service_time_id' => 'required|integer',
        ]);

        $areaToId = $request->area_to_id;
        $areaFromId = $request->area_from_id;
        $serviceTimeId = $request->service_time_id;

        $plans = Plan::where('area_to_id', $areaToId)
            ->where('area_from_id', $areaFromId)
            ->where('service_time_id', $serviceTimeId)
            ->with(['areaFrom', 'areaTo', 'serviceTime'])
            ->get();

        if ($plans->isEmpty()) {
            return response()->json([
                'success' => false,
                'plans' => [],
                'message' => 'Plan not found for the given criteria'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'plans' => $plans,
            'message' => 'Plans fetched successfully for the given criteria'
        ]);
    }


    public function area()
    {
        $town = Town::with(['city'])->get();
        return response()->json([
            'success' => true,
            'areas' => $town,
            'messages' => 'areas fetched successfully'
        ]);
    }
    public function allThing()
    {
        $town = Town::with(['city'])->get();
        $service = Service::with(['serviceTimes'])->get();
        $serviceTime = ServiceTime::with(['service', 'plans'])->get();
        $plan = Plan::with(['areaFrom', 'areaTo', 'serviceTime'])->get();


        return response()->json([
            'success' => true,
            'town' => $town,
            'service' => $service,
            'serviceTime' => $serviceTime,
            'plan' => $plan,
            'messages' => 'ahmer Bhai sab agyaaa is api maii or hukam kro'
        ]);
    }
    public function store(Request $request)
    {



        $validatedData = $request->validate([

            'booking_type_id' => 'required|exists:booking_types,id',
            'customer_id' => 'required|exists:users,id',
            'service_time_id' => 'required|exists:service_times,id',
            'service_id' => 'required|exists:services,id',
            'town_id' => 'required|exists:towns,id',
            'status' => 'required|string',
            'passengers' => 'required|array|min:1',
            'passengers.*.name' => 'required|string|max:255',
            'passengers.*.pickup_time' => 'required',
            'passengers.*.dropoff_time' => 'required',
            'passengers.*.pickup_latitude' => 'required|numeric',
            'passengers.*.pickup_longitude' => 'required|numeric',
            'passengers.*.dropoff_latitude' => 'required|numeric',
            'passengers.*.dropoff_longitude' => 'required|numeric',
            'passengers.*.pickup_location' => 'required|string',
            'passengers.*.dropoff_location' => 'required|string',
            'passengers.*.plan_id' => 'required|exists:plans,id',
        ]);

        DB::beginTransaction();

        try {

            $booking = Booking::create([
                'booking_type_id' => $validatedData['booking_type_id'],
                'customer_id' => $validatedData['customer_id'],
                'service_time_id' => $validatedData['service_time_id'],
                'service_id' => $validatedData['service_id'],
                'town_id' => $validatedData['town_id'],
                'status' => $validatedData['status'],
            ]);


            foreach ($validatedData['passengers'] as $passenger) {
                $booking->passengers()->create([
                    'customer_id' => $validatedData['customer_id'],
                    'name' => $passenger['name'],
                    'pickup_time' => $passenger['pickup_time'],
                    'dropoff_time' => $passenger['dropoff_time'],
                    'pickup_latitude' => $passenger['pickup_latitude'],
                    'pickup_longitude' => $passenger['pickup_longitude'],
                    'dropoff_latitude' => $passenger['dropoff_latitude'],
                    'dropoff_longitude' => $passenger['dropoff_longitude'],
                    'pickup_location' => $passenger['pickup_location'],
                    'dropoff_location' => $passenger['dropoff_location'],
                    'plan_id' => $passenger['plan_id'],
                ]);
            }

            

            $booking->load('passengers.plan');
            $totalAmount = $booking->passengers->sum('plan.price');
            $currentBookingPassengers = $booking->passengers->count();

         
            $activeLifetimePassengers = BookingPassenger::where('customer_id', $validatedData['customer_id'])
                ->whereHas('booking', function ($q) {
                    $q->where('status', 'active');
                })
                ->count();

           
            $totalPassengersForDiscount = $activeLifetimePassengers + $currentBookingPassengers;


           
            $isPromoNeeded = $totalPassengersForDiscount ? false :true;
            $discountAmount = 0;
            $discountApplied = null;


            if ($totalPassengersForDiscount > 1) {

                $discount = Discount::where('is_active', 1)
                    ->where('person', '<=', $totalPassengersForDiscount)
                    ->orderBy('person', 'desc')
                    ->first();

                if ($discount) {
                    if ($discount->type === 'percentage') {
                        $discountAmount = ($totalAmount * $discount->value) / 100;
                    } else {
                        $discountAmount = $discount->value;
                    }

                    
                    $discountAmount = min($discountAmount, $totalAmount);
                    $discountApplied = $discount;
                }
            }

            $payableAmount = $totalAmount - $discountAmount;
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'booking' => $booking,

                'current_booking_passengers' => $currentBookingPassengers,
                'active_lifetime_passengers' => $totalPassengersForDiscount,

                'total_amount'   => $totalAmount,
                'discount' => [
                    'applied' => $discountAmount > 0,
                    'type'    => $discountApplied?->type,
                    'value'   => $discountApplied?->value,
                    'amount'  => $discountAmount,
                ],
                'payable_amount' => $payableAmount,
                'isPromoNeeded'=>$isPromoNeeded

            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
