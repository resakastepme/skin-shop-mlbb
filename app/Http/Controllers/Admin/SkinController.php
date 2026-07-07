<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SkinController extends Controller
{
    public function index(): View
    {
        $skins = Skin::query()->latest()->paginate(15);

        return view('admin.skins.index', compact('skins'));
    }

    public function create(): View
    {
        return view('admin.skins.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('skins', 'public');
        }

        $skin = Skin::create($validated);

        return redirect()
            ->route('admin.skins.index')
            ->with('success', __('app.skin_created', ['name' => $skin->name]));
    }

    public function edit(Skin $skin): View
    {
        return view('admin.skins.edit', compact('skin'));
    }

    public function update(Request $request, Skin $skin): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($skin->image_url) {
                Storage::disk('public')->delete($skin->image_url);
            }
            $validated['image_url'] = $request->file('image')->store('skins', 'public');
        }

        $skin->update($validated);

        return redirect()
            ->route('admin.skins.index')
            ->with('success', __('app.skin_updated', ['name' => $skin->name]));
    }

    public function destroy(Skin $skin): RedirectResponse
    {
        if ($skin->orders()->exists()) {
            return back()->with('error', __('app.skin_has_orders'));
        }

        if ($skin->image_url) {
            Storage::disk('public')->delete($skin->image_url);
        }

        $skin->delete();

        return redirect()
            ->route('admin.skins.index')
            ->with('success', __('app.skin_deleted', ['name' => $skin->name]));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'hero_name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', 'max:50'],
            'price_diamond' => ['required', 'integer', 'min:1'],
            'price_rupiah' => ['required', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ], [], [
            'name' => __('app.skin_name'),
            'hero_name' => __('app.hero_name'),
            'type' => __('app.skin_type'),
            'price_diamond' => __('app.price_diamond'),
            'price_rupiah' => __('app.price_rupiah'),
            'image' => __('app.image'),
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
