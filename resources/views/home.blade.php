@extends('layouts.app')

@section('title', __('app.hero_title').' — '.config('app.name'))

@section('content')
    <!-- Hero Start -->
    <div class="container-fluid py-5 mb-5 hero-header">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-md-12 col-lg-8">
                    <h4 class="mb-3 text-secondary">{{ __('app.hero_tagline') }}</h4>
                    <h1 class="mb-4 display-3 text-white">{{ __('app.hero_title') }}</h1>
                    <p class="text-white-50 mb-4">{{ __('app.hero_subtitle') }}</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#catalog" class="btn btn-secondary rounded-pill py-3 px-5">
                            <i class="fas fa-gem me-2"></i>{{ __('app.hero_cta_catalog') }}
                        </a>
                        <a href="{{ route('orders.track') }}" class="btn btn-outline-light rounded-pill py-3 px-5">
                            {{ __('app.hero_cta_track') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Trust Features Start -->
    <div class="container-fluid featurs py-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4 h-100">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-shield-alt fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>{{ __('app.feature_secure_title') }}</h5>
                            <p class="mb-0">{{ __('app.feature_secure_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4 h-100">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-lock fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>{{ __('app.feature_reserve_title') }}</h5>
                            <p class="mb-0">{{ __('app.feature_reserve_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4 h-100">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-undo-alt fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>{{ __('app.feature_refund_title') }}</h5>
                            <p class="mb-0">{{ __('app.feature_refund_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4 h-100">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-gift fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>{{ __('app.feature_fast_title') }}</h5>
                            <p class="mb-0">{{ __('app.feature_fast_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Trust Features End -->

    <!-- Skins Catalog Start -->
    <div class="container-fluid fruite py-5" id="catalog">
        <div class="container py-5">
            <div class="tab-class text-center">
                <div class="row g-4">
                    <div class="col-lg-4 text-start">
                        <h1>{{ __('app.available_skins') }}</h1>
                    </div>
                    <div class="col-lg-8 text-end">
                        <ul class="nav nav-pills d-inline-flex text-center mb-5">
                            <li class="nav-item">
                                <a class="d-flex m-2 py-2 px-4 bg-light rounded-pill {{ $type === null ? 'active' : '' }}" href="{{ route('home') }}#catalog">
                                    <span class="text-dark">{{ __('app.all_types') }}</span>
                                </a>
                            </li>
                            @foreach ($types as $skinType)
                                <li class="nav-item">
                                    <a class="d-flex m-2 py-2 px-4 bg-light rounded-pill {{ $type === $skinType ? 'active' : '' }}"
                                       href="{{ route('home', ['type' => $skinType]) }}#catalog">
                                        <span class="text-dark">{{ $skinType }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="row g-4">
                    @forelse ($skins as $skin)
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="rounded position-relative fruite-item h-100 d-flex flex-column">
                                <div class="fruite-img">
                                    @if ($skin->imageUrl())
                                        <img src="{{ $skin->imageUrl() }}" class="img-fluid w-100 rounded-top" alt="{{ $skin->name }}">
                                    @else
                                        <div class="placeholder-img rounded-top">
                                            <i class="fas fa-image fa-3x text-white-50"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">{{ $skin->type }}</div>
                                <div class="p-4 border border-secondary border-top-0 rounded-bottom text-start flex-grow-1 d-flex flex-column">
                                    <h4>{{ $skin->name }}</h4>
                                    <p><i class="fas fa-user me-1 text-primary"></i>{{ $skin->hero_name }}</p>
                                    <div class="mt-auto">
                                        <p class="diamond-chip fs-5 mb-1"><i class="fas fa-gem me-1"></i>{{ number_format($skin->price_diamond, 0, ',', '.') }} {{ __('app.diamonds') }}</p>
                                        <div class="d-flex justify-content-between align-items-center flex-lg-wrap gap-2">
                                            <p class="text-dark fs-5 fw-bold mb-0">Rp {{ number_format($skin->price_rupiah, 0, ',', '.') }}</p>
                                            <a href="{{ route('checkout.create', $skin) }}" class="btn border border-secondary rounded-pill px-3 text-primary">
                                                <i class="fa fa-shopping-bag me-2 text-primary"></i>{{ __('app.order_now') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">{{ __('app.no_skins') }}</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <!-- Skins Catalog End -->

    <!-- Trust Banner Start -->
    <div class="container-fluid banner bg-secondary my-5">
        <div class="container py-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <div class="py-4">
                        <h1 class="display-4 text-white">{{ __('app.banner_title') }}</h1>
                        <p class="fw-normal fs-4 text-dark mb-4">{{ __('app.banner_subtitle') }}</p>
                        <ul class="list-unstyled text-dark mb-4">
                            <li class="mb-2"><i class="fas fa-check-circle me-2 text-white"></i>{{ __('app.banner_point_1') }}</li>
                            <li class="mb-2"><i class="fas fa-check-circle me-2 text-white"></i>{{ __('app.banner_point_2') }}</li>
                            <li class="mb-2"><i class="fas fa-check-circle me-2 text-white"></i>{{ __('app.banner_point_3') }}</li>
                        </ul>
                        <a href="#catalog" class="banner-btn btn border-2 border-white rounded-pill text-dark py-3 px-5">{{ __('app.banner_cta') }}</a>
                    </div>
                </div>
                <div class="col-lg-5 text-center">
                    <div class="position-relative d-inline-block">
                        <div class="d-flex align-items-center justify-content-center bg-dark rounded-circle mx-auto" style="width: 220px; height: 220px;">
                            <div>
                                <i class="fas fa-gem fa-4x text-secondary mb-2"></i>
                                <h4 class="text-white mb-0">{{ number_format($settings->current_diamond_balance, 0, ',', '.') }}</h4>
                                <small class="text-white-50">{{ __('app.diamonds') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Trust Banner End -->
@endsection
