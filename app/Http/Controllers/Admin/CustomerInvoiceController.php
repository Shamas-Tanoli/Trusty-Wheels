<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerInvoice;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerInvoiceController extends Controller
{
    public function list(Request $request)
    {

        $invoices = CustomerInvoice::with(['customer']);
        return DataTables::of($invoices)

            ->addColumn('customer_name', function ($row) {
                return $row->customer->name ?? 'N/A';
            })
            ->addColumn('invoice_date', function ($row) {
                return $row->invoice_for_date ? $row->invoice_for_date->format('d M, Y') : 'N/A';
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
                        data-ida="' . $row->id . '" 
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
