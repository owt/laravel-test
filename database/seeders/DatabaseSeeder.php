<?php

namespace Database\Seeders;

use App\Models\CoffeeProduct;
use App\Models\User;
use App\Models\CoffeeSale;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'name' => 'Sales Agent',
            'email' => 'sales@coffee.shop',
        ]);

        $goldCoffee = CoffeeProduct::factory()->create([
            'name' => 'Gold Coffee',
            'profit_margin' => 0.25,
        ]);

        $arabicCoffee = CoffeeProduct::factory()->create([
            'name' => 'Arabic Coffee',
            'profit_margin' => 0.15,
        ]);

        CoffeeSale::factory()->create([
            'user_id' => $user->id,
            'coffee_product_id' => $goldCoffee->id,
        ]);

        CoffeeSale::factory()->create([
            'user_id' => $user->id,
            'coffee_product_id' => $arabicCoffee->id,
            'quantity' => 1,
            'unit_cost' => 1000,
            'selling_price' => 2177,
        ]);
    }
}
