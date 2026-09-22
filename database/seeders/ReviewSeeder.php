<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->where('role', 'customer')->get();
        $products = Product::query()->where('is_bestseller', true)->orWhere('is_featured', true)->get();

        $reviews = [
            ['rating' => 5, 'title' => 'Better than expected', 'comment' => 'Heavyweight fabric, proper shape. My daily uniform now.', 'status' => 'approved'],
            ['rating' => 5, 'title' => 'The fit is everything', 'comment' => 'Signature sizing is spot on. Sized up for the intended drape.', 'status' => 'approved'],
            ['rating' => 4, 'title' => 'Great quality', 'comment' => 'Dense fleece, clean stitching. Shrinks slightly on first wash.', 'status' => 'approved'],
            ['rating' => 5, 'title' => 'Owned it for a month', 'comment' => 'Worn weekly and it still holds its structure.', 'status' => 'pending'],
            ['rating' => 4, 'title' => 'Strong staple', 'comment' => 'Solid piece, true to size. Colour is deeper in person.', 'status' => 'approved'],
        ];

        foreach ($products->take(5) as $index => $product) {
            $user = $users[$index % max(1, $users->count())];
            $review = $reviews[$index % count($reviews)];

            $orderItem = OrderItem::query()
                ->where('product_id', $product->id)
                ->first();

            Review::updateOrCreate(
                ['product_id' => $product->id, 'user_id' => $user->id],
                $review + [
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'order_item_id' => $orderItem?->id,
                    'is_verified' => $orderItem !== null,
                ]
            );
        }
    }
}