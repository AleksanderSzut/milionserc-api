<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Confession extends Model
{
    use HasFactory;
    const PUBLIC_NO = 0;
    const PUBLIC_YES = 1;
    const STATUS_NO_CREATED = 0;
    const STATUS_CREATED = 1;

    public function verifyConfession( $access_code) {
        return $this->access_code === $access_code;
    }

    public  function package(): BelongsTo
    {
        return $this->belongsTo(package::class);
    }
}
