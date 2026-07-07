@extends('layouts.admin')

@section('title', __('app.orders'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ __('app.orders') }}</h1>
        <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">{{ __('app.all_statuses') }}</option>
                @foreach ([\App\Models\Order::STATUS_PENDING, \App\Models\Order::STATUS_SUCCESS, \App\Models\Order::STATUS_CANCELED] as $option)
                    <option value="{{ $option }}" @selected($status === $option)>{{ __('app.status_'.$option) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-outline-secondary">{{ __('app.filter') }}</button>
        </form>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ __('app.order_code') }}</th>
                        <th>{{ __('app.skin') }}</th>
                        <th>{{ __('app.buyer') }}</th>
                        <th>{{ __('app.total_price') }}</th>
                        <th>{{ __('app.status') }}</th>
                        <th>{{ __('app.ordered_at') }}</th>
                        <th class="text-end">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td class="font-monospace">{{ $order->order_code }}</td>
                            <td>{{ $order->skin->name }}</td>
                            <td>{{ $order->buyer_ml_nickname }}</td>
                            <td>Rp {{ number_format($order->total_rupiah, 0, ',', '.') }}</td>
                            <td>
                                @include('admin.orders.partials.status-badge', ['order' => $order])
                            </td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary">{{ __('app.detail') }}</a>
                                    @if ($order->isPending())
                                        <form method="POST" action="{{ route('admin.orders.approve', $order) }}"
                                              onsubmit="return confirm('{{ __('app.confirm_approve') }}');">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-success rounded-0">{{ __('app.approve') }}</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.orders.cancel', $order) }}"
                                              onsubmit="return confirm('{{ __('app.confirm_cancel') }}');">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-danger rounded-start-0">{{ __('app.cancel') }}</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">{{ __('app.no_orders') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($orders->hasPages())
            <div class="card-footer">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
