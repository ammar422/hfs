<?php

namespace Modules\Users\App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Modules\Wallets\Entities\TokenWallet;
use Modules\Packages\Entities\Subscription;
use Modules\Commissions\Entities\Commission;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Modules\Wallets\Entities\CommissionWallet;
use Modules\Users\Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Ranks\Entities\Rank;

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
        'id_code',

        'sponsor_id',
        'upline_id',
        'left_leg_id',
        'right_leg_id',
        'leg_type',
        'cv',
        'left_leg_cv',
        'right_leg_cv',
        'placement',
        'rank_id',

        'total_earning',
        'total_receive',
        'total_bounce',
        'total_transfer',


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
        static::saving(function ($model) {
            $model->full_name = $model->first_name . ' ' . $model->last_name;
        });
        static::creating(function ($user) {
            if (is_null($user->id_code)) {
                $lastIdCode = DB::table('users')->max('id_code');
                $user->id_code = $lastIdCode ? $lastIdCode + 1 : 400000;
            }
        });
        static::created(function ($model) {
            // if (!CommissionWallet::where('user_id', $model->id)->exists() && $model->account_type == 'user')
            if (!CommissionWallet::where('user_id', $model->id)->exists())
                $model->commissionWallet()->create([
                    'user_id' => $model->id
                ]);

            // if (!TokenWallet::where('user_id', $model->id)->exists() && $model->account_type == 'user')
            if (!TokenWallet::where('user_id', $model->id)->exists())
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






    // outer relations strat ////////////////////////

    public function commissionWallet(): HasOne
    {
        return $this->hasOne(CommissionWallet::class);
    }

    public function tokenWallet(): HasOne
    {
        return $this->hasOne(TokenWallet::class);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }

    public function commission(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    // outer relations end /////////////////////////



    // inner relations start ////////////////////////////// 

    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }

    public function referrals():HasMany
    {
        return $this->hasMany(User::class, 'sponsor_id');
    }

    public function upline(): BelongsTo
    {
        return $this->belongsTo(User::class, 'upline_id');
    }

    public function downlines(): HasMany
    {
        return $this->hasMany(User::class, 'upline_id');
    }

    public function downline(): HasOne
    {
        return $this->hasOne(User::class, 'upline_id');
    }

    public function leftLeg(): BelongsTo
    {
        return $this->belongsTo(User::class, 'left_leg_id');
    }

    public function rightLeg(): BelongsTo
    {
        return $this->belongsTo(User::class, 'right_leg_id');
    }

    public function hasBothLegs(): bool
    {
        return $this->left_leg_id && $this->right_leg_id;
    }
    public function scopeHasBothLegsCv($query)
    {
        return $query->where('left_leg_cv', '>', 0)
            ->where('right_leg_cv', '>', 0);
    }

    public function getAncestorsAttribute()
    {
        $ancestors = collect();
        $current = $this->upline;

        while ($current) {
            $ancestors->push($current);
            $current = $current->upline;
        }

        return $ancestors;
    }

    public function isEligibleForBinary(): bool
    {
        return $this->left_leg_cv > 0 && $this->right_leg_cv > 0;
    }

    public function getWeakerLegValue(): float
    {
        return min($this->left_leg_cv, $this->right_leg_cv);
    }

    public function leftLegReferrals()
    {
        return $this->referrals()->where('leg_type', 'left');
    }


    public function rightLegReferrals()
    {
        return $this->referrals()->where('leg_type', 'right');
    }

    // functions end //////////////////////////////// 

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
