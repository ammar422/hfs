<?php

namespace Modules\Ranks\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Ranks\Database\factories\RankFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Users\App\Models\User;

class Rank extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'package',
        'left_volume',
        'right_volume',
        'direct_referrals',
        'downline_requirements',
        'image',
    ];

    protected $casts = [
        'downline_requirements' => 'array',
    ];


    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    
    protected static function newFactory()
    {
        return RankFactory::new();
    }
}
