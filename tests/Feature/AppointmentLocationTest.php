<?php

namespace Tests\Feature;

use App\Models\AppointmentApplication;
use App\Models\District;
use App\Models\Region;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AppointmentLocationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_district_is_not_required_when_region_has_no_districts(): void
    {
        $region = Region::create(['title' => 'Test viloyati', 'is_active' => true]);

        $response = $this->post(route('appointment-applications.store'), $this->payload($region));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas(AppointmentApplication::class, [
            'region_id' => $region->id,
            'district_id' => null,
            'region_district' => 'Test viloyati',
        ]);
    }

    public function test_district_is_required_when_region_has_districts(): void
    {
        $region = Region::create(['title' => 'Test viloyati', 'is_active' => true]);
        District::create(['region_id' => $region->id, 'title' => 'Test tumani', 'is_active' => true]);

        $response = $this->from('/')->post(route('appointment-applications.store'), $this->payload($region));

        $response->assertRedirect('/');
        $response->assertSessionHasErrors('appointment_district_id');
    }

    private function payload(Region $region): array
    {
        return [
            'appointment_last_name' => 'Testov',
            'appointment_first_name' => 'Bemor',
            'appointment_birth_date' => '1990-01-01',
            'appointment_region_id' => $region->id,
            'appointment_phone' => '+998 90 123 45 67',
            'appointment_types' => ['Test yo‘nalish'],
            'appointment_locale' => 'uz',
        ];
    }
}
