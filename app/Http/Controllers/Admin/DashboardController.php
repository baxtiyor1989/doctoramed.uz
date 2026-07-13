<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppointmentApplication;
use App\Models\Article;
use App\Models\Doctor;
use App\Models\Partner;
use App\Models\ResumeApplication;
use App\Models\Service;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $contentCards = [
            ['label' => 'Xizmatlar', 'value' => Service::count(), 'route' => route('admin.content.index', 'services'), 'icon' => 'ri-service-line', 'color' => 'success'],
            ['label' => 'Shifokorlar', 'value' => Doctor::count(), 'route' => route('admin.content.index', 'doctors'), 'icon' => 'ri-user-heart-line', 'color' => 'danger'],
            ['label' => 'Yangiliklar', 'value' => Article::count(), 'route' => route('admin.content.index', 'articles'), 'icon' => 'ri-newspaper-line', 'color' => 'primary'],
            ['label' => 'Hamkorlar', 'value' => Partner::count(), 'route' => route('admin.content.index', 'partners'), 'icon' => 'ri-building-line', 'color' => 'warning'],
        ];

        $requestCards = [
            ['label' => 'Qabul so‘rovlari', 'value' => AppointmentApplication::count(), 'today' => AppointmentApplication::whereDate('created_at', today())->count(), 'route' => route('admin.appointments.index'), 'icon' => 'ri-calendar-check-line', 'color' => 'danger'],
            ['label' => 'Rezyumelar', 'value' => ResumeApplication::count(), 'today' => ResumeApplication::whereDate('created_at', today())->count(), 'route' => route('admin.resumes.index'), 'icon' => 'ri-file-user-line', 'color' => 'success'],
        ];

        $months = collect(range(5, 0))->map(fn (int $index) => now()->subMonths($index));
        $appointmentMonthly = $this->monthlyCounts(AppointmentApplication::class, $months);
        $resumeMonthly = $this->monthlyCounts(ResumeApplication::class, $months);
        $maxMonthly = max(1, $appointmentMonthly->max('count'), $resumeMonthly->max('count'));

        return view('admin.dashboard', [
            'contentCards' => $contentCards,
            'requestCards' => $requestCards,
            'appointmentMonthly' => $appointmentMonthly,
            'resumeMonthly' => $resumeMonthly,
            'maxMonthly' => $maxMonthly,
            'latestAppointments' => AppointmentApplication::query()->latest()->limit(6)->get(),
            'latestResumes' => ResumeApplication::query()->latest()->limit(6)->get(),
        ]);
    }

    private function monthlyCounts(string $model, $months)
    {
        return $months->map(function ($month) use ($model) {
            return [
                'label' => $month->translatedFormat('M'),
                'count' => $model::query()
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        });
    }
}
