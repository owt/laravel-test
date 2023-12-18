<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SellingPriceApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the selling price api renders
     *
     * @return void
     */
    public function testSellingPriceApiRenders()
    {
        //Arrange
        $user = User::factory()->create();
        
        // Act
        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/api/sellingprice?quantity=1&unit_cost=10');

        // Assert
        $response->assertStatus(200);
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
        $response = $this->get('/api/sellingprice?quantity=1&unit_cost=10');

        // Assert
        $response->assertStatus(302)
            ->assertRedirectToRoute('login');;
    }

    /**
     * Test the selling price api calculates the selling price
     *
     * @return void
     */
    public function testSellingPriceCanBeCalcuated()
    {
        // Arrange
        $user = User::factory()->create();
        $data = [
            'quantity' => 1,
            'unit_cost' => 10.00,
        ];

        // Act
        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/api/sellingprice?quantity=1&unit_cost=10');

        // Assert
        // Test that we have coffee in the db
        $response->assertStatus(200);
        $response->assertJson(['sellingPrice' => 2334]);
    }
}
