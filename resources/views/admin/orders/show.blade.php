@extends('layouts.admin')

@section('title', __('app.order_details').' '.$order->order_code)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            {{ __('app.order_details') }}
            <span class="font-monospace fs-5 text-muted">{{ $order->order_code }}</span>
        </h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>{{ __('app.back') }}
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">{{ __('app.detail') }}</span>
                    @include('admin.orders.partials.status-badge', ['order' => $order])
                </div>
                <div class="card-body">
                    <table class="table table-sm align-middle mb-0">
                        <tbody>
                            <tr><th class="text-muted fw-normal w-33">{{ __('app.skin') }}</th><td>{{ $order->skin->name }} ({{ $order->skin->hero_name }})</td></tr>
                            <tr><th class="text-muted fw-normal">{{ __('app.total_diamond') }}</th><td>{{ number_format($order->total_diamond, 0, ',', '.') }}</td></tr>
                            <tr><th class="text-muted fw-normal">{{ __('app.total_price') }}</th><td class="fw-bold">Rp {{ number_format($order->total_rupiah, 0, ',', '.') }}</td></tr>
                            <tr><th class="text-muted fw-normal">{{ __('app.name') }}</th><td>{{ $order->buyer_name }}</td></tr>
                            <tr><th class="text-muted fw-normal">{{ __('app.email') }}</th><td>{{ $order->buyer_email }}</td></tr>
                            <tr><th class="text-muted fw-normal">{{ __('app.whatsapp_number') }}</th><td>{{ $order->buyer_whatsapp }}</td></tr>
                            <tr><th class="text-muted fw-normal">{{ __('app.ml_nickname') }}</th><td>{{ $order->buyer_ml_nickname }}</td></tr>
                            <tr><th class="text-muted fw-normal">{{ __('app.ml_id') }}</th><td>{{ $order->buyer_ml_id }} ({{ $order->buyer_ml_server }})</td></tr>
                            <tr><th class="text-muted fw-normal">{{ __('app.ordered_at') }}</th><td>{{ $order->created_at->format('d M Y H:i:s') }}</td></tr>
                            @if ($order->isPending())
                                <tr><th class="text-muted fw-normal">{{ __('app.reservation_expires_at') }}</th><td>{{ $order->reservationExpiresAt()->format('d M Y H:i:s') }}</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">{{ __('app.actions') }}</div>
                <div class="card-body d-grid gap-2">
                    @if ($order->isPending())
                        <form method="POST" action="{{ route('admin.orders.approve', $order) }}"
                              onsubmit="return confirm('{{ __('app.confirm_approve') }}');">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-lg me-1"></i>{{ __('app.approve') }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.orders.cancel', $order) }}"
                              onsubmit="return confirm('{{ __('app.confirm_cancel') }}');">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="bi bi-x-lg me-1"></i>{{ __('app.cancel_order') }}
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i>{{ __('app.edit') }}
                    </a>
                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}"
                          onsubmit="return confirm('{{ __('app.confirm_delete') }}');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash me-1"></i>{{ __('app.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
