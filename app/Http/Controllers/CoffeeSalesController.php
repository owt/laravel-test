<?php

namespace App\Http\Controllers;

use App\Models\CoffeeSale;
use App\Http\Requests\CoffeeSaleRequest;
use Illuminate\Http\RedirectResponse;

class CoffeeSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all coffee sales for this user
        $coffeeSales = CoffeeSale::where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('coffee_sales', [
            'coffeeSales' => $coffeeSales,
            'currency' => 'GBP',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CoffeeSaleRequest $request): RedirectResponse
    {
        // Create a new coffee sale for this user


        // Return to the coffee sales index page
        return redirect()->route('coffee.sales');

    }   
}
