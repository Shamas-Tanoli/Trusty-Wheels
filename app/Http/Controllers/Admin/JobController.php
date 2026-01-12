<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function store(Request $request)
    {
        dd($request->all());
        // Validate the incoming request data
        $validatedData = $request->validate([
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'passenger_ids' => 'required|array',
            'passenger_ids.*' => 'exists:passengers,id',
        ]);

        // Create a new job using the validated data
        $job = new \App\Models\Job();
        $job->title = $validatedData['job_title'];
        $job->description = $validatedData['job_description'];
        $job->save();

        // Attach passengers to the job
        $job->passengers()->attach($validatedData['passenger_ids']);

        // Return a JSON response indicating success
        return response()->json([
            'success' => true,
            'message' => 'Job created successfully!',
        ]);
    }
    public function list()
    {
        return view('admin.content.pages.job.list');
    }


     public function create()
    {
        return view('admin.content.pages.job.add');
    }
}
