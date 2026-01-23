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
