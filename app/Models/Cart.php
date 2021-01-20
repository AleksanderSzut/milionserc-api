<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory,
    Model,
    Relations\BelongsTo,
    Relations\BelongsToMany,
    Relations\HasMany,
    Relations\HasOne};

class Cart extends Model
{
    use HasFactory;

    public  function additionalAttributeCart(): BelongsToMany
    {
        return $this->belongsToMany(AdditionalAttributeCart::class);
    }
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
