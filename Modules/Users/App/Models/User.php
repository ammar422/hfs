<?php

namespace Modules\Users\App\Models;

use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Modules\Users\Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Wallets\Entities\CommissionWallet;
use Modules\Wallets\Entities\TokenWallet;

/**
 * @property string|int|null $verification_code
 * @property string|null $email
 * @property string|null $password
 * @property string|int|null $reset_token
 * @property string $full_name
 * @property string $user_type
 * @property string $account_status
 */
class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'full_name',
        'first_name',
        'last_name',
        'email',
        'password',
        'reset_token',
        'mobile',
        'account_type', // admin | user
        'admin_group_id', // admin_group_id
        'photo',
        'created_at',
        'updated_at',
        'deleted_at',
        'account_status',
        'verification_code',
        'ban_reason',
    ];

    /**
     * @var string
     */
    protected $deleted_at = 'deleted_at';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->full_name = $model->first_name . ' ' . $model->last_name;
        });
        static::created(function ($model) {
            if (!CommissionWallet::where('user_id', $model->id)->exists() && $model->account_type == 'user')
                $model->commissionWallet()->create([
                    'user_id' => $model->id
                ]);

            if (!TokenWallet::where('user_id', $model->id)->exists() && $model->account_type == 'user')
                $model->tokenWallet()->create([
                    'user_id' => $model->id
                ]);
        });
    }

    /**
     * @param string $date
     *
     * @return string|null
     */
    public function getCreatedAtAttribute(?string $date): ?string
    {
        if (!$date) {
            return null;
        }

        $timestamp = strtotime($date);
        return $timestamp !== false ? date('Y-m-d', $timestamp) : null;
    }

    /**
     * Format the updated at date.
     *
     * @param string|null $date
     * @return string|null
     */
    public function getUpdatedAtAttribute(?string $date): ?string
    {
        if (!$date) {
            return null;
        }

        $timestamp = strtotime($date);
        return $timestamp !== false ? date('Y-m-d', $timestamp) : null;
    }

    /**
     * Format the deleted at date.
     *
     * @param string|null $date
     * @return string|null
     */
    public function getDeletedAtAttribute(?string $date): ?string
    {
        if (!$date) {
            return null;
        }

        $timestamp = strtotime($date);
        return $timestamp !== false ? date('Y-m-d', $timestamp) : null;
    }

    /**
     * Format the email verified at date.
     *
     * @param string|null $date
     * @return string|null
     */
    public function getEmailVerifiedAtAttribute(?string $date): ?string
    {
        if (!$date) {
            return null;
        }

        $timestamp = strtotime($date);
        return $timestamp !== false ? date('Y-m-d', $timestamp) : null;
    }

    public function commissionWallet(): HasOne
    {
        return $this->hasOne(CommissionWallet::class);
    }

    public function tokenWallet(): HasOne
    {
        return $this->hasOne(TokenWallet::class);
    }

    /**
     * @return BelongsTo<AdminGroup,User>
     */
    public function admingroup(): BelongsTo
    {
        return $this->belongsTo(AdminGroup::class, 'admin_group_id');
    }
    /**
     * @return UserFactory
     */
    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
