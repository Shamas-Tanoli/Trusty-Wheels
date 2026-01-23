<?php

namespace App\Http\Controllers\Admin;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DiscountController extends Controller
{
    public function index()
    {
        return view('admin.content.pages.discount.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => [
                'required',
                Rule::in([Discount::TYPE_PERCENTAGE, Discount::TYPE_FIXED]),
            ],
            'value' => 'required|numeric|min:1',
            'person' => 'required|integer|min:1',
            'is_active' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],
        ]);

        if ($validated['type'] === Discount::TYPE_PERCENTAGE && $validated['value'] > 100) 
        {
            return response()->json([
                'status' => false,
                'message' => 'Percentage discount 100% se zyada nahi ho sakta',
            ], 422);
        }

        DB::beginTransaction();

        try {
            $discount = Discount::create($validated);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Discount successfully created',
                'data' => $discount,
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
