<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AdditionalAttributeCart extends Model
{
    use HasFactory;


    public  function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }
    public  function AdditionalPackageAttribute(): BelongsTo
    {
        return $this->belongsTo(AdditionalPackageAttribute::class, 'additional_attribute_id');
    }
}
