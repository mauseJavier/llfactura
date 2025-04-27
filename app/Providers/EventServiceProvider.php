<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\NovedadCreada;
use App\Listeners\RegistrarNovedad;

class EventServiceProvider extends ServiceProvider
{
    // protected $listen = [
    //     NovedadCreada::class => [
    //         RegistrarNovedad::class,
    //     ],
    // ];

    public function boot()
    {
        parent::boot();
    }
}