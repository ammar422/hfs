<?php

namespace Modules\Ranks\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class RanksResource extends JsonResource
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
            'id'                    => $this->id,
            'name'                  => $this->name,
            'left_volume'           => $this->left_volume,
            'right_volume'          => $this->right_volume,
            'direct_referrals'      => $this->direct_referrals,
            'downline_requirements' => $this->downline_requirements ?[ json_decode($this->downline_requirements, true)] : [],
            'image'                 => url('storage/ranks_images/'.$this->image),
        ];
    }
}
