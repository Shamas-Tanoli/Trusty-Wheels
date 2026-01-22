<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
   public function create()
   {
    return "shamas";
         return view('admin.content.pages.promo.add');
   }
}
