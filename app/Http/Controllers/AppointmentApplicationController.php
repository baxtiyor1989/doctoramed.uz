<?php

namespace App\Http\Controllers;

use App\Models\AppointmentApplication;
use App\Models\District;
use App\Models\Region;
use App\Services\AmoCrmService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class AppointmentApplicationController extends Controller
{
    public function store(Request $request, AmoCrmService $amoCrm): RedirectResponse
    {
        $region = Region::query()
            ->where('is_active', true)
            ->withCount(['districts' => fn ($query) => $query->where('is_active', true)])
            ->find($request->integer('appointment_region_id'));

        $data = $request->validate([
            'appointment_last_name' => ['required', 'string', 'max:120'],
            'appointment_first_name' => ['required', 'string', 'max:120'],
            'appointment_birth_date' => ['required', 'date', 'before:today'],
            'appointment_region_id' => ['required', Rule::exists('regions', 'id')->where('is_active', true)],
            'appointment_district_id' => [
                Rule::requiredIf(fn () => ($region?->districts_count ?? 0) > 0),
                'nullable',
                Rule::exists('districts', 'id')->where(fn ($query) => $query
                    ->where('region_id', $request->integer('appointment_region_id'))
                    ->where('is_active', true)),
            ],
            'appointment_phone' => ['required', 'regex:/^\+998\s\d{2}\s\d{3}\s\d{2}\s\d{2}$/'],
            'appointment_types' => ['required', 'array', 'min:1'],
            'appointment_types.*' => ['required', 'string', 'max:255', 'distinct'],
            'appointment_captcha' => ['required', 'digits:5'],
        ]);

        $captcha = $data['appointment_captcha'];
        $captchaIsValid = collect($request->session()->get('appointment_captcha_codes', []))
            ->filter(fn (array $item) => ($item['expires_at'] ?? 0) >= now()->timestamp)
            ->contains(fn (array $item) => hash_equals((string) $item['code'], $captcha));

        if (! $captchaIsValid) {
            throw ValidationException::withMessages([
                'appointment_captcha' => 'Rasmdagi raqam noto‘g‘ri kiritildi.',
            ]);
        }

        $request->session()->forget('appointment_captcha_codes');

        $district = filled($data['appointment_district_id'] ?? null)
            ? District::query()->find($data['appointment_district_id'])
            : null;
        $locale = in_array($request->input('appointment_locale'), ['uz', 'ru', 'en'], true)
            ? $request->input('appointment_locale')
            : 'uz';
        $regionDistrict = collect([$region?->tr('title', $locale), $district?->tr('title', $locale)])
            ->filter()
            ->implode(', ');

        $lastName = mb_strtoupper($data['appointment_last_name'], 'UTF-8');
        $firstName = mb_strtoupper($data['appointment_first_name'], 'UTF-8');

        $application = AppointmentApplication::create([
            'last_name' => $lastName,
            'first_name' => $firstName,
            'full_name' => $lastName.' '.$firstName,
            'birth_date' => $data['appointment_birth_date'],
            'region_id' => $region?->id,
            'district_id' => $district?->id,
            'region_district' => $regionDistrict,
            'phone' => $data['appointment_phone'],
            'appointment_type' => implode(', ', $data['appointment_types']),
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
