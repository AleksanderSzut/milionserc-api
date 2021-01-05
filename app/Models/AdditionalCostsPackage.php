<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AdditionalCostsPackage extends Model
{
    use HasFactory;
    public  function package(): HasOne
    {
        return $this->hasOne(Package::class);
    }

}
