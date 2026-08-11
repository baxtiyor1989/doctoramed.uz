<?php

namespace Tests\Unit;

use App\Models\AppointmentApplication;
use App\Services\TelegramAppointmentService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TelegramAppointmentServiceTest extends TestCase
{
    public function test_it_sends_appointment_details_to_telegram(): void
    {
        config([
            'services.telegram_appointments.enabled' => true,
            'services.telegram_appointments.bot_token' => 'test-token',
            'services.telegram_appointments.chat_id' => '-100123456789',
        ]);
        Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);

        $application = new AppointmentApplication([
            'full_name' => 'ALIYEV ALI',
            'phone' => '+998 90 123 45 67',
            'region_district' => 'Toshkent, Chilonzor',
            'appointment_type' => 'Kardiolog',
        ]);
        $application->created_at = now();

        app(TelegramAppointmentService::class)->send($application);

        Http::assertSent(fn ($request) =>
            $request->url() === 'https://api.telegram.org/bottest-token/sendMessage'
            && $request['chat_id'] === '-100123456789'
            && str_contains($request['text'], 'ALIYEV ALI')
            && str_contains($request['text'], '+998 90 123 45 67')
        );
    }
}
