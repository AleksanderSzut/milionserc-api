<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Confession extends Model
{
    use HasFactory;
    const PUBLIC_NO = 0;
    const PUBLIC_YES = 1;


    public  function package(): BelongsTo
    {
        return $this->belongsTo(package::class);
    }
}
