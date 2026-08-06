<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\District;
use App\Models\Region;
use App\Models\ResumeApplication;
use App\Models\Vacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ResumeApplicationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $region = Region::query()
            ->where('is_active', true)
            ->withCount(['districts' => fn ($query) => $query->where('is_active', true)])
            ->find($request->integer('resume_region_id'));

        $data = $request->validate([
            'resume_last_name' => ['required', 'string', 'max:120'],
            'resume_first_name' => ['required', 'string', 'max:120'],
            'resume_birth_date' => ['required', 'date', 'before:today'],
            'resume_phone' => ['required', 'regex:/^\+998\s\d{2}\s\d{3}\s\d{2}\s\d{2}$/'],
            'resume_region_id' => ['required', Rule::exists('regions', 'id')->where('is_active', true)],
            'resume_district_id' => [
                Rule::requiredIf(fn () => ($region?->districts_count ?? 0) > 0),
                'nullable',
                Rule::exists('districts', 'id')->where(fn ($query) => $query
                    ->where('region_id', $request->integer('resume_region_id'))
                    ->where('is_active', true)),
            ],
            'resume_address' => ['required', 'string', 'max:500'],
            'resume_branch_id' => ['required', Rule::exists('branches', 'id')->where('is_active', true)],
            'resume_vacancy_id' => [
                'required',
                Rule::exists('vacancies', 'id')->where(fn ($query) => $query
                    ->where('branch_id', $request->integer('resume_branch_id'))
                    ->where('is_active', true)),
            ],
            'resume_captcha' => ['required', 'digits:5'],
            'resume_locale' => ['nullable', Rule::in(['uz', 'ru', 'en'])],
        ]);

        $captchaIsValid = collect($request->session()->get('resume_captcha_codes', []))
            ->filter(fn (array $item) => ($item['expires_at'] ?? 0) >= now()->timestamp)
            ->contains(fn (array $item) => hash_equals((string) $item['code'], $data['resume_captcha']));

        if (! $captchaIsValid) {
            throw ValidationException::withMessages(['resume_captcha' => 'Rasmdagi raqam noto‘g‘ri kiritildi.']);
        }
        $request->session()->forget('resume_captcha_codes');

        $district = filled($data['resume_district_id'] ?? null) ? District::find($data['resume_district_id']) : null;
        $branch = Branch::find($data['resume_branch_id']);
        $vacancy = Vacancy::find($data['resume_vacancy_id']);
        $locale = $data['resume_locale'] ?? 'uz';
        $lastName = mb_strtoupper($data['resume_last_name'], 'UTF-8');
        $firstName = mb_strtoupper($data['resume_first_name'], 'UTF-8');

        ResumeApplication::create([
            'last_name' => $lastName,
            'first_name' => $firstName,
            'full_name' => $lastName.' '.$firstName,
            'birth_date' => $data['resume_birth_date'],
            'phone' => $data['resume_phone'],
            'region_id' => $region?->id,
            'district_id' => $district?->id,
            'region_district' => collect([$region?->tr('title', $locale), $district?->tr('title', $locale)])->filter()->implode(', '),
            'address' => $data['resume_address'],
            'branch_id' => $branch?->id,
            'vacancy_id' => $vacancy?->id,
            'branch' => $branch?->tr('title', $locale),
            'position' => $vacancy?->tr('title', $locale),
        ]);

        return back()->with('resume_status', 'Ma’lumot yuborildi.');
    }
}
