<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoffeeSalesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the coffee sales page renders
     *
     * @return void
     */
    public function testCoffeeSalesRenders()
    {
        //Arrange
        $user = User::factory()->create();
        
        // Act
        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/sales');

        // Assert
        $response->assertStatus(200);
    }

    public function testCoffeeSalesRedirectsWithNoUser()
    {
        // Arrange
        // Act
        $response = $this->get('/sales');

        // Assert
        $response->assertStatus(302)
            ->assertRedirectToRoute('login');;
    }

    public function testCoffeeSaleCanBeRecorded()
    {
        // Arrange
        $user = User::factory()->create();
        $data = [
            'quantity' => 2,
            'unit_cost' => 10.00,
        ];

        // Act
        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->post('/sales', $data);

        // Assert
        // Test that we have coffee in the db
        $this->assertDatabaseCount('coffee_sales', 1);
        $this->assertDatabaseHas('coffee_sales', [
            'quantity' => '2',
            'unit_cost' => '1000',
        ]);


        $response->assertStatus(302)
            ->assertRedirectToRoute('coffee.sales');
    }
}

