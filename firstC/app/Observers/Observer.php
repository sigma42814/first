<?php

namespace App\Observers;

use App\Models\Inventory;
use App\Models\PurchaseItem;

class PurchaseItemObserver
{
    public function created(PurchaseItem $purchaseItem)
    {
        Inventory::addFromPurchase($purchaseItem);
    }

    public function updated(PurchaseItem $purchaseItem)
    {
        // Handle updates if needed
    }

    public function deleted(PurchaseItem $purchaseItem)
    {
        // Handle inventory reversal if needed
    }
}