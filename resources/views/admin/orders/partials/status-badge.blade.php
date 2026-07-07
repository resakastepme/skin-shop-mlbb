@php
    $badgeClass = [
        \App\Models\Order::STATUS_PENDING => 'text-bg-warning',
        \App\Models\Order::STATUS_SUCCESS => 'text-bg-success',
        \App\Models\Order::STATUS_CANCELED => 'text-bg-danger',
    ][$order->status] ?? 'text-bg-secondary';
@endphp
<span class="badge {{ $badgeClass }}">{{ __('app.status_'.$order->status) }}</span>
