<?php

namespace Modules\Wallets\Entities;

use Modules\Users\App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TokenWallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'balance',
        'user_id',
    ];


    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    protected static function newFactory()
    {
        return \Modules\Wallets\Database\factories\TokenWalletFactory::new();
    }
}
