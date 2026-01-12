<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function list()
    {
        return view('admin.content.pages.job.list');
    }


     public function create()
    {
        return view('admin.content.pages.job.add');
    }
}
