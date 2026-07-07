<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use App\Models\Skin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(Skin $skin): View|RedirectResponse
    {
        $settings = Setting::current();

        if (! $skin->is_active) {
            return redirect()->route('home')->with('error', __('app.skin_unavailable'));
        }

        if ($skin->price_diamond > $settings->current_diamond_balance) {
            return redirect()->route('home')->with('error', __('app.insufficient_balance'));
        }

        return view('checkout', compact('skin', 'settings'));
    }

    public function store(Request $request, Skin $skin): RedirectResponse
    {
        $validated = $request->validate([
            'buyer_name' => ['required', 'string', 'max:100'],
            'buyer_email' => ['required', 'email', 'max:255'],
            'buyer_whatsapp' => ['required', 'string', 'regex:/^[0-9+]{8,20}$/'],
            'buyer_ml_nickname' => ['required', 'string', 'max:100'],
            'buyer_ml_id' => ['required', 'string', 'regex:/^[0-9]{4,15}$/'],
            'buyer_ml_server' => ['required', 'string', 'regex:/^[0-9]{3,10}$/'],
            'friend_agreement' => ['accepted'],
        ]);

        if (! $skin->is_active) {
            return redirect()->route('home')->with('error', __('app.skin_unavailable'));
        }

        // Reserve: deduct the balance up front so the same diamonds cannot be
        // sold twice while this order awaits payment (30-minute hold).
        $order = DB::transaction(function () use ($skin, $validated): ?Order {
            $settings = Setting::lockedForUpdate();

            if ($skin->price_diamond > $settings->current_diamond_balance) {
                return null;
            }

            $settings->decrement('current_diamond_balance', $skin->price_diamond);

            return Order::create([
                'order_code' => Order::generateOrderCode(),
                'skin_id' => $skin->id,
                'buyer_name' => $validated['buyer_name'],
                'buyer_email' => $validated['buyer_email'],
                'buyer_whatsapp' => $validated['buyer_whatsapp'],
                'buyer_ml_nickname' => $validated['buyer_ml_nickname'],
                'buyer_ml_id' => $validated['buyer_ml_id'],
                'buyer_ml_server' => $validated['buyer_ml_server'],
                'total_diamond' => $skin->price_diamond,
                'total_rupiah' => $skin->price_rupiah,
                'status' => Order::STATUS_PENDING,
            ]);
        });

        if (! $order) {
            return redirect()->route('home')->with('error', __('app.insufficient_balance'));
        }

        return redirect()
            ->route('orders.show', $order->order_code)
            ->with('order_created', true);
    }
}
