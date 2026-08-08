@php
  $ratingText = [
    'uz' => ['question' => 'Klinikamiz xizmatlarini qanday baholaysiz?', 'hint' => 'Fikringiz biz uchun muhim', 'thanks' => 'Bahoyingiz uchun rahmat!', 'results' => 'Umumiy natijalar', 'votes' => 'ta ovoz', 'close' => 'Yopish', 'labels' => [1 => 'Juda yomon', 2 => 'Qoniqarsiz', 3 => 'Yaxshi', 4 => 'Juda yaxshi', 5 => 'A’lo']],
    'ru' => ['question' => 'Как вы оцениваете услуги нашей клиники?', 'hint' => 'Ваше мнение важно для нас', 'thanks' => 'Спасибо за вашу оценку!', 'results' => 'Общие результаты', 'votes' => 'голосов', 'close' => 'Закрыть', 'labels' => [1 => 'Очень плохо', 2 => 'Плохо', 3 => 'Хорошо', 4 => 'Очень хорошо', 5 => 'Отлично']],
    'en' => ['question' => 'How would you rate our clinic services?', 'hint' => 'Your opinion matters to us', 'thanks' => 'Thank you for your rating!', 'results' => 'Overall results', 'votes' => 'votes', 'close' => 'Close', 'labels' => [1 => 'Very poor', 2 => 'Poor', 3 => 'Good', 4 => 'Very good', 5 => 'Excellent']],
  ][$locale ?? 'uz'];
@endphp
<div class="clinic-rating" data-rating-widget data-status-url="{{ route('service-ratings.status') }}" data-submit-url="{{ route('service-ratings.store') }}" data-csrf="{{ csrf_token() }}" data-locale="{{ $locale ?? 'uz' }}" data-copy='@json($ratingText)'>
  <button class="clinic-rating-trigger" type="button" aria-label="{{ $ratingText['question'] }}" aria-expanded="false" data-rating-open>
    <span class="clinic-rating-pulse"></span><span class="clinic-rating-heart">♡</span><span class="clinic-rating-trigger-text">{{ $ratingText['hint'] }}</span>
  </button>
  <section class="clinic-rating-panel" role="dialog" aria-modal="false" aria-labelledby="clinicRatingTitle" hidden>
    <button class="clinic-rating-close" type="button" aria-label="{{ $ratingText['close'] }}" data-rating-close>×</button>
    <div class="clinic-rating-mark">✚</div>
    <p class="clinic-rating-kicker">Doctor A Med</p>
    <h2 id="clinicRatingTitle">{{ $ratingText['question'] }}</h2>
    <p class="clinic-rating-subtitle">{{ $ratingText['hint'] }}</p>
    <div class="clinic-rating-options" data-rating-options>
      @foreach ([1 => '😞', 2 => '🙁', 3 => '🙂', 4 => '😊', 5 => '🤩'] as $score => $emoji)
        <button type="button" data-rating-score="{{ $score }}" aria-label="{{ $ratingText['labels'][$score] }}"><span>{{ $emoji }}</span><small>{{ $ratingText['labels'][$score] }}</small></button>
      @endforeach
    </div>
    <div class="clinic-rating-results" data-rating-results hidden></div>
    <p class="clinic-rating-message" data-rating-message aria-live="polite"></p>
  </section>
</div>
