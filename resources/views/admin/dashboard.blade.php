@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        @foreach ([
            ['label' => 'Xizmatlar', 'value' => \App\Models\Service::count(), 'route' => route('admin.content.index', 'services'), 'icon' => 'ri-service-line'],
            ['label' => 'Shifokorlar', 'value' => \App\Models\Doctor::count(), 'route' => route('admin.content.index', 'doctors'), 'icon' => 'ri-user-heart-line'],
            ['label' => 'Yangiliklar', 'value' => \App\Models\Article::count(), 'route' => route('admin.content.index', 'articles'), 'icon' => 'ri-newspaper-line'],
        ] as $card)
            <div class="col-xl-4 col-md-6">
                <a href="{{ $card['route'] }}" class="text-decoration-none">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-uppercase fw-medium text-muted mb-2">{{ $card['label'] }}</p>
                                    <h4 class="fs-22 fw-semibold mb-0">{{ $card['value'] }}</h4>
                                </div>
                                <span class="avatar-title bg-success-subtle rounded fs-3">
                                    <i class="{{ $card['icon'] }} text-success"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection
