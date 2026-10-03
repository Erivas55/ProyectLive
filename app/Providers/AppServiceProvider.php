<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        
        Event::listen(Registered::class, function (Registered $event) {
            Log::info('AUTH EVENT: usuario registrado', [
                'user_id' => $event->user->id,
                'email' => $event->user->email,
            ]);
        });

        Event::listen(Login::class, function (Login $event) {
            Log::info('AUTH EVENT: login realizado', [
                'user_id' => $event->user->id,
                'email' => $event->user->email,
            ]);
        });

        Event::listen(Logout::class, function (Logout $event) {
            Log::info('AUTH EVENT: logout realizado', [
                'user_id' => $event->user?->id,
            ]);
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
