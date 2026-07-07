<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skin extends Model
{
    protected $fillable = [
        'name',
        'hero_name',
        'type',
        'price_diamond',
        'price_rupiah',
        'image_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_diamond' => 'integer',
            'price_rupiah' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Skins the admin can still gift with the current diamond balance.
     */
    public function scopeAffordable(Builder $query, int $balance): Builder
    {
        return $query->where('price_diamond', '<=', $balance);
    }

    public function imageUrl(): ?string
    {
        // asset() follows the current request's host/port, unlike Storage::url()
        // which hardcodes APP_URL.
        return $this->image_url ? asset('storage/'.$this->image_url) : null;
    }
}
