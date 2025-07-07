<?php

namespace App\Observers;

use App\Models\Inventory;
use App\Models\PurchaseItem;

class PurchaseItemObserver
{
    public function created(PurchaseItem $purchaseItem)
    {
        Inventory::create([
            'purchase_item_id' => $purchaseItem->id,
            'item_id' => $purchaseItem->item_id,
            'batch_number' => $purchaseItem->batch_number,
            'expiry_date' => $purchaseItem->exp_date,
            'quantity' => $purchaseItem->quantity,
            'remaining_quantity' => $purchaseItem->quantity,
            'purchase_price' => $purchaseItem->purchase_price,
            'selling_price' => $purchaseItem->price,
            'movement_type' => 'purchase',
            'movement_date' => $purchaseItem->purchase->invoice_date,
            'status' => 'active'
        ]);
    }

    public function deleted(PurchaseItem $purchaseItem)
    {
        Inventory::where('purchase_item_id', $purchaseItem->id)->delete();
    }
}