<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SellingPriceApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the selling price api redirects with no user
     *
     * @return void
     */
    public function testSellingPriceApiRedirectsWithNoUser()
    {
        // Arrange
        // Act
        $response = $this->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get('/api/sellingprice?quantity=1&unit_cost=10');

        // Assert
        $response->assertStatus(401);
    }

    /**
     * Test the selling price api validation works
     *
     * @return void
     */
    public function testSellingPriceValidation()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get('/api/sellingprice');

        // Assert
        // Test that we have coffee in the db
        $response->assertStatus(422);
    }

    /**
     * Test the selling price api calculates the selling price
     *
     * @return void
     */
    public function testGoldCoffeeSellingPriceCanBeCalcuated()
    {
        // Arrange
        $user = User::factory()->create();

        $goldCoffeeProduct = \App\Models\CoffeeProduct::factory()->create([
            'name' => 'Gold Coffee',
            'profit_margin' => 0.25,
        ]);

        //dd($goldCoffeeProduct->id);

        // Act
        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get(sprintf('/api/sellingprice?coffee_product_id=%s&quantity=1&unit_cost=10', $goldCoffeeProduct->id));

        // Assert
        // Test that we have coffee in the db
        $response->assertStatus(200);
        $response->assertJson(['sellingPrice' => 2334]);
    }

    /**
     * Test the selling price api calculates the selling price
     *
     * @return void
     */
    public function testArabicCoffeeSellingPriceCanBeCalcuated()
    {
        // Arrange
        $user = User::factory()->create();

        $arabicCoffeeProduct = \App\Models\CoffeeProduct::factory()->create([
            'name' => 'Arabic Coffee',
            'profit_margin' => 0.15,
        ]);

        //dd($goldCoffeeProduct->id);

        // Act
        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get(sprintf('/api/sellingprice?coffee_product_id=%s&quantity=1&unit_cost=10', $arabicCoffeeProduct->id));

        // Assert
        // Test that we have coffee in the db
        $response->assertStatus(200);
        $response->assertJson(['sellingPrice' => 2177]);
    }
}
