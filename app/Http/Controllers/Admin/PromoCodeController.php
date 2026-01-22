<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
   public function create()
   {
         return view('admin.content.pages.promo.add');
   }

    public function store(Request $request)
    {
        dd($request->all());
        



         $validatedData = $request->validate([
              'code' => 'required|string|unique:promo_codes,code',
              'type' => 'required|in:percentage,fixed',
              'value' => 'required|numeric|min:0',
              'start_date' => 'required|date',
              'end_date' => 'required|date|after_or_equal:start_date',
              'usage_limit' => 'nullable|integer|min:1',
         ]);
    
         $validatedData['used_count'] = 0;
         $validatedData['is_active'] = true;
    
         \App\Models\PromoCode::create($validatedData);
    
         return redirect()->route('promo.show')->with('success', 'Promo code created successfully.');
    }
}
