<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Order extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_CANCELED = 'canceled';

    public const RESERVATION_MINUTES = 30;

    protected $fillable = [
        'order_code',
        'skin_id',
        'buyer_name',
        'buyer_email',
        'buyer_whatsapp',
        'buyer_ml_nickname',
        'buyer_ml_id',
        'buyer_ml_server',
        'total_diamond',
        'total_rupiah',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total_diamond' => 'integer',
            'total_rupiah' => 'integer',
        ];
    }

    public function skin(): BelongsTo
    {
        return $this->belongsTo(Skin::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Pending orders whose 30-minute reservation has run out.
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->pending()
            ->where('created_at', '<=', now()->subMinutes(self::RESERVATION_MINUTES));
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function reservationExpiresAt(): CarbonInterface
    {
        return $this->created_at->addMinutes(self::RESERVATION_MINUTES);
    }

    /**
     * Cancel this order and refund its diamonds to the admin balance.
     * No-op unless the order is still pending. Returns true when canceled.
     */
    public function cancelAndRefund(): bool
    {
        return DB::transaction(function (): bool {
            $order = static::query()->whereKey($this->getKey())->lockForUpdate()->first();

            if (! $order || ! $order->isPending()) {
                return false;
            }

            Setting::lockedForUpdate()->increment('current_diamond_balance', $order->total_diamond);
            $order->update(['status' => self::STATUS_CANCELED]);

            $this->status = self::STATUS_CANCELED;

            return true;
        });
    }

    /**
     * Partially masked contact details for the public tracking page.
     */
    public function maskedEmail(): string
    {
        [$local, $domain] = explode('@', $this->buyer_email, 2) + [1 => ''];

        return Str::mask($local, '*', min(2, strlen($local)), max(strlen($local) - 2, 1)).'@'.$domain;
    }

    public function maskedWhatsapp(): string
    {
        return Str::mask($this->buyer_whatsapp, '*', 4, max(strlen($this->buyer_whatsapp) - 6, 1));
    }

    /**
     * Date-based unique code, e.g. ORD-20260707-A3F9.
     */
    public static function generateOrderCode(): string
    {
        do {
            $code = sprintf('ORD-%s-%s', now()->format('Ymd'), Str::upper(Str::random(4)));
        } while (static::where('order_code', $code)->exists());

        return $code;
    }
}
