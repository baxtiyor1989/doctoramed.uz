@php
  $ui = [
    'uz' => ['home' => 'Bosh sahifa', 'services' => 'Xizmatlar', 'doctors' => 'Shifokorlar', 'about' => 'Klinika haqida', 'contact' => 'Aloqa', 'appointment' => 'Qabulga yozilish', 'back' => 'Barcha shifokorlar', 'profile' => 'Shifokor profili', 'experience' => 'Tajriba', 'category' => 'Toifa', 'education' => 'Ta’lim va malaka', 'schedule' => 'Ish jadvali', 'about_doctor' => 'Shifokor haqida', 'related' => 'Boshqa mutaxassislar', 'details' => 'Batafsil', 'clinic' => 'Klinika', 'team' => 'Bizning jamoa', 'menu' => 'Menu', 'close' => 'Yopish', 'form_title' => 'Qabulga yozilish', 'name' => 'Ism familiya', 'birth_date' => 'Tug‘ilgan kun, oy, yili', 'region' => 'Viloyat', 'region_placeholder' => 'Viloyatni tanlang', 'district' => 'Tuman', 'district_placeholder' => 'Tumanni tanlang', 'phone' => 'Telefon raqam', 'type' => 'Kimga ko‘rinishi yoki qaysi tekshiruvga tushishi', 'type_placeholder' => 'Yo‘nalishni tanlang', 'submit' => 'Yuborish'],
    'ru' => ['home' => 'Главная', 'services' => 'Услуги', 'doctors' => 'Врачи', 'about' => 'О клинике', 'contact' => 'Контакты', 'appointment' => 'Записаться', 'back' => 'Все врачи', 'profile' => 'Профиль врача', 'experience' => 'Опыт', 'category' => 'Категория', 'education' => 'Образование и квалификация', 'schedule' => 'График работы', 'about_doctor' => 'О враче', 'related' => 'Другие специалисты', 'details' => 'Подробнее', 'clinic' => 'Клиника', 'team' => 'Наша команда', 'menu' => 'Меню', 'close' => 'Закрыть', 'form_title' => 'Запись на прием', 'name' => 'Имя и фамилия', 'birth_date' => 'Дата рождения', 'region' => 'Область', 'region_placeholder' => 'Выберите область', 'district' => 'Район', 'district_placeholder' => 'Выберите район', 'phone' => 'Телефон', 'type' => 'К кому записаться или какое обследование', 'type_placeholder' => 'Выберите направление', 'submit' => 'Отправить'],
    'en' => ['home' => 'Home', 'services' => 'Services', 'doctors' => 'Doctors', 'about' => 'About clinic', 'contact' => 'Contact', 'appointment' => 'Book appointment', 'back' => 'All doctors', 'profile' => 'Doctor profile', 'experience' => 'Experience', 'category' => 'Category', 'education' => 'Education and qualifications', 'schedule' => 'Work schedule', 'about_doctor' => 'About the doctor', 'related' => 'Other specialists', 'details' => 'Details', 'clinic' => 'Clinic', 'team' => 'Our team', 'menu' => 'Menu', 'close' => 'Close', 'form_title' => 'Book appointment', 'name' => 'Full name', 'birth_date' => 'Date of birth', 'region' => 'Region', 'region_placeholder' => 'Select a region', 'district' => 'District', 'district_placeholder' => 'Select a district', 'phone' => 'Phone number', 'type' => 'Doctor or examination', 'type_placeholder' => 'Select a direction', 'submit' => 'Submit'],
  ][$locale] ?? [];
  $ui['last_name'] = ['uz' => 'Familiya', 'ru' => 'Фамилия', 'en' => 'Last name'][$locale] ?? 'Familiya';
  $ui['first_name'] = ['uz' => 'Ism', 'ru' => 'Имя', 'en' => 'First name'][$locale] ?? 'Ism';
  $ui['type'] = ['uz' => 'Tekshiruvlar', 'ru' => 'Обследования', 'en' => 'Examinations'][$locale] ?? 'Tekshiruvlar';
  $ui['captcha_label'] = ['uz' => 'Tasdiqlash kodi', 'ru' => 'Код подтверждения', 'en' => 'Verification code'][$locale] ?? 'Tasdiqlash kodi';
  $ui['captcha_placeholder'] = ['uz' => 'Rasmdagi 5 ta raqam', 'ru' => '5 цифр с картинки', 'en' => 'Enter the 5 digits'][$locale] ?? 'Rasmdagi 5 ta raqam';
  $ui['captcha_refresh'] = ['uz' => 'Kodni yangilash', 'ru' => 'Обновить код', 'en' => 'Refresh code'][$locale] ?? 'Kodni yangilash';
  $ui['address'] = ['uz' => 'Manzil (ixtiyoriy)', 'ru' => 'Адрес (необязательно)', 'en' => 'Address (optional)'][$locale] ?? 'Manzil (ixtiyoriy)';
  $ui['address_placeholder'] = ['uz' => 'Ko‘cha, uy va xonadon', 'ru' => 'Улица, дом и квартира', 'en' => 'Street, house and apartment'][$locale] ?? 'Ko‘cha, uy va xonadon';
  $ui['complaint'] = ['uz' => 'Shikoyatlar (ixtiyoriy)', 'ru' => 'Жалобы (необязательно)', 'en' => 'Complaints (optional)'][$locale] ?? 'Shikoyatlar (ixtiyoriy)';
  $ui['complaint_placeholder'] = ['uz' => 'Shikoyatingizni qisqacha yozing', 'ru' => 'Кратко опишите ваши жалобы', 'en' => 'Briefly describe your complaints'][$locale] ?? 'Shikoyatingizni qisqacha yozing';
  $homeUrl = $locale === 'uz' ? route('front.home') : route('front.locale', $locale);
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $doctor->tr('name', $locale) }} | {{ $settings->tr('site_title', $locale) }}</title>
  <link rel="icon" type="image/png" href="{{ asset('front-assets/logo.png') }}">
  <link rel="stylesheet" href="{{ asset('admin-assets/assets/libs/choices.js/public/assets/styles/choices.min.css') }}">
  <link rel="stylesheet" href="{{ asset('front-assets/style.css') }}?v={{ filemtime(public_path('front-assets/style.css')) }}">
</head>
<body id="top">
  <div class="page-lines"><span class="line line-1"></span><span class="line line-2"></span><span class="line line-3"></span></div>
  @include('front.partials.header')

  <main class="doctor-show-page">
    <section class="section doctor-show-section">
      <div class="container">
        <a class="doctor-show-back" href="{{ $homeUrl }}#doctors">← {{ $ui['back'] }}</a>

        <article class="doctor-show-card">
          <div class="doctor-show-art" aria-hidden="true"><span></span><span></span><i></i></div>
          @if ($doctor->image)
            <div class="doctor-show-image">
              <img src="{{ $doctor->image }}" alt="{{ $doctor->tr('name', $locale) }}">
            </div>
          @endif

          <div class="doctor-show-info">
            <span class="tag">{{ $ui['profile'] }}</span>
            <h1>{{ $doctor->tr('name', $locale) }}</h1>
            <p class="doctor-show-specialty">{{ $doctor->tr('specialty', $locale) }}</p>

            <dl class="doctor-show-facts">
              @if ($doctor->tr('experience', $locale))
                <div><dt>{{ $ui['experience'] }}</dt><dd>{{ $doctor->tr('experience', $locale) }}</dd></div>
              @endif
              @if ($doctor->tr('category', $locale))
                <div><dt>{{ $ui['category'] }}</dt><dd>{{ $doctor->tr('category', $locale) }}</dd></div>
              @endif
              @if ($doctor->tr('work_schedule', $locale))
                <div><dt>{{ $ui['schedule'] }}</dt><dd>{{ $doctor->tr('work_schedule', $locale) }}</dd></div>
              @endif
              @if ($doctor->tr('education', $locale))
                <div class="doctor-show-fact-wide"><dt>{{ $ui['education'] }}</dt><dd>{!! nl2br(e($doctor->tr('education', $locale))) !!}</dd></div>
              @endif
            </dl>

            @if ($doctor->tr('bio', $locale))
              <div class="doctor-show-bio">
                <h2>{{ $ui['about_doctor'] }}</h2>
                {!! $doctor->tr('bio', $locale) !!}
              </div>
            @endif

            <button type="button" class="btn btn-primary doctor-show-appointment" data-appointment-open data-appointment-type="{{ $doctor->tr('specialty', $locale) }}">{{ $ui['appointment'] }}</button>
          </div>
        </article>
      </div>
    </section>

    @if ($relatedDoctors->isNotEmpty())
      <section class="section doctor-related-section">
        <div class="container">
          <div class="section-head">
            <span>{{ $ui['doctors'] }}</span>
            <h2>{{ $ui['related'] }}</h2>
          </div>
          <div class="doctor-related-grid">
            @foreach ($relatedDoctors as $relatedDoctor)
              <article class="doctor-related-card">
                @if ($relatedDoctor->image)<img src="{{ $relatedDoctor->image }}" alt="{{ $relatedDoctor->tr('name', $locale) }}">@endif
                <h3>{{ $relatedDoctor->tr('name', $locale) }}</h3>
                <p>{{ $relatedDoctor->tr('specialty', $locale) }}</p>
                <a href="{{ $locale === 'uz' ? route('front.doctors.show', $relatedDoctor) : route('front.locale.doctors.show', [$locale, $relatedDoctor]) }}">{{ $ui['details'] }} →</a>
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @endif
  </main>

  <div class="resume-modal" id="appointmentModal" aria-hidden="true">
    <div class="resume-modal-backdrop" data-appointment-close></div>
    <div class="resume-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="appointmentModalTitle">
      <button class="resume-modal-close" type="button" data-appointment-close aria-label="{{ $ui['close'] }}">×</button>
      <span class="tag">{{ $ui['appointment'] }}</span>
      <h3 id="appointmentModalTitle">{{ $ui['form_title'] }}</h3>

      @if (session('appointment_status'))<div class="resume-success">{{ session('appointment_status') }}</div>@endif

      <form method="POST" action="{{ route('appointment-applications.store') }}" class="resume-form">
        @csrf
        <label><span>{{ $ui['last_name'] }}</span><input type="text" name="appointment_last_name" value="{{ old('appointment_last_name') }}" data-uppercase-input required>@error('appointment_last_name')<small>{{ $message }}</small>@enderror</label>
        <label><span>{{ $ui['first_name'] }}</span><input type="text" name="appointment_first_name" value="{{ old('appointment_first_name') }}" data-uppercase-input required>@error('appointment_first_name')<small>{{ $message }}</small>@enderror</label>
        <label><span>{{ $ui['birth_date'] }}</span><input type="date" name="appointment_birth_date" value="{{ old('appointment_birth_date') }}" required>@error('appointment_birth_date')<small>{{ $message }}</small>@enderror</label>
        <label><span>{{ $ui['phone'] }}</span><input type="tel" name="appointment_phone" value="{{ old('appointment_phone') }}" placeholder="+998 __ ___ __ __" inputmode="tel" data-phone-mask required>@error('appointment_phone')<small>{{ $message }}</small>@enderror</label>
        @include('front.partials.appointment-location-fields', ['regionLabel' => $ui['region'], 'regionPlaceholder' => $ui['region_placeholder'], 'districtLabel' => $ui['district'], 'districtPlaceholder' => $ui['district_placeholder']])
        <label class="resume-form-wide"><span>{{ $ui['address'] }}</span><input type="text" name="appointment_address" value="{{ old('appointment_address') }}" placeholder="{{ $ui['address_placeholder'] }}" maxlength="500">@error('appointment_address')<small>{{ $message }}</small>@enderror</label>
        <label class="resume-form-wide"><span>{{ $ui['complaint'] }}</span><textarea name="appointment_complaint" rows="3" maxlength="1000" placeholder="{{ $ui['complaint_placeholder'] }}">{{ old('appointment_complaint') }}</textarea>@error('appointment_complaint')<small>{{ $message }}</small>@enderror</label>
        <label class="resume-form-wide">
          <span>{{ $ui['type'] }}</span>
          <span class="resume-multi-select-wrap">
            <select name="appointment_types[]" multiple required data-appointment-type-select data-placeholder="{{ ['uz' => 'Tekshiruvlarni tanlang', 'ru' => 'Выберите обследования', 'en' => 'Select examinations'][$locale] }}">
              @foreach ($appointmentTypes as $appointmentType)
                @php($appointmentTypeTitle = $appointmentType->tr('title', $locale))
                <option value="{{ $appointmentTypeTitle }}" @selected(in_array($appointmentTypeTitle, (array) old('appointment_types', []), true))>{{ $appointmentTypeTitle }}</option>
              @endforeach
            </select>
          </span>
          @error('appointment_types')<small>{{ $message }}</small>@enderror
        </label>
        @include('front.partials.appointment-captcha', ['captchaLabel' => $ui['captcha_label'], 'captchaPlaceholder' => $ui['captcha_placeholder'], 'captchaRefreshLabel' => $ui['captcha_refresh']])
        <button type="submit" class="btn btn-primary resume-form-wide">{{ $ui['submit'] }}</button>
      </form>
    </div>
  </div>

  @include('front.partials.footer')
  <script src="{{ asset('admin-assets/assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
  <script src="{{ asset('front-assets/script.js') }}?v={{ filemtime(public_path('front-assets/script.js')) }}"></script>
</body>
</html>
