<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $settings = Setting::current();

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'admin_ml_id' => ['required', 'string', 'max:50'],
            'admin_ml_nickname' => ['required', 'string', 'max:100'],
            'dana_number' => ['required', 'string', 'max:30'],
            'whatsapp_number' => ['required', 'string', 'max:30'],
            'current_diamond_balance' => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($validated): void {
            Setting::lockedForUpdate()->update($validated);
        });

        return back()->with('success', __('app.settings_updated'));
    }
}
