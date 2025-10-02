<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(9)->create([
            'role' => 'customer'
        ]);
        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'role' => 'admin'
        ]);
        User::factory()->create([
            'name' => 'Test Admin2',
            'email' => 'admin2@test.com',
            'role' => 'admin'
        ]);
        User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'role' => 'customer'
        ]);

        $categories = \App\Models\Category::factory(5)->create();
        $products = \App\Models\Product::factory(20)->create([
            'category_id' => $categories->random()->id,
        ]);
        \App\Models\Cart::factory(10)->create([
            'user_id' => $users->random()->id,
            'product_id' => $products->random()->id,
        ]);
        $orders = \App\Models\Order::factory(15)->create([
            'user_id' => $users->random()->id,
        ]);

    }
}
