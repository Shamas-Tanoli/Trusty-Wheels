<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerInquiry;

class DashboardController extends Controller
{
  public function index()
  {
    $latestInquiries = CustomerInquiry::latest()->take(5)->get();

    return view('admin.content.pages.pages-home', compact('latestInquiries'));
  }
}
