<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the public landing page.
     */
    public function index()
    {
        return view('landing');
    }

    /**
     * Display the terms of service page.
     */
    public function terms()
    {
        return view('policy.terms');
    }

    /**
     * Display the privacy policy page.
     */
    public function privacy()
    {
        return view('policy.privacy');
    }
}
