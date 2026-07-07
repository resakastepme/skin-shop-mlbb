<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $orders = Order::query()
            ->with('skin')
            ->when(
                in_array($status, [Order::STATUS_PENDING, Order::STATUS_SUCCESS, Order::STATUS_CANCELED], true),
                fn ($query) => $query->where('status', $status),
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function show(Order $order): View
    {
        $order->load('skin');

        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order): View
    {
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Only buyer contact details are editable; totals and status are managed
     * by the checkout/approve/cancel flows to keep the balance consistent.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'buyer_name' => ['required', 'string', 'max:100'],
            'buyer_email' => ['required', 'email', 'max:255'],
            'buyer_whatsapp' => ['required', 'string', 'regex:/^[0-9+]{8,20}$/'],
            'buyer_ml_nickname' => ['required', 'string', 'max:100'],
            'buyer_ml_id' => ['required', 'string', 'regex:/^[0-9]{4,15}$/'],
            'buyer_ml_server' => ['required', 'string', 'regex:/^[0-9]{3,10}$/'],
        ]);

        $order->update($validated);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', __('app.order_updated', ['code' => $order->order_code]));
    }

    public function approve(Order $order): RedirectResponse
    {
        $approved = DB::transaction(function () use ($order): bool {
            $locked = Order::query()->whereKey($order->getKey())->lockForUpdate()->first();

            if (! $locked->isPending()) {
                return false;
            }

            // Diamonds were already deducted at checkout — no balance change here.
            $locked->update(['status' => Order::STATUS_SUCCESS]);

            return true;
        });

        if (! $approved) {
            return back()->with('error', __('app.only_pending_actionable'));
        }

        return back()->with('success', __('app.order_approved', ['code' => $order->order_code]));
    }

    public function cancel(Order $order): RedirectResponse
    {
        if (! $order->cancelAndRefund()) {
            return back()->with('error', __('app.only_pending_actionable'));
        }

        return back()->with('success', __('app.order_canceled', [
            'code' => $order->order_code,
            'diamond' => $order->total_diamond,
        ]));
    }

    public function destroy(Order $order): RedirectResponse
    {
        DB::transaction(function () use ($order): void {
            // A pending order still holds reserved diamonds — refund before deleting.
            $order->cancelAndRefund();
            $order->delete();
        });

        return redirect()
            ->route('admin.orders.index')
            ->with('success', __('app.order_deleted', ['code' => $order->order_code]));
    }
}
