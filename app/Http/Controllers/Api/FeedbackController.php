<?php

namespace App\Http\Controllers\Api;

use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'message' => 'nullable|string',
            'rating' => 'required|numeric|min:0|max:5',
        ]);

        $feedback = new Feedback();
        $feedback->customer_id = $request->user()->id;
        $feedback->message = $validatedData['message'] ?? null;
        $feedback->rating = $validatedData['rating'];
        $feedback->is_approved = 0;
        $feedback->save();

         return response()->json([
            'status'  => true,
            'message' => 'Feedback submitted successfully',
            'data'    => $feedback
        ], 201);

        
    }

    public function approvedFeedback()
{
    
    $feedbacks = Feedback::approved()
        ->with('customer:id,name,email') // Eager load customer with only id, name, and email
        ->get();

    return response()->json([
        'status'  => true,
        'message' => 'Approved feedback retrieved successfully',
        'data'    => $feedbacks
    ], 200);
}
}
