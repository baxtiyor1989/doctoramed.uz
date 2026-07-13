<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasTranslations;

#[Fillable([
    'site_title',
    'brand_name',
    'brand_subtitle',
    'phone',
    'email',
    'address',
    'map_embed_url',
    'website',
    'facebook_url',
    'telegram_url',
    'instagram_url',
    'youtube_url',
    'hero_eyebrow',
    'hero_title',
    'hero_highlight',
    'hero_text',
    'hero_features',
    'stats',
    'services_subtitle',
    'services_title',
    'services_text',
    'about_tag',
    'about_title',
    'about_text',
    'about_image',
    'about_items',
    'doctors_subtitle',
    'doctors_title',
    'testimonials_subtitle',
    'testimonials_title',
    'appointment_title',
    'appointment_text',
    'appointment_image',
    'appointment_hours',
    'news_subtitle',
    'news_title',
    'footer_text',
    'footer_copyright',
    'translations',
])]
class SiteSetting extends Model
{
    use HasTranslations;

    protected function casts(): array
    {
        return [
            'hero_features' => 'array',
            'stats' => 'array',
            'about_items' => 'array',
            'translations' => 'array',
        ];
    }

    public static function current(): self
    {
        return self::query()->firstOrCreate([], self::defaults());
    }

    public static function defaults(): array
    {
        return [
            'site_title' => 'Medicare Clinic — Premium tibbiyot klinikasi',
            'brand_name' => 'Medicare',
            'brand_subtitle' => 'CLINIC',
            'phone' => '+998 71 123 45 67',
            'email' => 'info@medicare.uz',
            'address' => 'Toshkent shahri, Chilonzor tumani',
            'map_embed_url' => null,
            'website' => 'www.medicare.uz',
            'facebook_url' => null,
            'telegram_url' => null,
            'instagram_url' => null,
            'youtube_url' => null,
            'hero_eyebrow' => 'Sizning sog‘lig‘ingiz — bizning ustuvor maqsadimiz',
            'hero_title' => 'Zamonaviy tibbiyot,',
            'hero_highlight' => 'ishonchli natijalar',
            'hero_text' => 'Medicare Clinic — yuqori malakali mutaxassislar, zamonaviy uskunalar va bemorga individual yondashuvga asoslangan xususiy tibbiyot klinikasi.',
            'hero_features' => [
                ['icon' => '▣', 'text' => 'Zamonaviy uskunalar'],
                ['icon' => '♙', 'text' => 'Tajribali mutaxassislar'],
                ['icon' => '♡', 'text' => 'Xalqaro standartlar'],
            ],
            'stats' => [
                ['icon' => '♡', 'value' => '15+', 'label' => 'Yo‘nalishlar'],
                ['icon' => '♙', 'value' => '40+', 'label' => 'Shifokorlar'],
                ['icon' => '♧', 'value' => '20 000+', 'label' => 'Mamnun bemorlar'],
                ['icon' => '✦', 'value' => '10+', 'label' => 'Yillik tajriba'],
                ['icon' => '☎', 'value' => '24/7', 'label' => 'Favqulodda yordam'],
            ],
            'services_subtitle' => 'Xizmatlarimiz',
            'services_title' => 'Bizning xizmatlar',
            'services_text' => 'Klinikamizda eng zamonaviy tibbiy xizmatlar mavjud',
            'about_tag' => 'Klinika haqida',
            'about_title' => 'Siz uchun eng yaxshi tibbiy xizmat',
            'about_text' => 'Medicare Clinic bemor salomatligini birinchi o‘ringa qo‘ygan holda qulay muhit, ilg‘or texnologiyalar va tajribali mutaxassislar bilan xizmat ko‘rsatadi.',
            'about_image' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=900&q=80',
            'about_items' => ['Zamonaviy tibbiy uskunalar', 'Tajribali va malakali mutaxassislar', 'Individual yondashuv', 'Xalqaro sifat standartlari'],
            'doctors_subtitle' => 'Shifokorlarimiz',
            'doctors_title' => 'Bizning mutaxassislar',
            'testimonials_subtitle' => 'Bemorlar fikri',
            'testimonials_title' => 'Bemorlarimiz biz haqimizda',
            'appointment_title' => 'Qabulga yoziling',
            'appointment_text' => 'Sog‘lig‘ingizni bugundan nazorat qiling. Biz sizga yordam berishga tayyormiz!',
            'appointment_image' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=600&q=80',
            'appointment_hours' => 'Har kuni 08:00 - 20:00',
            'news_subtitle' => 'Yangiliklar',
            'news_title' => 'Foydali maqolalar va yangiliklar',
            'footer_text' => 'Medicare Clinic — sizning sog‘lig‘ingiz uchun eng yuqori tibbiy xizmat.',
            'footer_copyright' => '© 2026 Medicare Clinic. Barcha huquqlar himoyalangan.',
        ];
    }
}
