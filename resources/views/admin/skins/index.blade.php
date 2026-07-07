@extends('layouts.admin')

@section('title', __('app.skins'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ __('app.skins') }}</h1>
        <a href="{{ route('admin.skins.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>{{ __('app.add_skin') }}
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 70px;">{{ __('app.image') }}</th>
                        <th>{{ __('app.skin_name') }}</th>
                        <th>{{ __('app.hero_name') }}</th>
                        <th>{{ __('app.skin_type') }}</th>
                        <th>{{ __('app.price_diamond') }}</th>
                        <th>{{ __('app.price_rupiah') }}</th>
                        <th>{{ __('app.status') }}</th>
                        <th class="text-end">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($skins as $skin)
                        <tr>
                            <td>
                                @if ($skin->imageUrl())
                                    <img src="{{ $skin->imageUrl() }}" alt="{{ $skin->name }}" class="rounded"
                                         style="width: 56px; height: 56px; object-fit: cover;">
                                @else
                                    <div class="rounded bg-secondary-subtle d-flex align-items-center justify-content-center"
                                         style="width: 56px; height: 56px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $skin->name }}</td>
                            <td>{{ $skin->hero_name }}</td>
                            <td><span class="badge text-bg-secondary">{{ $skin->type }}</span></td>
                            <td><i class="bi bi-gem me-1"></i>{{ number_format($skin->price_diamond, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($skin->price_rupiah, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $skin->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $skin->is_active ? __('app.active') : __('app.inactive') }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.skins.edit', $skin) }}" class="btn btn-outline-primary">{{ __('app.edit') }}</a>
                                    <form method="POST" action="{{ route('admin.skins.destroy', $skin) }}"
                                          onsubmit="return confirm('{{ __('app.confirm_delete') }}');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger rounded-start-0">{{ __('app.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">{{ __('app.no_skins_admin') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($skins->hasPages())
            <div class="card-footer">
                {{ $skins->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
