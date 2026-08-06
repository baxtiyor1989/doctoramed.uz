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
        $items = AppointmentApplication::query()->with(['region', 'district'])->latest()->paginate(20);
        $unreadIds = $items->getCollection()->whereNull('viewed_at')->pluck('id');

        if ($unreadIds->isNotEmpty()) {
            AppointmentApplication::query()->whereKey($unreadIds)->update(['viewed_at' => now()]);
        }

        return view('admin.appointments.index', compact('items'));
    }

    public function destroy(AppointmentApplication $appointment): RedirectResponse
    {
        $appointment->delete();

        return back()->with('status', 'Qabul so‘rovi o‘chirildi.');
    }
}
