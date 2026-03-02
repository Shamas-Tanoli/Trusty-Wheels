<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class mobileAppController extends Controller
{
   public function sliderIndex(){
      return view('admin.content.pages.mobileapp.slider');
   }
}
