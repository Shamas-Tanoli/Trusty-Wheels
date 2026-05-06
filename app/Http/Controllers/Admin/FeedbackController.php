<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Review;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FeedbackController extends Controller
{
    public function view()
    {
        return view('admin.content.pages.feedback.index');
    }

    public function list(Request $request)
    {
        $feedbacks = Feedback::with('customer')
            ->orderBy('created_at', 'desc');

        return DataTables::of($feedbacks)
            ->addIndexColumn()

            ->addColumn('customer_name', function ($row) {
                return $row->customer->name ?? 'N/A';
            })

            ->addColumn('message', function ($row) {
                return $row->message ?? 'N/A';
            })

            ->addColumn('rating', function ($row) {
                return $row->rating ?? 'N/A';
            })

            ->addColumn('status', function ($row) {
                return $row->is_approved 
                    ? '<span class="badge bg-success">Approved</span>' 
                    : '<span class="badge bg-warning">Pending</span>';
            })

            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="ti ti-dots-vertical"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end m-0">
                    <a href="javascript:void(0)" class="dropdown-item approve-feedback" data-id="' . $row->id . '">Approve</a>
                    <a href="javascript:void(0)" class="dropdown-item  delete-feedback" data-id="' . $row->id . '">Delete</a>
                     </div>
                ';
            })

            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function approve($id)
    {
        $feedback = Feedback::with('customer')->find($id);

        if (!$feedback) {

            return response()->json([
                'success' => false,
                'message' => 'Feedback not found'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE FEEDBACK STATUS
        |--------------------------------------------------------------------------
        */

        $feedback->update([
            'is_approved' => 1
        ]);


       
       
        $dummyImage = 'assets/img/feedbackdummy.png';

        /*
        |--------------------------------------------------------------------------
        | STORE IN REVIEWS TABLE
        |--------------------------------------------------------------------------
        */

        Review::create([
            'title'      => $feedback->customer->name ?? 'Anonymous',
            'sub_title'  => $feedback->message,
            'image_path' => $dummyImage,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback approved successfully'
        ]);
    }

    public function destroy($id)
{
    $feedback = Feedback::find($id);

    if (!$feedback) {

        return response()->json([
            'success' => false,
            'message' => 'Feedback not found'
        ]);
    }

    $feedback->delete();

    return response()->json([
        'success' => true,
        'message' => 'Feedback deleted successfully'
    ]);
}

}
