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
        return [

            "id"                => $this->id,
            'full_name'         => $this->full_name,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            "email"             => $this->email,
            "mobile"            => $this->mobile,
            "account_status"    => $this->account_status,
            "user_type"         => $this->user_type,
            "photo"             => url($this->photo),

            "verification_code"         => $this->verification_code,

        ];
    }
}
