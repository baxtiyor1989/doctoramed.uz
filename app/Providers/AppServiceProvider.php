<?php

namespace App\Providers;

use App\Models\AppointmentApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('admin.layout', function ($view) {
            $newAppointmentsCount = 0;

            if (Auth::check() && Schema::hasColumn('appointment_applications', 'viewed_at')) {
                $newAppointmentsCount = AppointmentApplication::query()->whereNull('viewed_at')->count();
            }

            $view->with('newAppointmentsCount', $newAppointmentsCount);
        });
    }
}
