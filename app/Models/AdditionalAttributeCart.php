<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AdditionalAttributeCart extends Model
{
    use HasFactory;


    public  function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }
    public  function AdditionalPackageAttribute(): HasOne
    {
        return $this->hasOne(AdditionalPackageAttribute::class);
    }
}
