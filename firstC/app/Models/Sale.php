<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoice_date',
        'invoice_number',
        'username',
        'total_sales',
        'net_payable',
        'prev_balance',
        'total_balance'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            // Generate invoice number when creating a new sale
            if (empty($sale->invoice_number)) {
                $latestSale = static::latest()->first();
                $nextId = $latestSale ? $latestSale->id + 1 : 1;
                $sale->invoice_number = 'INV-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    protected $casts = [
        'invoice_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'username', 'name'); // Adjust this based on your actual relationship
    }

}