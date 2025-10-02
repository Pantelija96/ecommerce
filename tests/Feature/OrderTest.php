<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \App\Models\Category::factory(5)->create();
    }

    public function test_customer_can_place_order()
    {
        $user = User::factory()->create(['role' => 'customer']);
        $product = Product::factory()->create(['stock' => 15]);

        $this->actingAs($user, 'sanctum')->postJson('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 3
        ]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/orders');

        $response->assertStatus(201)->assertJson(['success' => true]);
        $this->assertDatabaseHas('orders', ['user_id' => $user->id]);
    }
}
