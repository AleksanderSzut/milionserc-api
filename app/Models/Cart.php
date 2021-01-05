<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Model,
    Relations\HasMany,
    Relations\HasOne
};

class Cart extends Model
{
    use HasFactory;

    public  function additionalAttributeCart(): HasMany
    {
        return $this->hasMany(AdditionalAttributeCart::class);
    }
    public function package(): HasOne
    {
        return $this->hasOne(Package::class);
    }
}
