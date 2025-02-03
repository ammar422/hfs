<?php

namespace Modules\Users\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdminGroupRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'admin_group_id',
        'resource',
        'create',
        'show',
        'update',
        'delete',
        'force_delete',
        'restore',
    ];

    /**
     * Get the admin group that owns the role.
     *
     * @return BelongsTo<AdminGroup, AdminGroupRole>
     */
    public function admingroup(): BelongsTo
    {
        return $this->belongsTo(AdminGroup::class, 'admin_group_id');
    }

    /**
     * Get the groups associated with the role.
     *
     * @return BelongsToMany<AdminGroup>
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(AdminGroup::class);
    }
}