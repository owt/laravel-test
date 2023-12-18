<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Utils\CalculationUtils;
use App\Http\Requests\SellingPriceCalculationRequest;

class CalculationController extends Controller
{
    public function calculateSellingPrice(SellingPriceCalculationRequest $request): JsonResponse
    {
        $quantity = $request->get('quantity');
        $unitCost = $request->get('unit_cost') * 100;
        $shippingCost = config('coffeesales.shipping_cost');
        $profitMargin = config('coffeesales.profit_margin');

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
