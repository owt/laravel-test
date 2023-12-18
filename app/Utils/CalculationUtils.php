<?php

namespace App\Utils;

class CalculationUtils 
{
    public static function calculateCost(int $quantity, int $unitCost): int
    {
        return ceil($quantity * $unitCost);
    }

    public static function calculateSellingPrice(int $cost, int $shippingCost, float $profitMargin): int
    {
        $sellingPrice = ($cost / (1 - $profitMargin)) + $shippingCost;
        return ceil($sellingPrice);
    }
}
