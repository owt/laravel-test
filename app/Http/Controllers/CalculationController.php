<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Utils\CalculationUtils;

class CalculationController extends Controller
{
    public function calculateSellingPrice(Request $request): JsonResponse
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
