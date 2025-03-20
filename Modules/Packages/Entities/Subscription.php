<?php

namespace Modules\Packages\Entities;

use Modules\Users\App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Packages\Database\Factories\SubscriptionFactory;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'package_id',
        'name',
        'cv',
        'billing_period',
        'expired_at',
        'status',
        'amount',
        'payment_intent_id',
    ];


    protected  $appends  = [
        'remaining_days',
    ];


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!empty($model->package_id)) {
                $model->billing_period == 'monthly' ? $model->expired_at = now()->addDays(30) : null;
                $model->billing_period == 'yearly' ? $model->expired_at = now()->addYear() : null;
                $model->billing_period == 'quarterly' ? $model->expired_at = now()->addQuarter() : null;
                $model->billing_period == 'biannual' ? $model->expired_at = now()->addMonths(6) : null;
                $model->billing_period == 'lifelong' ? $model->expired_at = now()->addYears(100) : null;
            }
        });
    }


    /**
     * Get the remaining days for the campaign.
     *
     * @return int
     */
    public function getRemainingDaysAttribute()
    {
        return !empty($this->expired_at) ? now()->diffInDays($this->expired_at) : 0;
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    protected static function newFactory()
    {
        return SubscriptionFactory::new();
    }
}
