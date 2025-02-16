<?php

namespace Modules\Users\App\Resources;

use App\Http\Resources\FileManagemer;
use Dash\Models\FileManagerModel;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        // return  parent::toArray($request);
        return [

            "id"                => $this->id,
            'full_name'         => $this->full_name,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            "email"             => $this->email,
            "id_code"           => $this->id_code,
            "sponsor"        => [
                'id'            =>  $this?->sponsor?->id,
                'id_code'       =>  $this?->sponsor?->id_code,
                'full_name'     =>  $this?->sponsor?->full_name,
            ],

            "upline"        => [
                'id'            =>  $this?->upline?->id,
                'id_code'       =>  $this?->upline?->id_code,
                'full_name'     =>  $this?->upline?->full_name,
            ],

            "left_leg"        => [
                'id'            =>  $this?->leftLeg?->id,
                'id_code'       =>  $this?->leftLeg?->id_code,
                'full_name'     =>  $this?->leftLeg?->full_name,
            ],

            "rightLeg"        => [
                'id'            =>  $this?->rightLeg?->id,
                'id_code'       =>  $this?->rightLeg?->id_code,
                'full_name'     =>  $this?->rightLeg?->full_name,
            ],
            "cv"                => $this->cv,
            "left_leg_cv"       => $this->left_leg_cv,
            "right_leg_cv"      => $this->right_leg_cv,
            "placement"         => $this->placement,
            "mobile"            => $this->mobile,
            "account_status"    => $this->account_status,
            "photo"             => url($this->photo),

            "verification_code"         => $this->verification_code,

        ];
    }
}
