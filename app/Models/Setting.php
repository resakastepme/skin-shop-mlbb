<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'admin_ml_id',
        'admin_ml_nickname',
        'dana_number',
        'whatsapp_number',
        'current_diamond_balance',
    ];

    protected function casts(): array
    {
        return [
            'current_diamond_balance' => 'integer',
        ];
    }

    /**
     * The single global settings row.
     */
    public static function current(): self
    {
        return static::query()->firstOrFail();
    }

    /**
     * The settings row locked for update — use inside DB::transaction()
     * whenever the diamond balance is about to change.
     */
    public static function lockedForUpdate(): self
    {
        return static::query()->lockForUpdate()->firstOrFail();
    }
}
