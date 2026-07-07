<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Skin;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $settings = Setting::current();

        $available = Skin::query()
            ->active()
            ->affordable($settings->current_diamond_balance);

        $types = (clone $available)->select('type')->distinct()->orderBy('type')->pluck('type');

        $type = $request->query('type');
        $type = $types->contains($type) ? $type : null;

        $skins = (clone $available)
            ->when($type, fn ($query) => $query->where('type', $type))
            ->orderByDesc('price_diamond')
            ->get();

        return view('home', compact('settings', 'skins', 'types', 'type'));
    }
}
