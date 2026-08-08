@extends('admin.layout')

@section('title', 'Xizmatlar bahosi')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0" style="border-radius:18px;background:linear-gradient(135deg,#b7212d,#df4250);color:#fff">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8"><h3 class="text-white mb-2">Klinika xizmatlari bahosi</h3><p class="mb-0 opacity-75">Sayt tashrifchilarining umumiy fikri va so‘nggi ovozlari.</p></div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0"><span class="display-5 fw-bold">{{ number_format($average, 1) }}</span><span class="fs-4"> / 5</span><div>{{ $total }} ta ovoz</div></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100"><div class="card-body p-4">
            <h5 class="mb-4">Baholar taqsimoti</h5>
            @foreach ($results as $result)
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:72px"><strong>{{ $result['score'] }}</strong> <span class="text-warning">★</span></div>
                    <div class="progress flex-grow-1" style="height:12px"><div class="progress-bar bg-danger" style="width:{{ $result['percent'] }}%"></div></div>
                    <div class="text-end" style="width:105px"><strong>{{ $result['percent'] }}%</strong> <small class="text-muted">({{ $result['count'] }})</small></div>
                </div>
            @endforeach
        </div></div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100"><div class="card-body p-4 text-center d-flex flex-column justify-content-center">
            <div class="avatar-lg mx-auto mb-3"><span class="avatar-title rounded-circle bg-danger-subtle text-danger fs-2"><i class="ri-emotion-happy-line"></i></span></div>
            <div class="display-6 fw-bold">{{ $today }}</div><div class="text-muted">Bugun berilgan ovozlar</div>
        </div></div>
    </div>
</div>
<div class="card border-0 shadow-sm mt-4"><div class="card-header bg-white"><h5 class="mb-0">So‘nggi ovozlar</h5></div><div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Baho</th><th>Til</th><th>Sana</th></tr></thead><tbody>
@forelse ($recent as $rating)<tr><td><span class="badge bg-danger-subtle text-danger fs-13">{{ $rating->score }} ★</span></td><td>{{ strtoupper($rating->locale) }}</td><td>{{ $rating->created_at->format('d.m.Y H:i') }}</td></tr>@empty<tr><td colspan="3" class="text-center text-muted py-4">Hozircha ovozlar yo‘q.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
