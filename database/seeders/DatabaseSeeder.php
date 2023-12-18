<?php

namespace Database\Seeders;

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

        $coffeeSale = CoffeeSale::factory()->create([
            'user_id' => $user->id,
            'quantity' => 2,
            'unit_cost' => 10.00,
            'selling_price' => 23.33,
        ]);
        
    }
}
