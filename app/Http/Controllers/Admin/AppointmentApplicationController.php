<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppointmentApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AppointmentApplicationController extends Controller
{
    public function index(): View
    {
        return view('admin.appointments.index', [
            'items' => AppointmentApplication::query()->latest()->paginate(20),
        ]);
    }

    public function destroy(AppointmentApplication $appointment): RedirectResponse
    {
        $appointment->delete();

        return back()->with('status', 'Qabul so‘rovi o‘chirildi.');
    }
}
