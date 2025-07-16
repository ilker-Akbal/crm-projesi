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
use Illuminate\Support\Facades\DB;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // id = 1 ile ENV’den admin oluştur (veya güncelle)
        DB::table('users')->updateOrInsert(
            ['id' => 1],
            [
                'username'   => env('ADMIN_USERNAME'),
                'password'   => Hash::make(env('ADMIN_PASSWORD')),
                'role'       => 'admin',
                'active'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Diğer seed’ler...
    }
}