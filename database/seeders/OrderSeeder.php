<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Seed demo orders with Arabic/English names and realistic totals.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $fakerAr = \Faker\Factory::create('ar_SA');

        $numOrders = (int) (config('seed.orders_count', 50));

        for ($i = 0; $i < $numOrders; $i++) {
            $items = [];
            $numItems = rand(1, 4);
            $subtotal = 0;

            for ($j = 0; $j < $numItems; $j++) {
                $price = rand(10, 120);
                $qty = rand(1, 3);
                $subtotal += $price * $qty;
                $items[] = [
                    'id' => Str::uuid()->toString(),
                    'name' => [
                        'ar' => $fakerAr->words(3, true),
                        'en' => $faker->words(3, true),
                    ],
                    'selectedSize' => Arr::random(['S','M','L','XL']),
                    'quantity' => $qty,
                    'price' => $price,
                    'image' => 'https://picsum.photos/seed/'.Str::random(6).'/300/300',
                    'cityName' => [
                        'ar' => $fakerAr->city,
                        'en' => $faker->city,
                    ],
                ];
            }

            $shipping = 10;
            $total = $subtotal + $shipping;

            Order::create([
                'order_number' => 'ORD-'.now()->format('ymd').'-'.Str::upper(Str::random(6)),
                'customer_name' => $faker->name,
                'email' => $faker->safeEmail,
                'phone' => $faker->e164PhoneNumber,
                'country' => Arr::random(['PS','JO','SA','AE','EG','US','GB']),
                'city' => $faker->city,
                'address' => $faker->address,
                'postal_code' => $faker->postcode,
                'notes' => rand(0,1) ? $faker->sentence() : null,
                'items' => $items,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $total,
                'currency_symbol' => '$',
                'currency_rate' => 1,
                'payment_method' => Arr::random(['paypal','stripe']),
                'payment_status' => Arr::random(['paid','pending']),
                'order_status' => Arr::random(['processing','shipped','delivered','cancelled']),
                'transaction_id' => 'txn_'.Str::random(18),
                'payer_email' => $faker->safeEmail,
                'order_date' => $faker->dateTimeBetween('-60 days', 'now'),
            ]);
        }
    }
}


