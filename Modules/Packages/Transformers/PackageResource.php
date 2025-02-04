<?php

namespace Modules\Packages\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            'id'                => $this->id,
            'name'              => $this->name,
            'price'             => $this->price,
            'billing_period'    => $this->billing_period,
            'cv'                => $this->cv,
            'features'          => $this->features,

        ];
    }
}
