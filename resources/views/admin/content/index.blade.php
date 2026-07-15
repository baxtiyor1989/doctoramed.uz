@extends('admin.layout')

@section('title', $config['title'])

@section('actions')
    <a href="{{ route('admin.content.create', $resource) }}" class="btn btn-success">
        <i class="ri-add-line align-bottom"></i> Qo‘shish
    </a>
@endsection

@section('content')
    @push('styles')
        <style>
            .admin-menu-tree {
                display: grid;
                gap: 12px;
            }

            .admin-menu-node {
                position: relative;
                display: grid;
                gap: 10px;
            }

            .admin-menu-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                padding: 14px;
                border: 1px solid rgba(183, 33, 45, .12);
                border-radius: 14px;
                background: #fff;
                box-shadow: 0 8px 24px rgba(15, 23, 42, .04);
            }

            .admin-menu-main,
            .admin-menu-meta {
                display: flex;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
            }

            .admin-menu-icon {
                width: 34px;
                height: 34px;
                display: grid;
                place-items: center;
                border-radius: 11px;
                background: rgba(183, 33, 45, .1);
                color: #b7212d;
                flex: 0 0 auto;
            }

            .admin-menu-children {
                position: relative;
                display: grid;
                gap: 10px;
                margin-left: 28px;
                padding-left: 22px;
                border-left: 2px dashed rgba(183, 33, 45, .22);
            }

            .admin-menu-children > .admin-menu-node::before {
                content: "";
                position: absolute;
                top: 24px;
                left: -22px;
                width: 18px;
                border-top: 2px dashed rgba(183, 33, 45, .22);
            }

            .menu-row-title {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .menu-row-title.is-child {
                padding-left: calc(var(--menu-level, 1) * 28px);
            }

            .menu-child-arrow {
                width: 22px;
                height: 22px;
                display: inline-grid;
                place-items: center;
                border-radius: 50%;
                background: rgba(183, 33, 45, .1);
                color: #b7212d;
                font-size: 14px;
                flex: 0 0 auto;
            }

            .menu-title-text {
                display: grid;
                gap: 3px;
            }
        </style>
    @endpush

    @if ($resource === 'menus')
        <div class="card">
            <div class="card-body">
                @if ($items->isNotEmpty())
                    <div class="admin-menu-tree">
                        @include('admin.content.menu-tree', ['menus' => $items, 'resource' => $resource, 'level' => 0])
                    </div>
                @else
                    <div class="text-center text-muted py-4">Ma’lumot yo‘q.</div>
                @endif
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap">
                        <thead>
                            <tr>
                                <th>Nomi</th>
                                <th>Tartib</th>
                                <th>Holat</th>
                                <th class="text-end">Amallar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $item->title ?? $item->name }}</div>
                                        @if ($resource === 'services')
                                            <small class="text-muted">
                                                Menu: {{ $item->menuItem?->title ?? 'Tanlanmagan' }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>{{ $item->sort_order }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->is_active ? 'success' : 'secondary' }}">
                                            {{ $item->is_active ? 'Faol' : 'O‘chiq' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.content.edit', [$resource, $item]) }}" class="btn btn-sm btn-primary">Tahrirlash</a>
                                        <form method="POST" action="{{ route('admin.content.destroy', [$resource, $item]) }}" class="d-inline" onsubmit="return confirm('O‘chirasizmi?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit">O‘chirish</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">Ma’lumot yo‘q.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
