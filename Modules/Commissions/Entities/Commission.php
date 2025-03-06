<?php

namespace Modules\Commissions\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Commissions\Database\factories\CommissionFactory;
use Modules\Users\App\Models\User;

class Commission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'paid_at',
        'referral_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!empty($model->user)) {
                $model->user->increment('total_earning', $model->amount);
            };
        });
    }



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return CommissionFactory::new();
    }
}
