<input type="hidden" name="appointment_locale" value="{{ $locale }}">
<label>
  <span>{{ $regionLabel }}</span>
  <span class="resume-location-select-wrap">
    <select name="appointment_region_id" data-region-select required>
      <option value="">{{ $regionPlaceholder }}</option>
      @foreach ($regions as $region)
        <option value="{{ $region->id }}" @selected((string) old('appointment_region_id') === (string) $region->id)>{{ $region->tr('title', $locale) }}</option>
      @endforeach
    </select>
  </span>
  @error('appointment_region_id')<small>{{ $message }}</small>@enderror
</label>
<label data-district-field hidden>
  <span>{{ $districtLabel }}</span>
  <span class="resume-location-select-wrap">
    <select name="appointment_district_id" data-district-select disabled>
      <option value="">{{ $districtPlaceholder }}</option>
      @foreach ($regions as $region)
        @foreach ($region->districts as $district)
          <option value="{{ $district->id }}" data-region-id="{{ $region->id }}" @selected((string) old('appointment_district_id') === (string) $district->id)>{{ $district->tr('title', $locale) }}</option>
        @endforeach
      @endforeach
    </select>
  </span>
  @error('appointment_district_id')<small>{{ $message }}</small>@enderror
</label>
