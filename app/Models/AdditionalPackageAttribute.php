<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AdditionalPackageAttribute extends Model
{
    use HasFactory;

    public  function package(): HasOne
    {
        return $this->hasOne(Package::class);
    }

    public  function AdditionalAttributeCart(): HasMany
    {
        return $this->hasMany(AdditionalAttributeCart::class);
    }
}
