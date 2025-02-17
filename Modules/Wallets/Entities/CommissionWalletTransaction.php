<?php

namespace Modules\Wallets\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Users\App\Models\User;

class CommissionWalletTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'amount',
        'user_id',
        'commission_wallet_id',
        'token_wallet_id',
        'status',
        'rejection_reasons',
        'paid_at',
        'transaction_type',
        'transaction_fees',
    ];


    protected $casts = [
        'amount'           => 'decimal:2',
        'paid_at'          => 'datetime',
        'transaction_fees' => 'decimal:2',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function Wallet(): BelongsTo
    {
        return $this->belongsTo(CommissionWallet::class);
    }

    public function tokenWallet(): BelongsTo
    {
        return $this->belongsTo(TokenWallet::class);
    }

    protected static function newFactory()
    {
        return \Modules\Wallets\Database\factories\CommissionWalletTransactionFactory::new();
    }
}
