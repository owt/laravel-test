<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Utils\CalculationUtils;
use App\Http\Requests\SellingPriceCalculationRequest;
use App\Models\CoffeeProduct;

class CalculationController extends Controller
{
    public function calculateSellingPrice(SellingPriceCalculationRequest $request): JsonResponse
    {
        $quantity = $request->input('quantity');
        $unitCost = $request->input('unit_cost') * 100;
        $coffeeProductId = $request->input('coffee_product_id');
        $shippingCost = config('coffeesales.shipping_cost');

        // Get the profit margin for this coffee product
        $coffeeProduct = CoffeeProduct::find($coffeeProductId);
        $profitMargin = $coffeeProduct->profit_margin;

        $cost = CalculationUtils::calculateCost($quantity, $unitCost);
        $sellingPrice = CalculationUtils::calculateSellingPrice(
            $cost,
            $shippingCost,
            $profitMargin
        );

        return response()->json([
            'sellingPrice' => $sellingPrice
        ]);
    }
}
