<label class="resume-form-wide appointment-captcha-field">
  <span>{{ $ui['captcha_label'] }}</span>
  <span class="appointment-captcha-row">
    <img src="{{ route('resume.captcha') }}" alt="{{ $ui['captcha_label'] }}" data-appointment-captcha-image>
    <button type="button" data-appointment-captcha-refresh aria-label="{{ $ui['captcha_refresh'] }}">↻</button>
    <input type="text" name="resume_captcha" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" autocomplete="off" placeholder="{{ $ui['captcha_placeholder'] }}" required>
  </span>
  @error('resume_captcha')<small>{{ $message }}</small>@enderror
</label>
