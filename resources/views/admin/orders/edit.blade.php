@extends('layouts.admin')

@section('title', __('app.edit_order').' '.$order->order_code)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            {{ __('app.edit_order') }}
            <span class="font-monospace fs-5 text-muted">{{ $order->order_code }}</span>
        </h1>
        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>{{ __('app.back') }}
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="buyer_name" class="form-label">{{ __('app.name') }}</label>
                        <input type="text" class="form-control @error('buyer_name') is-invalid @enderror"
                               id="buyer_name" name="buyer_name" value="{{ old('buyer_name', $order->buyer_name) }}" required>
                        @error('buyer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="buyer_email" class="form-label">{{ __('app.email') }}</label>
                        <input type="email" class="form-control @error('buyer_email') is-invalid @enderror"
                               id="buyer_email" name="buyer_email" value="{{ old('buyer_email', $order->buyer_email) }}" required>
                        @error('buyer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="buyer_whatsapp" class="form-label">{{ __('app.whatsapp_number') }}</label>
                        <input type="text" class="form-control @error('buyer_whatsapp') is-invalid @enderror"
                               id="buyer_whatsapp" name="buyer_whatsapp" value="{{ old('buyer_whatsapp', $order->buyer_whatsapp) }}" required>
                        @error('buyer_whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="buyer_ml_nickname" class="form-label">{{ __('app.ml_nickname') }}</label>
                        <input type="text" class="form-control @error('buyer_ml_nickname') is-invalid @enderror"
                               id="buyer_ml_nickname" name="buyer_ml_nickname" value="{{ old('buyer_ml_nickname', $order->buyer_ml_nickname) }}" required>
                        @error('buyer_ml_nickname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="buyer_ml_id" class="form-label">{{ __('app.ml_id') }}</label>
                        <input type="text" class="form-control @error('buyer_ml_id') is-invalid @enderror"
                               id="buyer_ml_id" name="buyer_ml_id" value="{{ old('buyer_ml_id', $order->buyer_ml_id) }}" required>
                        @error('buyer_ml_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="buyer_ml_server" class="form-label">{{ __('app.ml_server') }}</label>
                        <input type="text" class="form-control @error('buyer_ml_server') is-invalid @enderror"
                               id="buyer_ml_server" name="buyer_ml_server" value="{{ old('buyer_ml_server', $order->buyer_ml_server) }}" required>
                        @error('buyer_ml_server')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
