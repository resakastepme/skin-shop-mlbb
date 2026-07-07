<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Skin;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $settings = Setting::current();

        $stats = [
            'pending_orders' => Order::query()->pending()->count(),
            'success_orders' => Order::query()->where('status', Order::STATUS_SUCCESS)->count(),
            'active_skins' => Skin::query()->active()->count(),
        ];

        $recentPending = Order::query()
            ->with('skin')
            ->pending()
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('settings', 'stats', 'recentPending'));
    }
}
