<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Utils\CalculationUtils;

class CalculationUtilsTest extends TestCase
{
    /**
     * Test the cost calculation
     *
     * @return void
     */
    public function testCostCalculation()
    {
        // Arrange
        $quantity = 2;
        $unitCost = 10.00;

        // Act
        $cost = CalculationUtils::calculateCost($quantity, $unitCost);

        // Assert
        $this->assertEquals(20.00, $cost);
    }

    /**
     * Test the selling price calculation
     *
     * @return void
     */
    public function testSellingPriceCalculation()
    {
        // Arrange
        $shippingCost = 10.00;
        $profitMargin = 0.25;

        $cost1 = CalculationUtils::calculateCost(1, 10.00);
        $cost2 = CalculationUtils::calculateCost(2, 20.50);
        $cost3 = CalculationUtils::calculateCost(5, 12.00);

        // Act
        $sellingPrice1 = CalculationUtils::calculateSellingPrice($cost1, $shippingCost, $profitMargin);
        $sellingPrice2 = CalculationUtils::calculateSellingPrice($cost2, $shippingCost, $profitMargin);
        $sellingPrice3 = CalculationUtils::calculateSellingPrice($cost3, $shippingCost, $profitMargin);

        // Assert
        $this->assertEquals(23.33, $sellingPrice1);
        $this->assertEquals(64.67, $sellingPrice2);
        $this->assertEquals(90.00, $sellingPrice3);
    }
}
