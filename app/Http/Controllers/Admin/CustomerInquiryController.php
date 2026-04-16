<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerInquiry;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerInquiryController extends Controller
{
    public function view()
    {
        return view('admin.content.pages.customer-inquriy.index');
    }

    public function list(Request $request)
    {

        $inquiries = CustomerInquiry::orderBy('created_at', 'desc');

        return DataTables::of($inquiries)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return $row->name ?? 'N/A';
            })

            ->addColumn('contact', function ($row) {
                return $row->contact ?? 'N/A';
            })

            ->addColumn('pickup_address', function ($row) {
                return $row->pickup_address ?? 'N/A';
            })

            ->addColumn('dropoff_address', function ($row) {
                return $row->dropoff_address ?? 'N/A';
            })

          
          

            

            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="ti ti-dots-vertical"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end m-0">
                    <a href="javascript:void(0)" class="dropdown-item view-message" data-id="' . $row->id . '">View</a>
                    
                </div>
            ';
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    public function msg($id)
{
    $inquiry = CustomerInquiry::find($id);

    if ($inquiry) {
        return response()->json([
            'success' => true,
            'message' => $inquiry->message
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Inquiry not found'
    ]);
}
}
