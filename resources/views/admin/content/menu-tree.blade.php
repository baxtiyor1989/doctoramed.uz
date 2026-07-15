@foreach ($menus as $menu)
    <div class="admin-menu-node">
        <div class="admin-menu-row">
            <div class="admin-menu-main">
                <span class="admin-menu-icon">
                    <i class="{{ $level === 0 ? 'ri-menu-line' : 'ri-corner-down-right-line' }}"></i>
                </span>
                <div>
                    <div class="fw-semibold">{{ $menu->title }}</div>
                    <div class="text-muted fs-12">
                        {{ $level === 0 ? 'Parent menu' : $level.'-daraja submenu' }} · {{ $menu->url }}
                    </div>
                </div>
            </div>
            <div class="admin-menu-meta">
                <span class="badge bg-light text-dark">Tartib: {{ $menu->sort_order }}</span>
                <span class="badge bg-{{ $menu->is_active ? 'success' : 'secondary' }}">
                    {{ $menu->is_active ? 'Faol' : 'O‘chiq' }}
                </span>
                <a href="{{ route('admin.content.edit', [$resource, $menu]) }}" class="btn btn-sm btn-primary">Tahrirlash</a>
                <form method="POST" action="{{ route('admin.content.destroy', [$resource, $menu]) }}" class="d-inline" onsubmit="return confirm('O‘chirasizmi?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" type="submit">O‘chirish</button>
                </form>
            </div>
        </div>

        @if ($menu->childrenRecursive->isNotEmpty())
            <div class="admin-menu-children">
                @include('admin.content.menu-tree', ['menus' => $menu->childrenRecursive, 'resource' => $resource, 'level' => $level + 1])
            </div>
        @endif
    </div>
@endforeach
