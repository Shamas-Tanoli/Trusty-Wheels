<?php

namespace App\Http\Controllers\Admin;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class DiscountController extends Controller
{
    public function list()
    {
        $promoCodes = Discount::query();

        return DataTables::of($promoCodes)
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
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


    public function index()
    {
        return view('admin.content.pages.discount.index');
    }


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => [
                    'required',
                    Rule::in([Discount::TYPE_PERCENTAGE, Discount::TYPE_FIXED]),
                ],
                'value' => 'required|numeric|min:1',
                'person' => 'required|integer|min:1|unique:discount,person',
                'is_active' => [
                    'required',
                    Rule::in(['active', 'inactive']),
                ],
            ]);

            
            if (
                $validated['type'] === Discount::TYPE_PERCENTAGE &&
                $validated['value'] > 100
            ) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'value' => ['Percentage discount 100% se zyada nahi ho sakta']
                    ],
                ], 422);
            }

           
            $validated['is_active'] = $validated['is_active'] === 'active' ? 1 : 0;

            Discount::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Discount successfully created',
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'errors' => [
                    'server' => ['Something went wrong. Please try again.']
                ],
            ], 500);
        }
    }
}
