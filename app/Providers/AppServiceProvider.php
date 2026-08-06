<?php

namespace App\Providers;

use App\Models\AppointmentApplication;
use App\Models\ResumeApplication;
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
            $newResumesCount = 0;

            if (Auth::check() && Schema::hasColumn('appointment_applications', 'viewed_at')) {
                $newAppointmentsCount = AppointmentApplication::query()->whereNull('viewed_at')->count();
            }

            if (Auth::check() && Schema::hasColumn('resume_applications', 'viewed_at')) {
                $newResumesCount = ResumeApplication::query()->whereNull('viewed_at')->count();
            }

            $view->with([
                'newAppointmentsCount' => $newAppointmentsCount,
                'newResumesCount' => $newResumesCount,
            ]);
        });
    }
}
