<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\CustomerInvoice;
use App\Models\CustomerInvoiceItem;
use App\Models\CustomerSubscription;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerInvoiceController extends Controller
{




    public function changeStatus(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:customer_invoices,id',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);

        $invoice = CustomerInvoice::findOrFail($request->invoice_id);

        $newPayment = (float) $request->paid_amount;

        $oldPaid = (float) $invoice->paid_amount;

        $totalPaid = $oldPaid + $newPayment;

        $invoiceTotal = (float) $invoice->after_discount;

        if ($totalPaid > $invoiceTotal) {

            return response()->json([
                'success' => false,
                'message' => 'Payment exceeds invoice total'
            ], 422);
        }

        $remaining = $invoiceTotal - $totalPaid;

        /*
    |--------------------------------------------------------------------------
    | Status Handling
    |--------------------------------------------------------------------------
    */

        if ($totalPaid <= 0) {

            $status = 'pending';
        } elseif ($remaining > 0) {

            $status = 'partial';
        } else {

            $status = 'paid';
            $remaining = 0;
        }

        /*
    |--------------------------------------------------------------------------
    | Update Invoice
    |--------------------------------------------------------------------------
    */

        $invoice->update([
            'paid_amount' => $totalPaid,
            'remaining_amount' => $remaining,
            'status' => $status,
            'paid_at' => $status == 'paid' ? now() : null,
        ]);

        /*
    |--------------------------------------------------------------------------
    | Create Subscription + Invoice Items
    |--------------------------------------------------------------------------
    */

        if (in_array($status, ['paid', 'partial'])) {

            // Get booking
            $booking = Booking::find($invoice->booking_id);

            if ($booking) {

                // All passengers of booking
                $passengers = BookingPassenger::where('booking_id', $booking->id)->get();

                foreach ($passengers as $passenger) {

                    // Get plan
                    $plan = Plan::find($passenger->plan_id);

                    /*
                |--------------------------------------------------------------------------
                | Create Subscription
                |--------------------------------------------------------------------------
                */

                    $subscription = CustomerSubscription::where('booking_id', $booking->id)
                        ->where('passenger_id', $passenger->id)
                        ->first();

                    if (!$subscription) {

                        $subscription = CustomerSubscription::create([

                            'customer_id' => $booking->customer_id,

                            'booking_id' => $booking->id,

                            'passenger_id' => $passenger->id,

                            'plan_id' => $passenger->plan_id,

                            'start_date' => Carbon::today(),

                            'end_date' => Carbon::today()->addDays(30),

                            'price' => $plan->price ?? 0,

                            'payment_type' => $plan->plan_type ?? 'monthly',

                            'status' => 'active',
                        ]);
                    }

                    /*
                |--------------------------------------------------------------------------
                | Create Invoice Item
                |--------------------------------------------------------------------------
                */

                    $invoiceItemExists = CustomerInvoiceItem::where('invoice_id', $invoice->id)
                        ->where('subscription_id', $subscription->id)
                        ->exists();

                    if (!$invoiceItemExists) {

                        CustomerInvoiceItem::create([

                            'invoice_id' => $invoice->id,

                            'subscription_id' => $subscription->id,

                            'amount' => $plan->price ?? 0,
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Invoice updated successfully'
        ]);
    }

    public function list(Request $request)
    {

        $invoices = CustomerInvoice::with(['customer'])->select('customer_invoices.*');
        return DataTables::of($invoices)

            ->addColumn('customer_name', function ($row) {
                return $row->customer->name ?? 'N/A';
            })
            ->addColumn('invoice_date', function ($row) {
                return $row->invoice_for_date ? $row->invoice_for_date->format('d M, Y') : 'N/A';
            })
            ->addColumn('paid_amount', function ($row) {
                return number_format($row->paid_amount, 2);
            })

            ->addColumn('remaining_amount', function ($row) {
                return number_format($row->remaining_amount, 2);
            })
            ->addColumn('total_amount', function ($row) {
                return number_format($row->total_amount, 2);
            })
            ->addColumn('discounted_total', function ($row) {
                return number_format($row->discounted_total, 2);
            })
            ->addColumn('after_discount', function ($row) {
                return number_format($row->after_discount, 2);
            })
            ->addColumn('discount_type', function ($row) {
                return ucfirst($row->discount_type);
            })
            ->addColumn('status', function ($row) {
                return ucfirst($row->status);
            })
            ->addColumn('due_date', function ($row) {
                return $row->due_date ? $row->due_date->format('d M, Y') : 'N/A';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end m-0">
                        <a href="javascript:void(0)" 
                        class="dropdown-item change-status" 
                        data-id="' . $row->id . '"
                       
                        data-status="' . $row->status . '">
                        Change Status
                        </a>
                    </div>
                    ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function index()
    {

        return view('admin.content.pages.invoice.index');
    }
}
