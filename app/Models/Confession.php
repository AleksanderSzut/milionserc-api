<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Confession extends Model
{
    use HasFactory;


    public  function package(): BelongsTo
    {
        return $this->belongsTo(package::class);
    }
}
