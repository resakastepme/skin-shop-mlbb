<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Public order-code search form (GET /track-order).
     */
    public function track(Request $request): View|RedirectResponse
    {
        $code = trim((string) $request->query('code'));

        if ($code !== '') {
            $order = Order::query()->where('order_code', $code)->first();

            if ($order) {
                return redirect()->route('orders.show', $order->order_code);
            }

            return view('orders.track', ['notFound' => true, 'code' => $code]);
        }

        return view('orders.track', ['notFound' => false, 'code' => '']);
    }

    /**
     * Public order detail (GET /order/{order_code}).
     */
    public function show(string $orderCode): View
    {
        $order = Order::query()
            ->with('skin')
            ->where('order_code', $orderCode)
            ->firstOrFail();

        $settings = Setting::current();

        $waMessage = __('app.wa_message', [
            'code' => $order->order_code,
            'skin' => $order->skin->name,
            'hero' => $order->skin->hero_name,
            'price' => 'Rp '.number_format($order->total_rupiah, 0, ',', '.'),
            'nickname' => $order->buyer_ml_nickname,
            'ml_id' => $order->buyer_ml_id,
            'server' => $order->buyer_ml_server,
        ]);

        $waUrl = 'https://wa.me/'.preg_replace('/\D/', '', $settings->whatsapp_number)
            .'?text='.rawurlencode($waMessage);

        return view('orders.show', compact('order', 'settings', 'waUrl'));
    }
}
