<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Skin;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $settings = Setting::current();

        $skins = Skin::query()
            ->active()
            ->affordable($settings->current_diamond_balance)
            ->orderByDesc('price_diamond')
            ->get();

        return view('home', compact('settings', 'skins'));
    }
}
