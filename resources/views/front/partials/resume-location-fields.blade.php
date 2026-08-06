<input type="hidden" name="resume_locale" value="{{ $locale }}">
<label>
  <span>{{ $ui['region'] }}</span>
  <span class="resume-location-select-wrap">
    <select name="resume_region_id" data-region-select required>
      <option value="">{{ $ui['region_placeholder'] }}</option>
      @foreach ($regions as $region)
        <option value="{{ $region->id }}" @selected((string) old('resume_region_id') === (string) $region->id)>{{ $region->tr('title', $locale) }}</option>
      @endforeach
    </select>
  </span>
  @error('resume_region_id')<small>{{ $message }}</small>@enderror
</label>
<label data-district-field hidden>
  <span>{{ $ui['district'] }}</span>
  <span class="resume-location-select-wrap">
    <select name="resume_district_id" data-district-select disabled>
      <option value="">{{ $ui['district_placeholder'] }}</option>
      @foreach ($regions as $region)
        @foreach ($region->districts as $district)
          <option value="{{ $district->id }}" data-region-id="{{ $region->id }}" @selected((string) old('resume_district_id') === (string) $district->id)>{{ $district->tr('title', $locale) }}</option>
        @endforeach
      @endforeach
    </select>
  </span>
  @error('resume_district_id')<small>{{ $message }}</small>@enderror
</label>
