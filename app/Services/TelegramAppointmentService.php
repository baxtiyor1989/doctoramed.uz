<?php

namespace App\Services;

use App\Models\AppointmentApplication;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class TelegramAppointmentService
{
    public function send(AppointmentApplication $application): void
    {
        if (! config('services.telegram_appointments.enabled')) {
            return;
        }

        $token = trim((string) config('services.telegram_appointments.bot_token'));
        $chatId = trim((string) config('services.telegram_appointments.chat_id'));

        if ($token === '' || $chatId === '') {
            throw new RuntimeException('Telegram xabarnomasi yoqilgan, ammo bot tokeni yoki chat ID kiritilmagan.');
        }

        Http::acceptJson()
            ->timeout(8)
            ->retry(2, 300)
            ->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $this->message($application),
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ])
            ->throw();
    }

    private function message(AppointmentApplication $application): string
    {
        $lines = [
            '🏥 <b>Yangi qabul so‘rovi</b>',
            '',
            '👤 <b>F.I.Sh:</b> '.$this->escape($application->full_name),
            '📞 <b>Telefon:</b> '.$this->escape($application->phone),
            '🎂 <b>Tug‘ilgan sana:</b> '.$this->escape($application->birth_date?->format('d.m.Y') ?? '—'),
            '📍 <b>Hudud:</b> '.$this->escape($application->region_district ?: '—'),
            '🏠 <b>Manzil:</b> '.$this->escape($application->address ?: '—'),
            '🩺 <b>Tekshiruv:</b> '.$this->escape($application->appointment_type),
            '💬 <b>Shikoyat:</b> '.$this->escape($application->complaint ?: '—'),
            '',
            '🕐 <b>Yuborildi:</b> '.$application->created_at?->format('d.m.Y H:i'),
        ];

        return implode("\n", $lines);
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
