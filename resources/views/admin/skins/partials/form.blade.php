@php($skin = $skin ?? null)

<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">{{ __('app.skin_name') }}</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror"
               id="name" name="name" value="{{ old('name', $skin?->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="hero_name" class="form-label">{{ __('app.hero_name') }}</label>
        <input type="text" class="form-control @error('hero_name') is-invalid @enderror"
               id="hero_name" name="hero_name" value="{{ old('hero_name', $skin?->hero_name) }}" required>
        @error('hero_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="type" class="form-label">{{ __('app.skin_type') }}</label>
        <input type="text" class="form-control @error('type') is-invalid @enderror"
               id="type" name="type" value="{{ old('type', $skin?->type) }}"
               placeholder="Epic, Special, Starlight..." required>
        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="price_diamond" class="form-label">{{ __('app.price_diamond') }}</label>
        <input type="number" min="1" class="form-control @error('price_diamond') is-invalid @enderror"
               id="price_diamond" name="price_diamond" value="{{ old('price_diamond', $skin?->price_diamond) }}" required>
        @error('price_diamond')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="price_rupiah" class="form-label">{{ __('app.price_rupiah') }}</label>
        <input type="number" min="1" class="form-control @error('price_rupiah') is-invalid @enderror"
               id="price_rupiah" name="price_rupiah" value="{{ old('price_rupiah', $skin?->price_rupiah) }}" required>
        @error('price_rupiah')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8">
        <label for="image" class="form-label">{{ __('app.image') }}</label>
        <input type="file" class="form-control @error('image') is-invalid @enderror"
               id="image" name="image" accept="image/jpeg,image/png,image/webp">
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if ($skin?->imageUrl())
            <div class="mt-2">
                <img src="{{ $skin->imageUrl() }}" alt="{{ $skin->name }}" class="rounded"
                     style="width: 96px; height: 96px; object-fit: cover;">
            </div>
        @endif
    </div>
    <div class="col-md-4 d-flex align-items-center">
        <div class="form-check form-switch mt-4">
            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                   @checked(old('is_active', $skin?->is_active ?? true))>
            <label class="form-check-label" for="is_active">{{ __('app.active') }}</label>
        </div>
    </div>
</div>
