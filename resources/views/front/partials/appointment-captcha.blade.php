<label class="resume-form-wide appointment-captcha-field">
  <span>{{ $captchaLabel }}</span>
  <span class="appointment-captcha-row">
    <img src="{{ route('appointment.captcha') }}" alt="{{ $captchaLabel }}" data-appointment-captcha-image>
    <button type="button" data-appointment-captcha-refresh aria-label="{{ $captchaRefreshLabel }}">↻</button>
    <input type="text" name="appointment_captcha" value="" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" autocomplete="off" placeholder="{{ $captchaPlaceholder }}" required>
  </span>
  @error('appointment_captcha')<small>{{ $message }}</small>@enderror
</label>
