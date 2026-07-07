@extends('layouts.app')

@section('title', __('app.track_your_order').' — '.config('app.name'))

@section('content')
<div class="container page-mt py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3">{{ __('app.track_your_order') }}</h1>
                    <form method="GET" action="{{ route('orders.track') }}">
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control" name="code" value="{{ $code }}"
                                   placeholder="{{ __('app.enter_order_code') }}" required>
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search me-1"></i>{{ __('app.search') }}
                            </button>
                        </div>
                    </form>
                    @if ($notFound)
                        <div class="alert alert-danger mt-3 mb-0">{{ __('app.order_not_found') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
