<?php

namespace App\Utils;

class CalculationUtils 
{
    public static function calculateCost(int $quantity, float $unitCost): int
    {
        return ceil($quantity * $unitCost);
    }

    public static function calculateSellingPrice(float $cost, float $shippingCost, float $profitMargin): int
    {
        $sellingPrice = ($cost / (1 - $profitMargin)) + $shippingCost;
        return ceil($sellingPrice);
    }
}
