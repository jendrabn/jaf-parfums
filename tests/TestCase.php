<?php

namespace Tests;

use App\Models\{
    Bank,
    Banner,
    Cart,
    City,
    Order,
    OrderItem,
    Product,
    ProductBrand,
    ProductCategory,
    Province,
    Shipping,
    User,
    UserAddress
};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use JMac\Testing\Traits\AdditionalAssertions;
use Symfony\Component\ErrorHandler\ErrorHandler;

abstract class TestCase extends BaseTestCase
{
    use AdditionalAssertions;

    protected function setUp(): void
    {
        parent::setUp();
        // $this->fakeHttpRajaOngkir();
    }

    // protected function tearDown(): void
    // {
    //     parent::tearDown();
    //     // Banner::all()->each(fn ($banner) => $banner->clearMediaCollection(Banner::MEDIA_COLLECTION_NAME));
    //     // Product::all()->each(fn ($product) => $product->clearMediaCollection(Product::MEDIA_COLLECTION_NAME));
    //     // Bank::all()->each(fn ($bank) => $bank->clearMediaCollection(Bank::MEDIA_COLLECTION_NAME));
    // }

    protected function createUser(?array $data = [], int $count = 1): User|Collection
    {
        $users = User::factory()->count($count)->create($data);

        return $count > 1 ? $users : $users->first();
    }

    protected function authBearerToken(User $user, bool $isHeader = true): array|string
    {
        $token = $user->createToken('auth_token')->plainTextToken;

        return $isHeader ? ['Authorization' => 'Bearer ' . $token] : $token;
    }

    protected function createProduct(?array $data = [], int $count = 1): Product|Collection
    {
        $products = Product::factory()->count($count)->create($data);

        return $count > 1 ? $products : $products->first();
    }

    protected function createCategory(?array $data = [], int $count = 1): ProductCategory|Collection
    {
        $categories = ProductCategory::factory()->count($count)->create($data);

        return $count > 1 ? $categories : $categories->first();
    }

    protected function createBrand(?array $data = [], int $count = 1): ProductBrand|Collection
    {
        $brands = ProductBrand::factory()->count($count)->create($data);

        return $count > 1 ? $brands : $brands->first();
    }

    public function createOrder(?array $data = [], ?int $count = 1)
    {
        $orders = Order::factory()
            ->count($count)
            ->for(User::factory()->create())
            ->create($data);

        return $count > 1 ? $orders : $orders->first();
    }

    protected function formatUserData(User $data): array
    {
        return [
            'id' => $data['id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'sex' => $data['sex'],
            'birth_date' => $data['birth_date'],
        ];
    }

    protected function formatCityData(City|Collection $data): array
    {
        return $data instanceof Collection
            ? $data->map(fn($data) => $this->formatCityData($data))->values()->toArray()
            : [
                'id' => $data['id'],
                'type' => $data['type'],
                'name' => $data['name'],
            ];
    }

    protected function formatProvinceData(Province|Collection $data): array
    {
        return $data instanceof Collection
            ? $data->map(fn($data) => $this->formatProvinceData($data))->values()->toArray()
            : [
                'id' => $data['id'],
                'name' => $data['name'],
            ];
    }

    protected function formatCategoryData(ProductCategory|Collection $data): array
    {
        return $data instanceof Collection
            ? $data->map(fn($data) => $this->formatCategoryData($data))->values()->toArray()
            : [
                'id' => $data['id'],
                'name' => $data['name'],
                'slug' => $data['slug'],
            ];
    }

    protected function formatBrandData(ProductBrand|Collection $data): array
    {
        return $data instanceof Collection
            ? $data->map(fn($data) => $this->formatBrandData($data))->values()->toArray()
            : [
                'id' => $data['id'],
                'name' => $data['name'],
                'slug' => $data['slug'],
            ];
    }

    protected function formatCartData(Cart|Collection $data): array
    {
        return $data instanceof Collection
            ? $data->map(fn($data) => $this->formatCartData($data))->values()->toArray()
            : [
                'id' => $data['id'],
                'product' => $this->formatProductData($data['product']),
                'quantity' => $data['quantity'],
            ];
    }

    protected function formatBankData(Bank|Collection $data): array
    {
        return $data instanceof Collection
            ? $data->map(fn($data) => $this->formatBankData($data))->values()->toArray()
            : [
                'id' => $data['id'],
                'name' => $data['name'],
                'code' => $data['code'],
                'account_name' => $data['account_name'],
                'account_number' => $data['account_number'],
                'logo' => $data['logo'] ? $data['logo']->getUrl() : null
            ];
    }

    protected function formatUserAddressData(UserAddress $data): array
    {
        return [
            'id' => $data['id'],
            'name' => $data['name'],
            'phone' => $data['phone'],
            'province' => $this->formatProvinceData($data['city']['province']),
            'city' => $this->formatCityData($data['city']),
            'district' => $data['district'],
            'postal_code' => $data['postal_code'],
            'address' => $data['address'],
        ];
    }

    protected function formatProductData(Product|Collection $data): array
    {
        return $data instanceof Collection
            ? $data->map(fn($data) => $this->formatProductData($data))->values()->toArray()
            : [
                'id' => $data['id'],
                'name' => $data['name'],
                'slug' => $data['slug'],
                'image' => $data['image'] ? $data['image']->getUrl() : null,
                'category' => $this->formatCategoryData($data['category']),
                'brand' => $this->formatBrandData($data['brand']),
                'sex' => $data['sex'],
                'price' => $data['price'],
                'stock' => $data['stock'],
                'weight' => $data['weight'],
                'sold_count' => $data['sold_count'] ?? 0,
                'is_wishlist' => $data['is_wishlist'] ?? false,
            ];
    }

    protected function createProductWithSales(
        ?array $data = [],
        ?array $quantities = [1],
        ?string $status = Order::STATUS_COMPLETED
    ): Product {
        $sequence = [];
        foreach ($quantities as $quantity) {
            $sequence[] = [
                'quantity' => $quantity,
                'order_id' => Order::factory()->for(User::factory()->create())
                    ->create(compact('status'))->id
            ];
        }

        return Product::factory()
            ->has(OrderItem::factory(count($sequence))->sequence(...$sequence))
            ->create($data);
    }

    public function fakeHttpRajaOngkir(): void
    {
        $url = config('shop.rajaongkir.base_url') . '/cost';
        // dd($url);

        Http::fake([
            $url => function (Request $request) {
                if ($request->method() === 'POST') {

                    foreach (Shipping::COURIERS as $courier) {

                        if ($request->data()['courier'] === $courier) {
                            $file = file_get_contents(
                                base_path('tests/fixtures/rajaongkir/' . $courier . '.json')
                            );

                            return Http::response($file);
                        }
                    }
                }
            }
        ]);
    }
}
