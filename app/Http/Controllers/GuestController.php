<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Index Page
     */
    public function index()
    {
        return inertia('Welcome');
    }

    /**
     * About page
     */
    public function about()
    {
        return inertia('About');
    }

    /**
     * How it works page
     */
    public function howItWorks()
    {
        return inertia('HowItWorks');
    }

    /**
     * Partnerships page
     */
    public function people()
    {
        return inertia('People');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function technology()
    {
        return inertia('Technology');
    }

    /**
     * Update the specified resource in storage.
     */
    public function raffles()
    {
        return inertia('Raffles');
    }


    

}

   
