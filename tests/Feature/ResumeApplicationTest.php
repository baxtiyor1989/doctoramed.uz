<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\District;
use App\Models\Region;
use App\Models\ResumeApplication;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ResumeApplicationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_resume_form_accepts_a_region_without_districts(): void
    {
        $region = Region::create(['title' => 'Test viloyati', 'is_active' => true]);

        $response = $this->withValidCaptcha()->post(route('resume-applications.store'), $this->payload($region));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas(ResumeApplication::class, [
            'last_name' => 'TESTOV',
            'first_name' => 'BEMOR',
            'region_id' => $region->id,
            'district_id' => null,
            'address' => 'Test manzili',
        ]);
    }

    public function test_resume_form_requires_a_district_when_region_has_districts(): void
    {
        $region = Region::create(['title' => 'Test viloyati', 'is_active' => true]);
        District::create(['region_id' => $region->id, 'title' => 'Test tumani', 'is_active' => true]);

        $response = $this->withValidCaptcha()->from('/')->post(route('resume-applications.store'), $this->payload($region));

        $response->assertRedirect('/');
        $response->assertSessionHasErrors('resume_district_id');
    }

    private function payload(Region $region): array
    {
        $branch = Branch::create(['title' => 'Test filiali', 'is_active' => true]);
        $vacancy = Vacancy::create(['branch_id' => $branch->id, 'title' => 'Test lavozimi', 'is_active' => true]);

        return [
            'resume_last_name' => 'Testov',
            'resume_first_name' => 'Bemor',
            'resume_birth_date' => '1990-01-01',
            'resume_phone' => '+998 90 123 45 67',
            'resume_region_id' => $region->id,
            'resume_address' => 'Test manzili',
            'resume_branch_id' => $branch->id,
            'resume_vacancy_id' => $vacancy->id,
            'resume_captcha' => '12345',
            'resume_locale' => 'uz',
        ];
    }

    private function withValidCaptcha(): static
    {
        return $this->withSession([
            'resume_captcha_codes' => [[
                'code' => '12345',
                'expires_at' => now()->addMinute()->timestamp,
            ]],
        ]);
    }
}
