<?php

namespace Modules\Packages\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);

        auth('api')->user()->subscription ? $data = [
            "id"                => $this->id,
            'name'              => $this->name,
            "cv"                => $this->cv,
            "billing_period"    => $this->billing_period,
            "created_at"        => $this->created_at,
            "expired_at"        => $this->expired_at,
            "remaining_days"    => $this->remaining_days,
            "status"            => $this->status,
            "gatway_url"        => $this->gatway_url,
        ] : $data = [];
        return $data;
    }
}
