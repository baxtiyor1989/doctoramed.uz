@extends('admin.layout')

@section('title', 'Dashboard')

@push('styles')
    <style>
        .dashboard-hero {
            position: relative;
            overflow: hidden;
            border: 0;
            border-radius: 18px;
            background: linear-gradient(135deg, #b7212d 0%, #d83946 48%, #8e1721 100%);
            color: #fff;
        }

        .dashboard-hero::after {
            content: "";
            position: absolute;
            inset: -60px -90px auto auto;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .13);
        }

        .dashboard-hero .card-body {
            position: relative;
            z-index: 1;
        }

        .dashboard-rating-summary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 13px;
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 12px;
            background: rgba(255, 255, 255, .14);
            color: #fff;
            text-decoration: none;
            backdrop-filter: blur(8px);
            transition: background .2s ease, transform .2s ease;
        }

        .dashboard-rating-summary:hover {
            color: #fff;
            background: rgba(255, 255, 255, .23);
            transform: translateY(-2px);
        }

        .dashboard-card {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 12px 35px rgba(15, 23, 42, .07);
        }

        .dashboard-stat {
            border: 0;
            border-radius: 16px;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .dashboard-stat:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 35px rgba(15, 23, 42, .1);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            font-size: 26px;
        }

        .mini-chart {
            display: grid;
            grid-template-columns: repeat(6, minmax(48px, 1fr));
            gap: 18px;
            align-items: end;
            min-height: 230px;
            padding-top: 18px;
        }

        .chart-month {
            display: grid;
            gap: 10px;
            justify-items: center;
            height: 205px;
        }

        .chart-bars {
            display: flex;
            align-items: end;
            gap: 7px;
            width: 100%;
            height: 160px;
            justify-content: center;
            padding: 0 4px;
        }

        .chart-bar {
            width: 18px;
            min-height: 8px;
            border-radius: 10px 10px 4px 4px;
            box-shadow: inset 0 -10px 18px rgba(0, 0, 0, .12);
        }

        .chart-bar-danger {
            background: linear-gradient(180deg, #ff6b78, #b7212d);
        }

        .chart-bar-success {
            background: linear-gradient(180deg, #2dd4bf, #0f9f8f);
        }

        .content-meter {
            height: 9px;
            border-radius: 999px;
            background: #eef2f7;
            overflow: hidden;
        }

        .content-meter span {
            display: block;
            height: 100%;
            border-radius: inherit;
        }

        .activity-item {
            display: flex;
            gap: 12px;
            padding: 14px 0;
            border-bottom: 1px solid #eef2f7;
        }

        .activity-item:last-child {
            border-bottom: 0;
        }

        .activity-dot {
            width: 10px;
            height: 10px;
            margin-top: 7px;
            flex: 0 0 auto;
            border-radius: 50%;
            background: #b7212d;
            box-shadow: 0 0 0 5px rgba(183, 33, 45, .12);
        }

        @media (max-width: 767.98px) {
            .mini-chart {
                gap: 10px;
                overflow-x: auto;
                grid-template-columns: repeat(6, 58px);
                padding-bottom: 8px;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $totalContent = max(1, collect($contentCards)->sum('value'));
        $colorMap = [
            'success' => '#0f9f8f',
            'danger' => '#b7212d',
            'primary' => '#405189',
            'warning' => '#f7b84b',
        ];
    @endphp

    <div class="card dashboard-hero mb-4">
        <div class="card-body p-4 p-lg-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge bg-white text-danger mb-3">Doctor A Med Clinic</span>
                    <h2 class="mb-2 text-white">Admin dashboard</h2>
                    <p class="mb-0 text-white-75 fs-15">
                        Saytdagi kontentlar, qabul so‘rovlari va rezyumelar holati bir joyda jamlandi.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="fs-13 text-white-75 mb-2">Bugungi sana</div>
                    <div class="fs-4 fw-semibold">{{ now()->format('d.m.Y') }}</div>
                    <div class="d-flex flex-wrap justify-content-lg-end gap-2 mt-3">
                        <a href="{{ route('admin.ratings.index') }}" class="dashboard-rating-summary">
                            <i class="ri-star-fill text-warning fs-18"></i>
                            <strong>{{ number_format($ratingAverage, 1) }}</strong>
                            <span class="text-white-75">{{ $ratingTotal }} ta ovoz</span>
                        </a>
                        <a href="{{ route('front.home') }}" class="btn btn-light btn-sm d-inline-flex align-items-center" target="_blank" rel="noopener noreferrer">
                            <i class="ri-global-line align-middle me-1"></i> Saytga o‘tish
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @foreach ($requestCards as $card)
            <div class="col-xl-2 col-lg-4 col-md-6">
                <a href="{{ $card['route'] }}" class="text-decoration-none d-block h-100">
                    <div class="card dashboard-stat h-100 mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-uppercase fw-medium text-muted mb-2">{{ $card['label'] }}</p>
                                    <h3 class="fw-bold mb-1">{{ $card['value'] }}</h3>
                                    <span class="badge bg-{{ $card['color'] }}-subtle text-{{ $card['color'] }}">
                                        Bugun: {{ $card['today'] }}
                                    </span>
                                </div>
                                <span class="stat-icon bg-{{ $card['color'] }}-subtle text-{{ $card['color'] }}">
                                    <i class="{{ $card['icon'] }}"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach

        @foreach ($contentCards as $card)
            <div class="col-xl-2 col-lg-4 col-md-6">
                <a href="{{ $card['route'] }}" class="text-decoration-none d-block h-100">
                    <div class="card dashboard-stat h-100 mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-uppercase fw-medium text-muted mb-2">{{ $card['label'] }}</p>
                                    <h3 class="fw-bold mb-0">{{ $card['value'] }}</h3>
                                </div>
                                <span class="stat-icon bg-{{ $card['color'] }}-subtle text-{{ $card['color'] }}">
                                    <i class="{{ $card['icon'] }}"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card dashboard-card">
                <div class="card-header border-0 align-items-center d-flex">
                    <div class="flex-grow-1">
                        <h4 class="card-title mb-1">So‘rovlar dinamikasi</h4>
                        <p class="text-muted mb-0">Oxirgi 6 oy bo‘yicha qabul va rezyume statistikasi</p>
                    </div>
                    <div class="d-flex gap-3 fs-12">
                        <span><i class="ri-checkbox-blank-circle-fill text-danger me-1"></i>Qabul</span>
                        <span><i class="ri-checkbox-blank-circle-fill text-success me-1"></i>Rezyume</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mini-chart">
                        @foreach ($appointmentMonthly as $index => $month)
                            @php
                                $resumeCount = $resumeMonthly[$index]['count'] ?? 0;
                                $appointmentHeight = max(8, round(($month['count'] / $maxMonthly) * 100));
                                $resumeHeight = max(8, round(($resumeCount / $maxMonthly) * 100));
                            @endphp
                            <div class="chart-month">
                                <div class="chart-bars">
                                    <span class="chart-bar chart-bar-danger" style="height: {{ $appointmentHeight }}%" title="Qabul: {{ $month['count'] }}"></span>
                                    <span class="chart-bar chart-bar-success" style="height: {{ $resumeHeight }}%" title="Rezyume: {{ $resumeCount }}"></span>
                                </div>
                                <div class="text-center">
                                    <div class="fw-semibold">{{ $month['label'] }}</div>
                                    <small class="text-muted">{{ $month['count'] }} / {{ $resumeCount }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card dashboard-card">
                <div class="card-header border-0">
                    <h4 class="card-title mb-1">Kontent taqsimoti</h4>
                    <p class="text-muted mb-0">Saytda boshqarilayotgan asosiy bo‘limlar</p>
                </div>
                <div class="card-body">
                    @foreach ($contentCards as $card)
                        @php
                            $percent = round(($card['value'] / $totalContent) * 100);
                            $color = $colorMap[$card['color']] ?? '#405189';
                        @endphp
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-medium">{{ $card['label'] }}</span>
                                <span class="text-muted">{{ $card['value'] }} ta</span>
                            </div>
                            <div class="content-meter">
                                <span style="width: {{ $percent }}%; background: {{ $color }}"></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card dashboard-card">
                <div class="card-header border-0 d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-1">Oxirgi qabul so‘rovlari</h4>
                        <p class="text-muted mb-0">Saytdan yuborilgan yangi murojaatlar</p>
                    </div>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-soft-danger btn-sm">Barchasi</a>
                </div>
                <div class="card-body pt-0">
                    @forelse ($latestAppointments as $appointment)
                        <div class="activity-item">
                            <span class="activity-dot"></span>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between gap-3">
                                    <h6 class="mb-1">{{ $appointment->full_name }}</h6>
                                    <small class="text-muted">{{ $appointment->created_at?->format('d.m.Y') }}</small>
                                </div>
                                <div class="text-muted fs-13">{{ $appointment->phone }}</div>
                                <div class="fs-13">{{ $appointment->appointment_type }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted py-4 text-center">Qabul so‘rovlari hali yo‘q.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card dashboard-card">
                <div class="card-header border-0 d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-1">Oxirgi rezyumelar</h4>
                        <p class="text-muted mb-0">Ishga qabul uchun yuborilgan arizalar</p>
                    </div>
                    <a href="{{ route('admin.resumes.index') }}" class="btn btn-soft-success btn-sm">Barchasi</a>
                </div>
                <div class="card-body pt-0">
                    @forelse ($latestResumes as $resume)
                        <div class="activity-item">
                            <span class="activity-dot bg-success" style="box-shadow: 0 0 0 5px rgba(15, 159, 143, .12)"></span>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between gap-3">
                                    <h6 class="mb-1">{{ $resume->full_name }}</h6>
                                    <small class="text-muted">{{ $resume->created_at?->format('d.m.Y') }}</small>
                                </div>
                                <div class="text-muted fs-13">{{ $resume->phone }}</div>
                                <div class="fs-13">{{ $resume->position }} @if($resume->branch) · {{ $resume->branch }} @endif</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted py-4 text-center">Rezyumelar hali yo‘q.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
