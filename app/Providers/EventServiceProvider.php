<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/* --- Observer eşleştirmeleri için ek -- */
use App\Models\Order;
use App\Models\Offer;
use App\Observers\OrderObserver;
use App\Observers\OfferObserver;

class EventServiceProvider extends ServiceProvider
{
    /** Dinleyici tablosu (şimdilik kullanılmıyor) */
    protected $listen = [
        // 'App\Events\SomeEvent' => [
        //     'App\Listeners\EventListener',
        // ],
    ];

    /** —— MODEL ⇾ OBSERVER eşleştirmeleri —— */
    protected $observers = [
        Order::class => OrderObserver::class,
        Offer::class => OfferObserver::class,
    ];

    public function boot(): void
    {
         logger()->info('EventServiceProvider booted');
    }
}
