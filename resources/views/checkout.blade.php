@extends('layouts.app')

@section('title', __('app.checkout').' — '.config('app.name'))

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('app.home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('app.checkout') }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">{{ __('app.order_summary') }}</div>
                @if ($skin->imageUrl())
                    <img src="{{ $skin->imageUrl() }}" class="card-img-top" alt="{{ $skin->name }}" style="object-fit: cover; max-height: 220px;">
                @endif
                <div class="card-body">
                    <h5 class="card-title mb-1">{{ $skin->name }}</h5>
                    <p class="text-muted small mb-2">{{ $skin->hero_name }} &middot; {{ $skin->type }}</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>{{ __('app.diamonds') }}</span>
                            <span class="fw-semibold"><i class="bi bi-gem me-1"></i>{{ number_format($skin->price_diamond, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>{{ __('app.price') }}</span>
                            <span class="fw-bold">Rp {{ number_format($skin->price_rupiah, 0, ',', '.') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="alert alert-warning mt-3 small">
                <i class="bi bi-clock-history me-1"></i>
                {{ __('app.checkout_note', ['minutes' => \App\Models\Order::RESERVATION_MINUTES]) }}
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">{{ __('app.your_details') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('checkout.store', $skin) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="buyer_name" class="form-label">{{ __('app.name') }}</label>
                                <input type="text" class="form-control @error('buyer_name') is-invalid @enderror"
                                       id="buyer_name" name="buyer_name" value="{{ old('buyer_name') }}" required>
                                @error('buyer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="buyer_email" class="form-label">{{ __('app.email') }}</label>
                                <input type="email" class="form-control @error('buyer_email') is-invalid @enderror"
                                       id="buyer_email" name="buyer_email" value="{{ old('buyer_email') }}" required>
                                @error('buyer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="buyer_whatsapp" class="form-label">{{ __('app.whatsapp_number') }}</label>
                                <input type="text" class="form-control @error('buyer_whatsapp') is-invalid @enderror"
                                       id="buyer_whatsapp" name="buyer_whatsapp" value="{{ old('buyer_whatsapp') }}"
                                       placeholder="08xxxxxxxxxx" required>
                                @error('buyer_whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="buyer_ml_nickname" class="form-label">{{ __('app.ml_nickname') }}</label>
                                <input type="text" class="form-control @error('buyer_ml_nickname') is-invalid @enderror"
                                       id="buyer_ml_nickname" name="buyer_ml_nickname" value="{{ old('buyer_ml_nickname') }}" required>
                                @error('buyer_ml_nickname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="buyer_ml_id" class="form-label">{{ __('app.ml_id') }}</label>
                                <input type="text" class="form-control @error('buyer_ml_id') is-invalid @enderror"
                                       id="buyer_ml_id" name="buyer_ml_id" value="{{ old('buyer_ml_id') }}"
                                       placeholder="123456789" required>
                                @error('buyer_ml_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="buyer_ml_server" class="form-label">{{ __('app.ml_server') }}</label>
                                <input type="text" class="form-control @error('buyer_ml_server') is-invalid @enderror"
                                       id="buyer_ml_server" name="buyer_ml_server" value="{{ old('buyer_ml_server') }}"
                                       placeholder="1234" required>
                                @error('buyer_ml_server')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-check mt-4">
                            <input class="form-check-input @error('friend_agreement') is-invalid @enderror" type="checkbox"
                                   id="friend_agreement" name="friend_agreement" value="1" required>
                            <label class="form-check-label" for="friend_agreement">
                                {{ __('app.friend_agreement', ['nickname' => $settings->admin_ml_nickname, 'id' => $settings->admin_ml_id]) }}
                            </label>
                            @error('friend_agreement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">
                            <i class="bi bi-whatsapp me-1"></i>{{ __('app.submit_order') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
