<?php

namespace App\Http\Controllers;

use App\Models\AppointmentApplication;
use App\Services\AmoCrmService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class AppointmentApplicationController extends Controller
{
    public function store(Request $request, AmoCrmService $amoCrm): RedirectResponse
    {
        $data = $request->validate([
            'appointment_full_name' => ['required', 'string', 'max:255'],
            'appointment_birth_date' => ['required', 'date', 'before:today'],
            'appointment_region_district' => ['required', 'string', 'max:255'],
            'appointment_phone' => ['required', 'regex:/^\+998\s\d{2}\s\d{3}\s\d{2}\s\d{2}$/'],
            'appointment_type' => ['required', 'string', 'max:255'],
        ]);

        $application = AppointmentApplication::create([
            'full_name' => mb_strtoupper($data['appointment_full_name'], 'UTF-8'),
            'birth_date' => $data['appointment_birth_date'],
            'region_district' => $data['appointment_region_district'],
            'phone' => $data['appointment_phone'],
            'appointment_type' => $data['appointment_type'],
        ]);

        try {
            $amoCrm->sendAppointment($application);
        } catch (Throwable $exception) {
            Log::error('Qabul so‘rovini amoCRM ga yuborib bo‘lmadi.', [
                'appointment_application_id' => $application->id,
                'exception' => $exception,
            ]);
        }

        return back()->with('appointment_status', 'Qabul so‘rovi yuborildi.');
    }
}
