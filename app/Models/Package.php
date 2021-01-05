<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin Builder
 */
class Package extends Model
{
    use HasFactory;
    public  function additionalCostsPackage(): HasMany
    {
        return $this->hasMany(AdditionalCostsPackage::class);
    }
    public  function confessions(): HasMany
    {
        return $this->hasMany(Confession::class);
    }
    public  function additionalPackageAttribute(): HasMany
    {
        return $this->hasMany(AdditionalPackageAttribute::class);
    }
    public  function cart(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}
