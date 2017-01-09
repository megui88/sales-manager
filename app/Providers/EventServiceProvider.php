<?php

namespace App\Providers;

use App\Events\NewSaleEvent;
use App\Events\ReProcessSaleEvent;
use App\Listeners\CurrentAccountListener;
use App\Listeners\PharmacySellingListener;
use App\Listeners\PurchaseOrdertListener;
use App\Listeners\SubsidyListener;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
        NewSaleEvent::class => [
            CurrentAccountListener::class,
            PurchaseOrdertListener::class,
            PharmacySellingListener::class,
            SubsidyListener::class,
        ],
        ReProcessSaleEvent::class => [
            CurrentAccountListener::class,
            PurchaseOrdertListener::class,
            PharmacySellingListener::class,
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
