<?php

namespace App\Utils;

class CalculationUtils 
{
    public static function calculateCost(int $quantity, float $unitCost): float
    {
        return \round($quantity * $unitCost, 2, PHP_ROUND_HALF_UP);
    }

    public static function calculateSellingPrice(float $cost, float $shippingCost, float $profitMargin): float
    {
        $sellingPrice = ($cost / (1 - $profitMargin)) + $shippingCost;
        return \round($sellingPrice, 2, PHP_ROUND_HALF_UP);
    }
}
