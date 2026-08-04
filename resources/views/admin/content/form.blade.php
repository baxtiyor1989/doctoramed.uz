@extends('admin.layout')

@section('title', $config['title'])

@section('content')
    <form method="POST" action="{{ $item->exists ? route('admin.content.update', [$resource, $item]) : route('admin.content.store', $resource) }}" enctype="multipart/form-data">
        @csrf
        @if ($item->exists)
            @method('PUT')
        @endif

        <div class="card">
            <div class="card-body">
                <div class="row">
                    @php
                        $translatableFields = collect($config['fields'])->filter(fn ($field) => $field['translatable'] ?? false);
                        $plainFields = collect($config['fields'])->reject(fn ($field) => $field['translatable'] ?? false);
                    @endphp

                    <div class="col-md-8">
                        @if ($translatableFields->isNotEmpty())
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                @foreach ($locales as $locale => $label)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link @if ($loop->first) active @endif" data-bs-toggle="tab" data-bs-target="#locale-{{ $locale }}" type="button" role="tab">{{ $label }}</button>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                @foreach ($locales as $locale => $label)
                                    <div class="tab-pane fade @if ($loop->first) show active @endif" id="locale-{{ $locale }}" role="tabpanel">
                                        @foreach ($translatableFields as $name => $field)
                                            <div class="mb-3">
                                                <label class="form-label">{{ $field['label'] }}</label>
                                                @php
                                                    $value = old(
                                                        "translations.$name.$locale",
                                                        $item->translations[$name][$locale] ?? ($locale === 'uz' ? $item->{$name} : '')
                                                    );
                                                @endphp

                                                @if ($field['type'] === 'textarea')
                                                    <textarea class="form-control" name="translations[{{ $name }}][{{ $locale }}]" rows="4">{{ $value }}</textarea>
                                                @elseif ($field['type'] === 'richtext')
                                                    <textarea class="form-control ckeditor-field" name="translations[{{ $name }}][{{ $locale }}]" rows="8">{{ $value }}</textarea>
                                                @else
                                                    <input class="form-control" name="translations[{{ $name }}][{{ $locale }}]" type="{{ $field['type'] }}" value="{{ $value }}">
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-light mb-0">Bu bo‘limda tilga bog‘liq maydonlar yo‘q.</div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        @foreach ($plainFields as $name => $field)
                            <div class="mb-3">
                                @if ($field['type'] === 'checkbox')
                                    <div class="form-check mt-4">
                                        <input type="hidden" name="{{ $name }}" value="0">
                                        <input class="form-check-input" type="checkbox" name="{{ $name }}" value="1" id="{{ $name }}" @checked(old($name, $item->{$name}))>
                                        <label class="form-check-label" for="{{ $name }}">{{ $field['label'] }}</label>
                                    </div>
                                @else
                                    <label class="form-label" for="{{ $name }}">{{ $field['label'] }}</label>
                                    @if ($field['type'] === 'textarea')
                                        <textarea class="form-control @error($name) is-invalid @enderror" id="{{ $name }}" name="{{ $name }}" rows="4">{{ old($name, $item->{$name}) }}</textarea>
                                    @elseif ($field['type'] === 'richtext')
                                        <textarea class="form-control ckeditor-field @error($name) is-invalid @enderror" id="{{ $name }}" name="{{ $name }}" rows="8">{{ old($name, $item->{$name}) }}</textarea>
                                    @elseif ($field['type'] === 'file')
                                        <div @class(['single-upload-row', 'has-existing-image' => $item->{$name}])>
                                            <div class="single-upload-preview-column">
                                                <div class="uploaded-image-grid" data-upload-preview></div>
                                                @if ($item->{$name})
                                                    <div class="uploaded-image-grid" data-existing-upload="{{ $name }}">
                                                        <label class="uploaded-image-card">
                                                            <img src="{{ $item->{$name} }}" alt="">
                                                            <span>
                                                                <input type="checkbox" name="remove_{{ $name }}" value="1">
                                                                O‘chirish
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                            </div>
                                            <label class="custom-upload @error($name) is-invalid @enderror">
                                                <input id="{{ $name }}" name="{{ $name }}" type="file" accept="{{ $field['accept'] ?? '' }}" data-upload-input>
                                                <span class="custom-upload-icon"><i class="ri-upload-cloud-2-line"></i></span>
                                                <span class="custom-upload-title">Rasm yuklash</span>
                                                <span class="custom-upload-text" data-upload-text>Fayl tanlang yoki shu yerga tashlang</span>
                                            </label>
                                        </div>
                                    @elseif ($field['type'] === 'multi_file')
                                        <label class="custom-upload custom-upload-multiple @error($name) is-invalid @enderror">
                                            <input id="{{ $name }}" name="{{ $name }}[]" type="file" accept="{{ $field['accept'] ?? '' }}" multiple data-upload-input>
                                            <span class="custom-upload-icon"><i class="ri-gallery-upload-line"></i></span>
                                            <span class="custom-upload-title">Slider rasmlarini yuklash</span>
                                            <span class="custom-upload-text" data-upload-text>Bir nechta rasm tanlash mumkin</span>
                                        </label>
                                        <div class="uploaded-image-grid mt-3" data-upload-preview></div>
                                        @if ($item->{$name})
                                            <div class="uploaded-image-grid mt-3">
                                                @foreach ($item->{$name} as $image)
                                                    <label class="uploaded-image-card">
                                                        <input type="hidden" name="existing_{{ $name }}[]" value="{{ $image }}">
                                                        <img src="{{ $image }}" alt="">
                                                        <span>
                                                            <input type="checkbox" name="remove_{{ $name }}[]" value="{{ $image }}">
                                                            O‘chirish
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif
                                    @elseif ($field['type'] === 'select')
                                        <select class="form-select @error($name) is-invalid @enderror" id="{{ $name }}" name="{{ $name }}">
                                            @foreach (($field['options'] ?? []) as $optionValue => $optionLabel)
                                                <option value="{{ $optionValue }}" @selected((string) old($name, $item->{$name}) === (string) $optionValue)>{{ $optionLabel }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        @php
                                            $inputValue = $field['type'] === 'date'
                                                ? old($name, $item->{$name} ? $item->{$name}->format('Y-m-d') : now()->format('Y-m-d'))
                                                : old($name, $item->{$name});
                                        @endphp
                                        <input class="form-control @error($name) is-invalid @enderror" id="{{ $name }}" name="{{ $name }}" type="{{ $field['type'] }}" value="{{ $inputValue }}">
                                    @endif
                                    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>
                        @endforeach

                        <div class="row g-2 mt-4">
                            <div class="col-6">
                                <button type="submit" class="btn btn-success w-100">Saqlash</button>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('admin.content.index', $resource) }}" class="btn btn-light w-100">Orqaga</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('styles')
    <style>
        .ck-editor__editable {
            min-height: 220px;
        }

        .custom-upload {
            position: relative;
            display: grid;
            justify-items: center;
            gap: 8px;
            min-height: 150px;
            padding: 24px 18px;
            border: 1.5px dashed rgba(183, 33, 45, .38);
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(183, 33, 45, .08), rgba(183, 33, 45, .02));
            text-align: center;
            cursor: pointer;
            transition: .2s ease;
        }

        .custom-upload:hover,
        .custom-upload.dragover {
            border-color: #b7212d;
            background: linear-gradient(135deg, rgba(183, 33, 45, .14), rgba(183, 33, 45, .04));
            transform: translateY(-1px);
        }

        .custom-upload input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .custom-upload-icon {
            width: 54px;
            height: 54px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #b7212d;
            color: #fff;
            font-size: 26px;
        }

        .custom-upload-title {
            color: #1f2937;
            font-weight: 800;
        }

        .custom-upload-text {
            color: #6b7280;
            font-size: 13px;
            line-height: 1.45;
        }

        .uploaded-image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(108px, 1fr));
            gap: 12px;
        }

        .single-upload-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
            align-items: stretch;
        }

        .single-upload-row.has-existing-image {
            grid-template-columns: minmax(120px, 160px) minmax(0, 1fr);
        }

        .single-upload-preview-column {
            display: none;
            align-self: stretch;
        }

        .single-upload-row.has-existing-image .single-upload-preview-column {
            display: block;
        }

        .single-upload-row .custom-upload {
            min-height: 150px;
        }

        .single-upload-row .uploaded-image-grid {
            grid-template-columns: 1fr;
        }

        .single-upload-row .uploaded-image-grid:empty {
            display: none;
        }

        .single-upload-row [data-existing-upload],
        .single-upload-row [data-upload-preview]:not(:empty) {
            height: 100%;
        }

        .uploaded-image-card {
            position: relative;
            display: block;
            overflow: hidden;
            border: 1px solid rgba(183, 33, 45, .14);
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
        }

        .uploaded-image-card img {
            width: 100%;
            height: 92px;
            display: block;
            object-fit: cover;
        }

        .single-upload-row .uploaded-image-card img {
            height: 110px;
            object-fit: contain;
            background: #f8fafc;
        }

        @media (max-width: 575px) {
            .single-upload-row.has-existing-image {
                grid-template-columns: 110px minmax(0, 1fr);
            }

            .single-upload-row .custom-upload {
                min-height: 140px;
                padding: 18px 12px;
            }
        }

        .uploaded-image-card span {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px;
            color: #6b7280;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .uploaded-image-card input[type="checkbox"] {
            accent-color: #b7212d;
        }

        .custom-upload-error {
            color: #b7212d;
            font-size: 13px;
            font-weight: 700;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('admin-assets/assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
    <script>
        const ckeditorInstances = [];

        document.querySelectorAll('.ckeditor-field').forEach((field) => {
            ClassicEditor
                .create(field, {
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'link',
                        'bulletedList', 'numberedList',
                        'blockQuote', 'undo', 'redo'
                    ]
                })
                .then((editor) => {
                    ckeditorInstances.push(editor);
                })
                .catch((error) => console.error(error));
        });

        document.querySelector('form')?.addEventListener('submit', () => {
            ckeditorInstances.forEach((editor) => editor.updateSourceElement());
        });

        document.querySelectorAll('[data-upload-input]').forEach((input) => {
            const wrapper = input.closest('.custom-upload');
            const text = wrapper?.querySelector('[data-upload-text]');
            const preview = wrapper?.closest('.single-upload-row')?.querySelector('[data-upload-preview]')
                || (wrapper?.nextElementSibling?.matches('[data-upload-preview]') ? wrapper.nextElementSibling : null);
            const maxFileSize = 2 * 1024 * 1024;
            const maxTotalSize = 8 * 1024 * 1024;

            const updateText = () => {
                if (!text || !input.files.length) return;

                wrapper?.querySelector('.custom-upload-error')?.remove();
                const files = Array.from(input.files);
                const oversizedFiles = files.filter((file) => file.size > maxFileSize);
                const totalSize = files.reduce((sum, file) => sum + file.size, 0);

                if (oversizedFiles.length || totalSize > maxTotalSize) {
                    input.value = '';
                    text.textContent = 'Fayl tanlang yoki shu yerga tashlang';
                    if (preview) {
                        preview.innerHTML = '';
                    }

                    const error = document.createElement('span');
                    error.className = 'custom-upload-error';
                    error.textContent = oversizedFiles.length
                        ? 'Har bir rasm hajmi 2MB dan oshmasligi kerak.'
                        : 'Jami yuklanadigan rasmlar 8MB dan oshmasligi kerak.';
                    wrapper?.appendChild(error);
                    return;
                }

                const names = files.map((file) => file.name);
                text.textContent = names.length > 2
                    ? `${names.length} ta fayl tanlandi`
                    : names.join(', ');

                if (preview) {
                    preview.innerHTML = '';
                    preview.closest('.single-upload-row')?.classList.add('has-existing-image');
                    document.querySelector(`[data-existing-upload="${input.name}"]`)?.classList.add('d-none');

                    files.forEach((file) => {
                        if (!file.type.startsWith('image/')) return;

                        const reader = new FileReader();
                        reader.addEventListener('load', () => {
                            const card = document.createElement('div');
                            card.className = 'uploaded-image-card';
                            card.innerHTML = `<img src="${reader.result}" alt=""><span>Yangi rasm</span>`;
                            preview.appendChild(card);
                        });
                        reader.readAsDataURL(file);
                    });
                }
            };

            input.addEventListener('change', updateText);
            wrapper?.addEventListener('dragenter', () => wrapper.classList.add('dragover'));
            wrapper?.addEventListener('dragover', () => wrapper.classList.add('dragover'));
            wrapper?.addEventListener('dragleave', () => wrapper.classList.remove('dragover'));
            wrapper?.addEventListener('drop', () => {
                wrapper.classList.remove('dragover');
                window.setTimeout(updateText, 0);
            });
        });
    </script>
@endpush
