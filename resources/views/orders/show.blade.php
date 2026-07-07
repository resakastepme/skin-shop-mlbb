@extends('layouts.app')

@section('title', __('app.order_details').' '.$order->order_code.' — '.config('app.name'))

@php
    $statusBadge = [
        \App\Models\Order::STATUS_PENDING => 'text-bg-warning',
        \App\Models\Order::STATUS_SUCCESS => 'text-bg-success',
        \App\Models\Order::STATUS_CANCELED => 'text-bg-danger',
    ][$order->status] ?? 'text-bg-secondary';
@endphp

@section('content')
<div class="container page-mt py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if (session('order_created'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-1"></i>{{ __('app.order_created') }}
                    <div class="small mt-1">{{ __('app.save_order_code') }}</div>
                </div>
            @endif

            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">{{ __('app.order_details') }}</span>
                    <span class="badge {{ $statusBadge }}">{{ __('app.status_'.$order->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <div class="text-muted small">{{ __('app.order_code') }}</div>
                            <div class="fs-5 fw-bold font-monospace">{{ $order->order_code }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">{{ __('app.ordered_at') }}</div>
                            <div>{{ $order->created_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>

                    @if ($order->isPending())
                        <div class="alert alert-warning small mb-3">
                            <i class="bi bi-clock-history me-1"></i>
                            {{ __('app.reservation_expires_at') }}:
                            <strong>{{ $order->reservationExpiresAt()->format('d M Y H:i') }}</strong>
                        </div>
                    @endif

                    <table class="table table-sm align-middle mb-0">
                        <tbody>
                            <tr>
                                <th class="text-muted fw-normal w-50">{{ __('app.skin') }}</th>
                                <td>{{ $order->skin->name }} ({{ $order->skin->hero_name }})</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-normal">{{ __('app.total_diamond') }}</th>
                                <td><i class="bi bi-gem me-1"></i>{{ number_format($order->total_diamond, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-normal">{{ __('app.total_price') }}</th>
                                <td class="fw-bold">Rp {{ number_format($order->total_rupiah, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-normal">{{ __('app.buyer') }}</th>
                                <td>{{ $order->buyer_ml_nickname }} ({{ $order->buyer_ml_id }} / {{ $order->buyer_ml_server }})</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-normal">{{ __('app.email') }}</th>
                                <td>{{ $order->maskedEmail() }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-normal">{{ __('app.whatsapp_number') }}</th>
                                <td>{{ $order->maskedWhatsapp() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($order->isPending())
                <div class="card shadow-sm mb-4">
                    <div class="card-header fw-semibold">{{ __('app.payment_instructions') }}</div>
                    <div class="card-body">
                        <ol class="mb-3">
                            <li>{{ __('app.pay_via_dana', ['amount' => 'Rp '.number_format($order->total_rupiah, 0, ',', '.'), 'number' => $settings->dana_number]) }}</li>
                            <li>{{ __('app.then_confirm_wa') }}</li>
                        </ol>
                        <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-whatsapp me-1"></i>{{ __('app.contact_admin_whatsapp') }}
                        </a>
                    </div>
                </div>
            @endif

            <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i>{{ __('app.back_to_home') }}
            </a>
        </div>
    </div>
</div>
@endsection
