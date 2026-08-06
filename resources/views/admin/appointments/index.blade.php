@extends('admin.layout')

@section('title', 'Qabul so‘rovlari')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>F.I.Sh</th>
                            <th>Tug‘ilgan sana</th>
                            <th>Viloyat, tuman</th>
                            <th>Telefon</th>
                            <th>Yo‘nalish</th>
                            <th>Shikoyatlar</th>
                            <th>Sana</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>
                                    {{ $item->full_name }}
                                    @if (!$item->viewed_at)<span class="badge bg-danger ms-1">Yangi</span>@endif
                                </td>
                                <td>{{ optional($item->birth_date)->format('d.m.Y') }}</td>
                                <td>
                                    <div>{{ $item->region_district }}</div>
                                    @if ($item->address)<small class="text-muted">{{ $item->address }}</small>@endif
                                </td>
                                <td><a href="tel:{{ preg_replace('/\D+/', '', $item->phone) }}">{{ $item->phone }}</a></td>
                                <td>{{ $item->appointment_type }}</td>
                                <td style="min-width: 220px">{{ $item->complaint ?: '—' }}</td>
                                <td>{{ $item->created_at->format('d.m.Y H:i') }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('admin.appointments.destroy', $item) }}" onsubmit="return confirm('O‘chirasizmi?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">O‘chirish</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Qabul so‘rovlari yo‘q.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $items->links() }}
        </div>
    </div>
@endsection
