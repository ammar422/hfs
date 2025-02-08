<?php

namespace Modules\Packages\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Packages\Database\Factories\PackageFactory;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'billing_period',
        'cv',
        'features'
    ];

    protected $casts = [
        'features' => 'array',
    ];


    public function subscription(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }


    protected static function newFactory()
    {
        return PackageFactory::new();
    }
}
