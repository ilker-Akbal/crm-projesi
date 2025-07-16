<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/* ---------- Modeller ---------- */
use App\Models\User;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Offer;
use App\Models\OfferProduct;
use App\Models\Action;
use App\Models\CurrentCard;
use App\Models\CurrentMovement;
use App\Models\Reminder;
use App\Models\SupportRequest;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /* ==============================================================
         | 1) USERS
         * ============================================================ */
        // Sistem admini
        User::factory()->create([
            'username' => 'admin',
            'role'     => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        // Ek 20 kullanıcı
        User::factory(20)->create();

        /* ==============================================================
         | 2) CUSTOMERS  +  COMPANY  +  CONTACTS
         * ============================================================ */
        Customer::factory(40)                       // 40 müşteri
            ->has(
                Company::factory()                  // her müşteriye 1 şirket
                    ->has(Contact::factory()->count(3))  // ve 3 kontak
            )
            ->create();

        /* ==============================================================
         | 3) PRODUCTS  +  PRICE  +  STOCK
         * ============================================================ */
        /* 3) PRODUCTS + PRICE + STOCK */
Product::factory(60)
    ->has(ProductPrice::factory(), 'prices')   // <-- ilişki adı "prices"
    ->has(ProductStock::factory(),  'stocks')  // <-- ilişki adı "stocks"
    ->create();


        /* ==============================================================
         | 4) ORDERS  +  ORDER_PRODUCTS  (satırlar)
         * ============================================================ */
        Order::factory(50)                          // 50 sipariş
            ->create()
            ->each(function (Order $order) {
                // 2-6 satır
                $lines  = Product::inRandomOrder()->limit(rand(2, 6))->get();
                $total  = 0;

                foreach ($lines as $product) {
                    $qty   = rand(1, 12);
                    // varsa son fiyatı al, yoksa rastgele
                    $price = $product->prices()->latest()->first()->price ?? rand(10, 300);

                    OrderProduct::factory()->create([
                        'order_id'   => $order->id,
                        'product_id' => $product->id,
                        'amount'     => $qty,
                        'unit_price' => $price,
                        'updated_by' => User::inRandomOrder()->first()->id,
                    ]);

                    $total += $qty * $price;
                }
                $order->update(['total_amount' => $total]);
            });

        /* ==============================================================
         | 5) OFFERS  +  OFFER_PRODUCTS
         * ============================================================ */
        Offer::factory(25)                          // 25 teklif
            ->create()
            ->each(function (Offer $offer) {
                $lines = Product::inRandomOrder()->limit(rand(1, 5))->get();
                $total = 0;

                foreach ($lines as $product) {
                    $qty   = rand(1, 10);
                    $price = $product->prices()->latest()->first()->price ?? rand(20, 350);

                    OfferProduct::factory()->create([
                        'offer_id'   => $offer->id,
                        'product_id' => $product->id,
                        'amount'     => $qty,
                        'unit_price' => $price,
                        'updated_by' => User::inRandomOrder()->first()->id,
                    ]);
                    $total += $qty * $price;
                }
                $offer->update(['total_amount' => $total]);
            });

        /* ==============================================================
         | 6) ACTIONS
         * ============================================================ */
        Action::factory(80)->create();

        /* ==============================================================
         | 7) CURRENT CARDS  +  MOVEMENTS
         * ============================================================ */
        CurrentCard::factory(30)
            ->create()
            ->each(function (CurrentCard $card) {
                CurrentMovement::factory(4)->create([
                    'current_id' => $card->id,
                ]);
            });

        /* ==============================================================
         | 8) REMINDERS  &  SUPPORT REQUESTS
         * ============================================================ */
        Reminder::factory(40)->create();
        SupportRequest::factory(25)->create();
    }
}
