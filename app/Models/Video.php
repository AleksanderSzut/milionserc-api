<?php

namespace App\Models;

use App\Traits\ProtectedFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    use HasFactory, ProtectedFile;

    public  function Confession(): BelongsTo
    {
        return $this->belongsTo(Confession::class);
    }
}
