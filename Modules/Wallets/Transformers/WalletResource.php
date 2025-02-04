<?php

namespace Modules\Wallets\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'balance' => $this->balance,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->full_name
            ],
        ];
    }
}
