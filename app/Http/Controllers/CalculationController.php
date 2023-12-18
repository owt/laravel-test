<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Utils\CalculationUtils;

class CalculationController extends Controller
{
    const SHIPPING_COST = 10.00;
    const PROFIT_MARGIN = 0.25;
    
    public function calculateSellingPrice(Request $request): JsonResponse
    {
        $quantity = (float) $request->get('quantity');
        $unitCost = (float) $request->get('unitcost');

        $cost = CalculationUtils::calculateCost($quantity, $unitCost);
        $sellingPrice = CalculationUtils::calculateSellingPrice(
            $cost,
            self::SHIPPING_COST,
            self::PROFIT_MARGIN
        );

        return response()->json([
            'sellingPrice' => $sellingPrice
        ]);
    }
}
