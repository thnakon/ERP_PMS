<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PeoplesController extends Controller
{
   public function patientscustomer()
    {
        return view('peoples.patients-customer',);
    }

    public function staffuser()
    {
        return view('peoples.staff-user',);
    }

    public function recent()
    {
        return view('peoples.recent',);
    }
}
