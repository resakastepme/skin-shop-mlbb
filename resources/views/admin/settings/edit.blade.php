@extends('layouts.admin')

@section('title', __('app.store_settings'))

@section('content')
    <h1 class="h3 mb-4">{{ __('app.store_settings') }}</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="admin_ml_id" class="form-label">{{ __('app.admin_ml_id') }}</label>
                        <input type="text" class="form-control @error('admin_ml_id') is-invalid @enderror"
                               id="admin_ml_id" name="admin_ml_id" value="{{ old('admin_ml_id', $settings->admin_ml_id) }}" required>
                        @error('admin_ml_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="admin_ml_nickname" class="form-label">{{ __('app.admin_ml_nickname') }}</label>
                        <input type="text" class="form-control @error('admin_ml_nickname') is-invalid @enderror"
                               id="admin_ml_nickname" name="admin_ml_nickname" value="{{ old('admin_ml_nickname', $settings->admin_ml_nickname) }}" required>
                        @error('admin_ml_nickname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="dana_number" class="form-label">{{ __('app.dana_number') }}</label>
                        <input type="text" class="form-control @error('dana_number') is-invalid @enderror"
                               id="dana_number" name="dana_number" value="{{ old('dana_number', $settings->dana_number) }}" required>
                        @error('dana_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="whatsapp_number" class="form-label">{{ __('app.whatsapp_number_admin') }}</label>
                        <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror"
                               id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $settings->whatsapp_number) }}"
                               placeholder="628xxxxxxxxxx" required>
                        @error('whatsapp_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="current_diamond_balance" class="form-label">{{ __('app.current_diamond_balance') }}</label>
                        <input type="number" min="0" class="form-control @error('current_diamond_balance') is-invalid @enderror"
                               id="current_diamond_balance" name="current_diamond_balance"
                               value="{{ old('current_diamond_balance', $settings->current_diamond_balance) }}" required>
                        @error('current_diamond_balance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">{{ __('app.balance_note') }}</div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-4">{{ __('app.save') }}</button>
            </form>
        </div>
    </div>
@endsection
