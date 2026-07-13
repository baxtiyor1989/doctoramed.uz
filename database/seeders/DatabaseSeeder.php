<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Article;
use App\Models\Doctor;
use App\Models\HeroVideo;
use App\Models\Partner;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'login' => env('ADMIN_LOGIN', 'admin'),
        ], [
            'name' => env('ADMIN_NAME', 'Administrator'),
            'email' => env('ADMIN_EMAIL', 'admin@example.com'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'admin12345')),
        ]);

        $settings = SiteSetting::current();
        $settings->update([
            'translations' => array_merge($settings->translations ?? [], [
                'site_title' => ['uz' => $settings->site_title, 'ru' => 'Medicare Clinic — премиальная медицинская клиника', 'en' => 'Medicare Clinic — Premium medical clinic'],
                'brand_name' => ['uz' => $settings->brand_name, 'ru' => 'Medicare', 'en' => 'Medicare'],
                'brand_subtitle' => ['uz' => $settings->brand_subtitle, 'ru' => 'CLINIC', 'en' => 'CLINIC'],
                'hero_eyebrow' => ['uz' => $settings->hero_eyebrow, 'ru' => 'Ваше здоровье — наш главный приоритет', 'en' => 'Your health is our top priority'],
                'hero_title' => ['uz' => $settings->hero_title, 'ru' => 'Современная медицина,', 'en' => 'Modern medicine,'],
                'hero_highlight' => ['uz' => $settings->hero_highlight, 'ru' => 'надежные результаты', 'en' => 'trusted results'],
                'hero_text' => ['uz' => $settings->hero_text, 'ru' => 'Medicare Clinic — частная медицинская клиника с опытными специалистами, современным оборудованием и индивидуальным подходом.', 'en' => 'Medicare Clinic is a private medical clinic with experienced specialists, modern equipment, and a personal approach.'],
                'services_subtitle' => ['uz' => $settings->services_subtitle, 'ru' => 'Наши услуги', 'en' => 'Our services'],
                'services_title' => ['uz' => $settings->services_title, 'ru' => 'Медицинские услуги', 'en' => 'Medical services'],
                'services_text' => ['uz' => $settings->services_text, 'ru' => 'В клинике доступны современные медицинские услуги', 'en' => 'Modern medical services are available at our clinic'],
                'about_tag' => ['uz' => $settings->about_tag, 'ru' => 'О клинике', 'en' => 'About clinic'],
                'about_title' => ['uz' => $settings->about_title, 'ru' => 'Лучший медицинский сервис для вас', 'en' => 'The best medical care for you'],
                'about_text' => ['uz' => $settings->about_text, 'ru' => 'Medicare Clinic ставит здоровье пациента на первое место и предлагает комфортную среду, современные технологии и опытных специалистов.', 'en' => 'Medicare Clinic puts patient health first with a comfortable environment, advanced technology, and experienced specialists.'],
                'doctors_subtitle' => ['uz' => $settings->doctors_subtitle, 'ru' => 'Наши врачи', 'en' => 'Our doctors'],
                'doctors_title' => ['uz' => $settings->doctors_title, 'ru' => 'Наши специалисты', 'en' => 'Our specialists'],
                'testimonials_subtitle' => ['uz' => $settings->testimonials_subtitle, 'ru' => 'Отзывы пациентов', 'en' => 'Patient reviews'],
                'testimonials_title' => ['uz' => $settings->testimonials_title, 'ru' => 'Что говорят наши пациенты', 'en' => 'What our patients say'],
                'appointment_title' => ['uz' => $settings->appointment_title, 'ru' => 'Запишитесь на прием', 'en' => 'Book an appointment'],
                'appointment_text' => ['uz' => $settings->appointment_text, 'ru' => 'Начните заботиться о здоровье сегодня. Мы готовы помочь вам!', 'en' => 'Start taking care of your health today. We are ready to help!'],
                'appointment_hours' => ['uz' => $settings->appointment_hours, 'ru' => 'Ежедневно 08:00 - 20:00', 'en' => 'Daily 08:00 - 20:00'],
                'news_subtitle' => ['uz' => $settings->news_subtitle, 'ru' => 'Новости', 'en' => 'News'],
                'news_title' => ['uz' => $settings->news_title, 'ru' => 'Полезные статьи и новости', 'en' => 'Useful articles and news'],
                'footer_text' => ['uz' => $settings->footer_text, 'ru' => 'Medicare Clinic — качественная медицинская помощь для вашего здоровья.', 'en' => 'Medicare Clinic provides high-quality medical care for your health.'],
                'footer_copyright' => ['uz' => $settings->footer_copyright, 'ru' => '© 2026 Medicare Clinic. Все права защищены.', 'en' => '© 2026 Medicare Clinic. All rights reserved.'],
                'hero_features' => [
                    'uz' => $settings->hero_features,
                    'ru' => [
                        ['icon' => '▣', 'text' => 'Современное оборудование'],
                        ['icon' => '♙', 'text' => 'Опытные специалисты'],
                        ['icon' => '♡', 'text' => 'Международные стандарты'],
                    ],
                    'en' => [
                        ['icon' => '▣', 'text' => 'Modern equipment'],
                        ['icon' => '♙', 'text' => 'Experienced specialists'],
                        ['icon' => '♡', 'text' => 'International standards'],
                    ],
                ],
                'stats' => [
                    'uz' => $settings->stats,
                    'ru' => [
                        ['icon' => '♡', 'value' => '15+', 'label' => 'Направлений'],
                        ['icon' => '♙', 'value' => '40+', 'label' => 'Врачей'],
                        ['icon' => '♧', 'value' => '20 000+', 'label' => 'Довольных пациентов'],
                        ['icon' => '✦', 'value' => '10+', 'label' => 'Лет опыта'],
                        ['icon' => '☎', 'value' => '24/7', 'label' => 'Экстренная помощь'],
                    ],
                    'en' => [
                        ['icon' => '♡', 'value' => '15+', 'label' => 'Departments'],
                        ['icon' => '♙', 'value' => '40+', 'label' => 'Doctors'],
                        ['icon' => '♧', 'value' => '20 000+', 'label' => 'Happy patients'],
                        ['icon' => '✦', 'value' => '10+', 'label' => 'Years experience'],
                        ['icon' => '☎', 'value' => '24/7', 'label' => 'Emergency care'],
                    ],
                ],
                'about_items' => [
                    'uz' => $settings->about_items,
                    'ru' => ['Современное медицинское оборудование', 'Опытные и квалифицированные специалисты', 'Индивидуальный подход', 'Международные стандарты качества'],
                    'en' => ['Modern medical equipment', 'Experienced and qualified specialists', 'Personal approach', 'International quality standards'],
                ],
            ]),
        ]);

        collect([
            ['♡', 'Kardiologiya', 'Yurak-qon tomir kasalliklarini diagnostika va davolash.'],
            ['⚕', 'Laboratoriya', 'Zamonaviy laborator tahlillar, aniq va tezkor natijalar.'],
            ['⌕', 'Diagnostika', 'UZI, EKG, rentgen va boshqa tekshiruv xizmatlari.'],
            ['♀', 'Ginekologiya', 'Ayollar salomatligi uchun to‘liq tibbiy xizmatlar.'],
            ['✚', 'Xirurgiya', 'Zamonaviy jarrohlik amaliyotlari va operatsiyalar.'],
        ])->each(function (array $item, int $index) {
            $service = Service::firstOrCreate([
            'title' => $item[1],
        ], [
            'icon' => $item[0],
            'description' => $item[2],
            'sort_order' => $index + 1,
            ]);
            $service->update(['translations' => array_merge($service->translations ?? [], [
                'title' => ['uz' => $service->title],
                'description' => ['uz' => $service->description],
            ])]);
        });

        collect([
            ['Dr. Anvarov Alisher', 'Kardiolog', '15 yillik tajriba', 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=500&q=80'],
            ['Dr. Karimova Dildora', 'Ginekolog', '12 yillik tajriba', 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=500&q=80'],
            ['Dr. Azizov Behzod', 'Xirurg', '18 yillik tajriba', 'https://images.unsplash.com/photo-1537368910025-700350fe46c7?auto=format&fit=crop&w=500&q=80'],
            ['Dr. Masharipova Nilufar', 'Nevrolog', '10 yillik tajriba', 'https://images.unsplash.com/photo-1594824476967-48c8b964273f?auto=format&fit=crop&w=500&q=80'],
            ['Dr. Sodiqov Jamshid', 'Terapevt', '11 yillik tajriba', 'https://images.unsplash.com/photo-1582750433449-648ed127bb54?auto=format&fit=crop&w=500&q=80'],
        ])->each(fn (array $item, int $index) => Doctor::firstOrCreate([
            'name' => $item[0],
        ], [
            'specialty' => $item[1],
            'experience' => $item[2],
            'image' => $item[3],
            'sort_order' => $index + 1,
        ]));

        collect([
            ['Dinora R.', 'Bemor', 'Klinikada xizmat ko‘rsatish darajasi juda yuqori. Shifokorlar juda mehribon va professional.'],
            ['Shaxzod B.', 'Bemor', 'Zamonaviy uskunalar va toza muhit. Natijalardan juda mamnunman.'],
            ['Malika T.', 'Bemor', 'Tekshiruv tez va sifatli o‘tdi. Medicare Clinic eng yaxshisi!'],
        ])->each(fn (array $item, int $index) => Testimonial::firstOrCreate([
            'name' => $item[0],
        ], [
            'role' => $item[1],
            'text' => $item[2],
            'sort_order' => $index + 1,
        ]));

        collect([
            ['Yurak salomatligini saqlash uchun 7 ta maslahat', 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=600&q=80', '2026-05-26'],
            ['Laborator tahlillar haqida bilishingiz kerak bo‘lganlar', 'https://images.unsplash.com/photo-1579154204601-01588f351e67?auto=format&fit=crop&w=600&q=80', '2026-05-20'],
            ['Bahorgi allergiya: alomatlar va davolash usullari', 'https://images.unsplash.com/photo-1580281657527-47f249e8f4df?auto=format&fit=crop&w=600&q=80', '2026-05-18'],
            ['Sog‘lom turmush tarzi uchun foydali odatlar', 'https://images.unsplash.com/photo-1516841273335-e39b37888115?auto=format&fit=crop&w=600&q=80', '2026-05-10'],
        ])->each(fn (array $item, int $index) => Article::firstOrCreate([
            'title' => $item[0],
        ], [
            'image' => $item[1],
            'published_at' => $item[2],
            'sort_order' => $index + 1,
        ]));

        collect(['SIEMENS Healthineers', 'Roche', 'mindray', 'PHILIPS', 'GE Healthcare', 'Canon Medical'])
            ->each(fn (string $name, int $index) => Partner::firstOrCreate([
                'name' => $name,
            ], [
                'sort_order' => $index + 1,
            ]));

        HeroVideo::firstOrCreate([
            'title' => 'Klinika haqida video',
        ], [
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'translations' => [
                'title' => [
                    'uz' => 'Klinika haqida video',
                    'ru' => 'Видео о клинике',
                    'en' => 'Clinic video',
                ],
            ],
            'sort_order' => 1,
        ]);
    }
}
