<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::query()->where('role', 'customer')->get();
        if ($customers->isEmpty()) {
            return;
        }

        $variants = ProductVariant::query()
            ->whereHas('product', fn ($q) => $q->where('status', 'active'))
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->with('product')
            ->get();

        if ($variants->isEmpty()) {
            return;
        }

        $statuses = [
            ['status' => 'delivered', 'payment_status' => 'paid', 'daysAgo' => 34],
            ['status' => 'shipped', 'payment_status' => 'paid', 'daysAgo' => 12],
            ['status' => 'processing', 'payment_status' => 'paid', 'daysAgo' => 3],
            ['status' => 'pending', 'payment_status' => 'unpaid', 'daysAgo' => 1],
            ['status' => 'confirmed', 'payment_status' => 'paid', 'daysAgo' => 5],
        ];

        foreach ($customers as $customer) {
            foreach (array_slice($statuses, 0, random_int(2, 3)) as $spec) {
                $this->createOrder($customer, $variants, $spec['status'], $spec['payment_status'], $spec['daysAgo']);
            }
        }

        $this->seedCart($customers->first());
    }

    private function createOrder(User $customer, $variants, string $status, string $paymentStatus, int $daysAgo): void
    {
        $items = $variants->random(random_int(1, 3));
        $itemRows = [];
        $subtotal = 0;

        foreach ($items as $variant) {
            $qty = random_int(1, 2);
            $price = (float) ($variant->price ?: $variant->product->price);
            $lineTotal = $price * $qty;
            $subtotal += $lineTotal;
            $itemRows[] = [
                'product_id' => $variant->product_id,
                'variant_id' => $variant->id,
                'product_name' => $variant->product->name,
                'sku' => $variant->sku,
                'size' => $variant->size,
                'colour' => $variant->colour,
                'quantity' => $qty,
                'unit_price' => $price,
                'line_total' => $lineTotal,
            ];
        }

        $shipping = $subtotal >= 100 ? 0 : 3.95;
        $total = $subtotal + $shipping;
        $placedAt = now()->subDays($daysAgo);

        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'HIM-' . now()->format('y') . strtoupper(str()->random(8)),
            'status' => $status,
            'payment_status' => $paymentStatus,
            'subtotal' => $subtotal,
            'discount_total' => 0,
            'shipping_total' => $shipping,
            'tax_total' => 0,
            'total' => $total,
            'currency' => 'GBP',
            'customer_email' => $customer->email,
            'customer_phone' => $customer->phone,
            'shipping_method' => $shipping === 0 ? 'uk_standard' : 'uk_standard',
            'placed_at' => $placedAt,
            'created_at' => $placedAt,
            'updated_at' => $placedAt,
        ]);

        foreach ($itemRows as $row) {
            OrderItem::create(['order_id' => $order->id] + $row);
        }

        OrderAddress::create([
            'order_id' => $order->id,
            'type' => 'shipping',
            'name' => $customer->name,
            'line_one' => '12 Example Street',
            'city' => 'London',
            'county' => 'Greater London',
            'postcode' => 'SW1A 1AA',
            'country' => 'United Kingdom',
            'phone' => $customer->phone,
        ]);
    }

    private function seedCart(User $customer): void
    {
        $cart = Cart::query()->where('user_id', $customer->id)->first();
        if (! $cart) {
            $cart = Cart::create(['user_id' => $customer->id, 'session_id' => 'seed-' . $customer->id]);
        }

        $variant = ProductVariant::query()
            ->where('is_active', true)
            ->where('stock', '>', 2)
            ->with('product')
            ->first();
        if ($variant) {
            CartItem::updateOrCreate(
                ['cart_id' => $cart->id, 'variant_id' => $variant->id],
                ['quantity' => 2, 'unit_price' => $variant->price ?: $variant->product->price]
            );
        }
    }
}