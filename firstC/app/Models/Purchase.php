<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'company_invoice',
        'invoice_number',
        'invoice_date',
        'total_purchases',
        'net_payable',
        'prev_balance',
        'total_balance',
        'notes'
    ];

    // OR using $casts (preferred in newer Laravel versions)
    protected $casts = [
        'invoice_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];


    protected $dates = ['invoice_date'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}