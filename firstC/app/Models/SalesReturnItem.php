<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_return_id',
        'item_id',
        'batch_number',
        'exp_date',
        'price',
        'selling_price',
        'quantity',
        'discount',
        'discount2',
        'bonus',
        'total'
    ];

    protected $dates = ['exp_date'];

    public function salesReturn()
    {
        return $this->belongsTo(SalesReturn::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}