<?php

namespace Modules\Wallets\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CommissionWalletTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'amount'            => $this->amount,
            'commission_wallet' => [
                'id'            => $this?->wallet?->id,
                'balance'       => $this?->wallet?->balance
            ],
            'token_wallet_id'   =>  [
                'id'            => $this?->tokenWallet?->id,
                'balance'       => $this?->tokenWallet?->balance
            ],
            'status'            => $this->status,
            'rejection_reasons' => $this->rejection_reasons,
            'paid_at'           => $this->paid_at,
            'transaction_type'  => $this->transaction_type,
            'transaction_fees'  => $this->transaction_fees
        ];
    }
}
