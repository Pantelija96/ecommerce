<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \App\Models\Category::factory(5)->create();
    }

    public function test_customer_can_add_to_cart()
    {
        $user = User::factory()->create(['role' => 'customer']);
        $product = Product::factory()->create(['stock' => 12]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/cart', [
                'product_id' => $product->id,
                'quantity' => 5
            ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);
        $this->assertDatabaseHas('carts', ['user_id' => $user->id, 'product_id' => $product->id]);
    }
}
