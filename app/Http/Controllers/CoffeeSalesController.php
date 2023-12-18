<?php

namespace App\Http\Controllers;

use App\Models\CoffeeSale;
use App\Http\Requests\CoffeeSaleRequest;
use Illuminate\Http\RedirectResponse;
use App\Utils\CalculationUtils;
use Illuminate\Support\MessageBag;

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
        $quantity = $request->quantity;
        $unitCost = $request->unit_cost; // Number input as decimal
        $userId = auth()->user()->id;
        $shippingCost = config('coffeesales.shipping_cost');
        $profitMargin = config('coffeesales.profit_margin');

        // Calculate the selling price
        $cost = CalculationUtils::calculateCost($quantity, $unitCost);
        $sellingPrice = CalculationUtils::calculateSellingPrice($cost, $shippingCost, $profitMargin);
        
        // Create a new coffee sale for this user
        try {
            CoffeeSale::create([
                'user_id' => $userId,
                'quantity' => $quantity,
                'unit_cost' => $unitCost * 100,
                'selling_price' => $sellingPrice,
            ]);

            session()->flash('success', 'Coffee sale saved successfully.');
        } catch (\Exception $e) {
            $errorBag = new MessageBag();
            $errorBag->add('error', 'There was an error saving the coffee sale.');
            session()->flash('errors', $errorBag);
        }

        // Return to the coffee sales index page
        return redirect()->route('coffee.sales');

    }   
}
