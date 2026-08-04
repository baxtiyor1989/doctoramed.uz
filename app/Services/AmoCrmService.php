<?php

namespace App\Services;

use App\Models\AppointmentApplication;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class AmoCrmService
{
    public function sendAppointment(AppointmentApplication $application): void
    {
        if (! config('services.amocrm.enabled')) {
            return;
        }

        $baseUrl = rtrim((string) config('services.amocrm.base_url'), '/');
        $accessToken = (string) config('services.amocrm.access_token');

        if ($baseUrl === '' || $accessToken === '') {
            throw new RuntimeException('amoCRM yoqilgan, ammo AMOCRM_BASE_URL yoki AMOCRM_ACCESS_TOKEN kiritilmagan.');
        }

        $lead = [
            'name' => 'Qabulga yozilish — '.$application->appointment_type,
            '_embedded' => [
                'contacts' => [[
                    'name' => $application->full_name,
                    'custom_fields_values' => [[
                        'field_code' => 'PHONE',
                        'values' => [[
                            'value' => $application->phone,
                            'enum_code' => 'WORK',
                        ]],
                    ]],
                ]],
            ],
        ];

        $this->addIntegerSetting($lead, 'pipeline_id');
        $this->addIntegerSetting($lead, 'status_id');

        $customFields = array_values(array_filter([
            $this->customField('birth_date_field_id', $application->birth_date?->startOfDay()->timestamp),
            $this->customField('region_field_id', $application->region_district),
            $this->customField('appointment_type_field_id', $application->appointment_type),
        ]));

        if ($customFields !== []) {
            $lead['custom_fields_values'] = $customFields;
        }

        $this->client($accessToken)
            ->post($baseUrl.'/api/v4/leads/complex', [$lead])
            ->throw();
    }

    private function client(string $accessToken): PendingRequest
    {
        return Http::acceptJson()
            ->withToken($accessToken)
            ->timeout(8)
            ->retry(2, 300);
    }

    private function addIntegerSetting(array &$lead, string $key): void
    {
        $value = config('services.amocrm.'.$key);

        if (is_numeric($value)) {
            $lead[$key] = (int) $value;
        }
    }

    private function customField(string $setting, mixed $value): ?array
    {
        $fieldId = config('services.amocrm.'.$setting);

        if (! is_numeric($fieldId) || $value === null || $value === '') {
            return null;
        }

        return [
            'field_id' => (int) $fieldId,
            'values' => [['value' => $value]],
        ];
    }
}
