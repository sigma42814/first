<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'item_id',
        'batch_number',
        'exp_date',
        'price',
        'purchase_price',
        'quantity',
        'discount',
        'total'
    ];

    protected $dates = ['exp_date'];

    protected $casts = [
        'exp_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    protected $dispatchesEvents = [
        'created' => \App\Observers\PurchaseItemObserver::class,
        'deleted' => \App\Observers\PurchaseItemObserver::class
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    // Add this method to update inventory when a purchase item is created/updated
    protected static function booted()
    {
        static::created(function ($purchaseItem) {
            $purchaseItem->updateInventory();
        });

        static::updated(function ($purchaseItem) {
            $purchaseItem->updateInventory();
        });
    }

    public function updateInventory()
    {
        Inventory::updateOrCreate(
            [
                'item_id' => $this->item_id,
                'batch_number' => $this->batch_number,
                'purchase_item_id' => $this->id
            ],
            [
                'exp_date' => $this->exp_date,
                'purchase_price' => $this->purchase_price,
                'selling_price' => $this->price,
                'quantity' => $this->quantity
            ]
        );
    }

    

    public function returnedItems()
    {
        return $this->hasMany(ReturnPurchaseItem::class, 'purchase_item_id');
    }

    public function getReturnedQuantityAttribute()
    {
        return $this->returnedItems()->sum('quantity');
    }

}