<?php

namespace App\Http\Controllers;

use App\Models\CoffeeSale;
use App\Models\CoffeeProduct;
use App\Http\Requests\CoffeeSaleRequest;
use Illuminate\Http\RedirectResponse;
use App\Utils\CalculationUtils;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;

class CoffeeSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // Get all coffee products
        $coffeeProducts = CoffeeProduct::all();

        // Get all coffee sales for this user
        $coffeeSales = CoffeeSale::where('user_id', auth()->user()->id)
            ->join('coffee_products', 'coffee_sales.coffee_product_id', '=', 'coffee_products.id')
            ->orderBy('coffee_sales.created_at', 'desc')
            ->get([
                'coffee_products.name',
                'coffee_sales.quantity',
                'coffee_sales.unit_cost',
                'coffee_sales.selling_price',
                'coffee_sales.created_at'
            ]);

        return view('coffee_sales', [
            'coffeeProducts' => $coffeeProducts,
            'coffeeSales' => $coffeeSales,
            'currency' => 'GBP',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CoffeeSaleRequest $request): RedirectResponse
    {
        $quantity = $request->input('quantity');
        $unitCost = $request->input('unit_cost') * 100; // Number input as decimal
        $coffeeProductId = $request->input('coffee_product_id');
        $userId = auth()->user()->id;
        $shippingCost = config('coffeesales.shipping_cost');

        // Get the profit margin for this coffee product
        $coffeeProduct = CoffeeProduct::find($coffeeProductId);
        $profitMargin = $coffeeProduct->profit_margin;

        // Calculate the selling price
        $cost = CalculationUtils::calculateCost($quantity, $unitCost);
        $sellingPrice = CalculationUtils::calculateSellingPrice($cost, $shippingCost, $profitMargin);

        // Create a new coffee sale for this user
        try {
            CoffeeSale::create([
                'user_id' => $userId,
                'coffee_product_id' => $coffeeProduct->id,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'selling_price' => $sellingPrice,
                'profit_margin' => $profitMargin,
            ]);

            session()->flash('success', 'Coffee sale saved successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $errorBag = new MessageBag();
            $errorBag->add('error', 'There was an error saving the coffee sale.');
            session()->flash('errors', $errorBag);
        }

        // Return to the coffee sales index page
        return redirect()->route('coffee.sales');
    }   
}
