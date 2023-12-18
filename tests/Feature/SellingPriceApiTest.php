<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SellingPriceApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setup(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

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
        // Act
        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get('/api/sellingprice');

        // Assert
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
        $goldCoffeeProduct = \App\Models\CoffeeProduct::factory()->create([
            'name' => 'Gold Coffee',
            'profit_margin' => 0.25,
        ]);

        // Act
        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->get(sprintf('/api/sellingprice?coffee_product_id=%s&quantity=1&unit_cost=10', $goldCoffeeProduct->id));

        // Assert
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
        $arabicCoffeeProduct = \App\Models\CoffeeProduct::factory()->create([
            'name' => 'Arabic Coffee',
            'profit_margin' => 0.15,
        ]);

        // Act
        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->get(sprintf('/api/sellingprice?coffee_product_id=%s&quantity=1&unit_cost=10', $arabicCoffeeProduct->id));

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['sellingPrice' => 2177]);
    }
}
