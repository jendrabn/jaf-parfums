<?php

namespace Tests\Feature\Api;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Database\Seeders\ProductBrandSeeder;
use Database\Seeders\ProductCategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class OrderGetTest extends TestCase
{
  use RefreshDatabase;

  private string $uri = '/api/orders';
  private User $user;

  protected function setUp(): void
  {
    parent::setUp();
    $this->user = $this->createUser();
  }

  /** @test */
  public function returns_unauthenticated_error_if_user_is_not_authenticated()
  {
    $response = $this->getJson($this->uri, ['Authorization' => 'Bearer Invalid-Token']);

    $response->assertUnauthorized()
      ->assertJsonStructure(['message']);
  }

  /** @test */
  public function can_get_all_orders()
  {
    $this->seed([ProductCategorySeeder::class, ProductBrandSeeder::class]);
    $order = Order::factory()
      ->has(OrderItem::factory()->for($this->createProduct()), 'items')
      ->has(Invoice::factory())
      ->for($this->user)
      ->create();

    $response = $this->attemptToGetOrderAndExpectOk();

    $response->assertJsonPath('data.0', [
      'id' => $order['id'],
      'items' => $order['items']->map(
        fn ($item) => [
          'id' => $item['id'],
          'product' => $this->formatProductData($item['product']),
          'name' => $item['name'],
          'price' => $item['price'],
          'weight' => $item['weight'],
        ]
      )->toArray(),
      'status' => $order['status'],
      'total_amount' => $order['invoice']['amount'],
      'payment_due_date' => $order['invoice']['due_date'],
      'created_at' => $order['created_at']
    ]);
  }

  /** @test */
  public function can_get_all_orders_with_pagination()
  {
    $this->createOrder(['user_id' => $this->user->id], $total = 13);

    $response = $this->attemptToGetOrderAndExpectOk(['page' => $page = 2]);

    $response
      ->assertJsonPath('page', [
        'total' => $total,
        'per_page' => 10,
        'current_page' => $page,
        'last_page' => 2,
        'from' => 11,
        'to' => 13
      ])
      ->assertJsonCount(3, 'data');
  }

  /** @test */
  public function can_get_orders_by_status()
  {
    $statuses = [
      Order::STATUS_PENDING_PAYMENT,
      Order::STATUS_PENDING,
      Order::STATUS_PROCESSING,
      Order::STATUS_ON_DELIVERY,
      Order::STATUS_COMPLETED,
      Order::STATUS_CANCELLED,
    ];
    $orders = [];

    foreach ($statuses as $status) {
      $orders[$status] = $this->createOrder(
        [
          'user_id' => $this->user->id,
          'status' => $status
        ],
        3
      );
    }

    foreach ($statuses as $status) {
      $response = $this->attemptToGetOrderAndExpectOk(compact('status'));

      $response->assertJsonCount(3, 'data')->json('data');
      $this->assertEquals(
        Arr::pluck($orders[$status]->sortByDesc('id'), 'id'),
        Arr::pluck($response, 'id')
      );
    }
  }

  /** @test */
  public function can_sort_order_by_newest()
  {
    $orders = $this->createOrder(['user_id' => $this->user->id], 3);

    $response = $this->attemptToGetOrderAndExpectOk();

    $response->assertJsonCount(3, 'data')->json('data');
    $this->assertEquals(
      Arr::pluck($orders->sortByDesc('id'), 'id'),
      Arr::pluck($response, 'id')
    );
  }

  /** @test */
  public function can_sort_order_by_oldest()
  {
    $orders = $this->createOrder(['user_id' => $this->user->id], 3);

    $response = $this->attemptToGetOrderAndExpectOk();

    $response->assertJsonCount(3, 'data')->json('data');
    $this->assertEquals(
      Arr::pluck($orders->sortBy('id'), 'id'),
      Arr::pluck($response, 'id')
    );
  }

  public function attemptToGetOrderAndExpectOk(?array $params = [])
  {
    $response = $this->getJson(
      $this->uri . '?' . http_build_query($params),
      $this->authBearerToken($this->user)
    );

    $response->assertOk()
      ->assertJsonStructure([
        'data' => [
          '*' => [
            'id',
            'items',
            'status',
            'total_amount',
            'payment_due_date',
            'created_at'
          ]
        ],
        'page' => [
          'total',
          'per_page',
          'current_page',
          'last_page',
          'from',
          'to'
        ]
      ]);

    return $response;
  }
}
