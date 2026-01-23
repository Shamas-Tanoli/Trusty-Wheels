<?php

namespace App\Http\Controllers\Admin;

use App\Models\PromoCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PromoCodeController extends Controller
{
 public function edit($id)
{
    $promo = PromoCode::find($id);

    if (!$promo) {
        return response()->json(['status' => 'error', 'message' => 'Promo code not found'], 404);
    }

     $data = [
        'id' => $promo->id,
        'code' => $promo->code,
        'type' => $promo->type,
        'value' => $promo->value,
        'start_date' => $promo->start_date ? \Carbon\Carbon::parse($promo->start_date)->format('Y-m-d') : null,
        'end_date' => $promo->end_date ? \Carbon\Carbon::parse($promo->end_date)->format('Y-m-d') : null,
        'usage_limit' => $promo->usage_limit,
        'is_active' => $promo->is_active ? 'active' : 'inactive'
    ];

    return response()->json([
        'status' => 'success',
        'data' => $data
    ]);
}





    public function list()
    {
        $promoCodes = PromoCode::query();

        return DataTables::of($promoCodes)
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->editColumn('start_date', function ($row) {
                return \Carbon\Carbon::parse($row->start_date)->format('d M Y');
            })
            ->editColumn('end_date', function ($row) {
                return \Carbon\Carbon::parse($row->end_date)->format('d M Y');
            })
            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-sm btn-primary edit-btn" data-id="' . $row->id . '">Edit</button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</button>
            ';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.content.pages.promo.add');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|unique:promo_codes,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'required|in:active,inactive'
        ]);

        $validatedData['used_count'] = 0;
        $validatedData['is_active'] =  $validatedData['is_active'] ? 1 : 0;

        $PromoCode = PromoCode::create($validatedData);



        return response()->json([
            'success' => true,
            'message' => 'Promo Code Added Succesfully'
        ]);
    }

    public function destroy($id)
    {
        $plan = PromoCode::findOrFail($id);
        $plan->delete();

        return response()->json(['status' => 'success', 'message' => 'Promo code deleted successfully.']);
    }

    public function update(Request $request){
        dd($request->all());
    }
}
