<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \App\Models\Category::factory(5)->create();
    }

    public function test_create_order_from_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'price' => 50]);

        $user->carts()->create([
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        $service = new OrderService();
        $order = $service->createOrderFromCart($user);

        $this->assertNotNull($order);
        $this->assertEquals(100, $order->total_amount);
        $this->assertDatabaseMissing('carts', ['user_id' => $user->id]);
        $this->assertEquals(8, $product->fresh()->stock);
    }
}
