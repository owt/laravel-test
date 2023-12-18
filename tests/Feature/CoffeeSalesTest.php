<?php

namespace Tests\Feature;

use App\Models\CoffeeProduct;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoffeeSalesTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected CoffeeProduct $goldCoffeeProduct;
    protected CoffeeProduct $arabicCoffeeProduct;

    public function setup(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        // Create a coffee product
        $this->goldCoffeeProduct = CoffeeProduct::factory()->create([
            'name' => 'Gold Coffee',
            'profit_margin' => 0.25,
        ]);

        $this->arabicCoffeeProduct = CoffeeProduct::factory()->create([
            'name' => 'Arabic Coffee',
            'profit_margin' => 0.15,
        ]);
    }

    /**
     * Test the coffee sales page renders
     *
     * @return void
     */
    public function testCoffeeSalesRenders()
    {
        //Arrange        
        // Act
        $response = $this->actingAs($this->user)
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

    public function testCoffeeSaleUnitCostValidation()
    {
        // Arrange
        $data = [
            'coffee_product_id' => $this->goldCoffeeProduct->id, // 'Gold Coffee
            'quantity' => 2,
            'unit_cost' => null,
        ];

        // Act
        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->post('/sales', $data);

        // Assert
        $response->assertSessionHasErrors([
            'unit_cost' => 'The unit cost field is required.',
        ]);
        $response->assertStatus(302);
    }

    public function testCoffeeSaleProductIdValidation()
    {
        // Arrange
        $data = [
            'coffee_product_id' => null,
            'quantity' => 1,
            'unit_cost' => 10,
        ];

        // Act
        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->post('/sales', $data);

        // Assert
        $response->assertSessionHasErrors([
            'coffee_product_id' => 'The coffee product id field is required.',
        ]);
        $response->assertStatus(302);
    }

    public function testCoffeeSaleQuantityValidation()
    {
        // Arrange
        $data = [
            'coffee_product_id' => $this->goldCoffeeProduct->id, // 'Gold Coffee
            'quantity' => null,
            'unit_cost' => 10,
        ];

        // Act
        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->post('/sales', $data);

        // Assert
        $response->assertSessionHasErrors([
            'quantity' => 'The quantity field is required.',
        ]);
        $response->assertStatus(302);
    }

    public function testCoffeeSaleValidation()
    {
        // Arrange
        $data = [
            'coffee_product_id' => null,
            'quantity' => null,
            'unit_cost' => null,
        ];

        // Act
        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->post('/sales', $data);

        // Assert
        $response->assertSessionHasErrors([
            'coffee_product_id' => 'The coffee product id field is required.',
            'quantity' => 'The quantity field is required.',
            'unit_cost' => 'The unit cost field is required.',
        ]);
        $response->assertStatus(302);
    }

    public function testCoffeeSaleCanBeRecorded()
    {
        // Arrange
        $data = [
            'coffee_product_id' => $this->goldCoffeeProduct->id, // 'Gold Coffee
            'quantity' => 1,
            'unit_cost' => 10.00,
        ];

        // Act
        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->post('/sales', $data);

        // Assert
        // Test that we have coffee in the db
        $this->assertDatabaseCount('coffee_sales', 1);
        $this->assertDatabaseHas('coffee_sales', [
            'coffee_product_id' => $this->goldCoffeeProduct->id,
            'quantity' => '1',
            'unit_cost' => '1000',
            'selling_price' => '2334',
        ]);


        $response->assertStatus(302)
            ->assertRedirectToRoute('coffee.sales');
    }
}

