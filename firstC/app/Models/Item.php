<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'item_name',
        'category_id',
        'unit',
        'cost_price',
        'selling_price',
        'min_stock_level',
        'stock',
        'description',
        'active',
        'tp',
        'mrp',
        'barcode',
        'tax_rate'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'tp' => 'decimal:2',
        'mrp' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'active' => 'boolean',
        'min_stock_level' => 'integer',
        'stock' => 'integer'
    ];

    
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function getCurrentStockAttribute()
    {
        return $this->inventories()->sum('remaining_quantity');
    }

    public function isLowStock()
    {
        if (!isset($this->min_stock_level)) {
            return false;
        }
        return $this->current_stock < $this->min_stock_level;
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function category()
{
    return $this->belongsTo(Category::class)->withDefault([
        'name' => 'Uncategorized',
        'description' => 'No category assigned'
    ]);
}

public function items()
{
    return $this->hasMany(PurchaseItem::class);
}

}