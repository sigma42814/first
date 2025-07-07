<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_number',
        'return_date',
        'customer_id',
        'username',
        'total_return',
        'net_payable',
        'reason'
    ];

    protected $casts = [
        'return_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SalesReturnItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->return_number = 'SR-' . date('Ymd') . '-' . str_pad(SalesReturn::count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}