<?php

namespace Ares\Providers;

use Ares\Listeners\AuthenticationSubscriber;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /** @inheritdoc */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class
        ],
    ];
    /** @inheritdoc */
    protected $subscribe = [
        AuthenticationSubscriber::class
    ];
}
