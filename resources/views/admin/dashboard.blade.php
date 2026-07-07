@extends('layouts.admin')

@section('title', __('app.dashboard'))

@section('content')
    <h1 class="h3 mb-4">{{ __('app.dashboard') }}</h1>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card text-bg-primary shadow-sm">
                <div class="card-body">
                    <div class="small text-uppercase">{{ __('app.diamond_balance') }}</div>
                    <div class="fs-3 fw-bold"><i class="bi bi-gem me-1"></i>{{ number_format($settings->current_diamond_balance, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card text-bg-warning shadow-sm">
                <div class="card-body">
                    <div class="small text-uppercase">{{ __('app.pending_orders') }}</div>
                    <div class="fs-3 fw-bold">{{ $stats['pending_orders'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card text-bg-success shadow-sm">
                <div class="card-body">
                    <div class="small text-uppercase">{{ __('app.success_orders') }}</div>
                    <div class="fs-3 fw-bold">{{ $stats['success_orders'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card text-bg-secondary shadow-sm">
                <div class="card-body">
                    <div class="small text-uppercase">{{ __('app.total_skins') }}</div>
                    <div class="fs-3 fw-bold">{{ $stats['active_skins'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header fw-semibold">{{ __('app.pending_orders') }}</div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ __('app.order_code') }}</th>
                        <th>{{ __('app.skin') }}</th>
                        <th>{{ __('app.buyer') }}</th>
                        <th>{{ __('app.total_price') }}</th>
                        <th>{{ __('app.ordered_at') }}</th>
                        <th class="text-end">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentPending as $order)
                        <tr>
                            <td class="font-monospace">{{ $order->order_code }}</td>
                            <td>{{ $order->skin->name }}</td>
                            <td>{{ $order->buyer_ml_nickname }}</td>
                            <td>Rp {{ number_format($order->total_rupiah, 0, ',', '.') }}</td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                    {{ __('app.detail') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">{{ __('app.no_orders') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
