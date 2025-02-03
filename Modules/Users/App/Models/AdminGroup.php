<?php

namespace Modules\Users\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdminGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
    ];


    /**
     * @return HasMany<AdminGroupRole >
     */
    public function roles(): HasMany
    {
        return $this->hasMany(AdminGroupRole::class, 'admin_group_id', 'id');
    }
}
