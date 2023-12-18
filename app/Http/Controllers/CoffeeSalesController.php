<?php

namespace App\Http\Controllers;

use App\Models\CoffeeSale;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CoffeeSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('coffee_sales');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate with the request

        // Create a new coffee sale for this user


        // Return to the coffee sales index page
        return redirect()->route('coffee.sales');

    }   
}
