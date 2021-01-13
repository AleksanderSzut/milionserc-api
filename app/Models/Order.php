<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    const STATUS_NOT_PAID = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 2;

    protected $fillable = ['payment_id', 'billing_id', 'shipping_id', 'status'];

    public  function shipping(): BelongsTo
    {
        return $this->belongsTo(Shipping::class);
    }
    public  function billing(): BelongsTo
    {
        return $this->belongsTo(Billing::class);
    }
    public  function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
    public function cart(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}
