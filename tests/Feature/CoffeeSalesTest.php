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
        
        // Act
        $response = $this->get('/sales');

        // Assert
        $response->assertStatus(302)
            ->assertRedirectToRoute('login');;
    }
}

