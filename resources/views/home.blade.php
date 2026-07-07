@extends('layouts.app')

@section('title', __('app.hero_title').' — '.config('app.name'))

@section('content')
    <div class="p-4 p-md-5 mb-4 rounded-3 text-white" style="background: linear-gradient(135deg, #1a1a2e, #6f42c1);">
        <div class="col-lg-8">
            <h1 class="display-6 fw-bold">{{ __('app.hero_title') }}</h1>
            <p class="lead mb-0">{{ __('app.hero_subtitle') }}</p>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">{{ __('app.available_skins') }}</h2>
    </div>

    @if ($skins->isEmpty())
        <div class="alert alert-info">{{ __('app.no_skins') }}</div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
            @foreach ($skins as $skin)
                <div class="col">
                    <div class="card h-100 shadow-sm skin-card">
                        @if ($skin->imageUrl())
                            <img src="{{ $skin->imageUrl() }}" class="card-img-top" alt="{{ $skin->name }}">
                        @else
                            <div class="card-img-top placeholder-img d-flex align-items-center justify-content-center">
                                <i class="bi bi-image text-white-50 fs-1"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-1">{{ $skin->name }}</h5>
                            <p class="text-muted small mb-2">{{ $skin->hero_name }}</p>
                            <span class="badge text-bg-secondary align-self-start mb-3">{{ $skin->type }}</span>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-semibold text-primary">
                                        <i class="bi bi-gem me-1"></i>{{ number_format($skin->price_diamond, 0, ',', '.') }}
                                    </span>
                                    <span class="fw-bold">Rp {{ number_format($skin->price_rupiah, 0, ',', '.') }}</span>
                                </div>
                                <a href="{{ route('checkout.create', $skin) }}" class="btn btn-primary w-100">
                                    {{ __('app.order_now') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
