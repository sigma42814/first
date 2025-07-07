<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\SalesReturnController;



// Dashboard Route
Route::get('/', function () {
    return view('dashboard');
});

// Items Routes
Route::resource('items', ItemController::class);
Route::get('/items/{id}/details', [ItemController::class, 'getItemDetails']);

// Customers Routes
Route::resource('customers', CustomerController::class);
Route::get('/customers/search-by-name', [CustomerController::class, 'searchByName']);
Route::get('/customers/search-by-name', [SaleController::class, 'searchCustomersByName'])->name('customers.searchByName');
Route::get('/customer-search', [CustomerController::class, 'search']);

// Employees Routes
Route::resource('employees', EmployeeController::class);

// Companies Routes
Route::resource('companies', CompanyController::class);

// Sales Routes
Route::resource('sales', SaleController::class);
Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
Route::post('/save-sale', [SaleController::class, 'store'])->name('save.sale');
Route::get('/sales/{sale}/print', [SaleController::class, 'print'])->name('sales.print');
Route::get('/sales/{sale}/invoice', [SaleController::class, 'invoice'])->name('sales.invoice');
Route::get('/sales/{sale}/edit', [SaleController::class, 'edit'])->name('sales.edit');
Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update');
Route::get('/sales/{sale}/details', [SaleController::class, 'showDetails'])->name('sales.details');

// Sales Return section
Route::resource('sales-returns', SalesReturnController::class);

// API routes for search
Route::get('/search-customers', [SalesReturnController::class, 'searchCustomers'])->name('sales-returns.search-customers');
Route::get('/search-items', [SalesReturnController::class, 'searchItems'])->name('sales-returns.search-items');
Route::get('/search-items-by-name', [SalesReturnController::class, 'searchItemsByName'])->name('sales-returns.search-items-by-name');
Route::get('/search-inventory', [SalesReturnController::class, 'searchInventory'])->name('sales-returns.search-inventory');
Route::get('/search-employees', [SalesReturnController::class, 'searchEmployees']);
Route::get('sales-returns/{sales_return}/print', [SalesReturnController::class, 'print'])->name('sales-returns.print');
Route::get('sales-returns/{sales_return}/details', [SalesReturnController::class, 'details'])->name('sales-returns.details');

Route::get('/sales-returns/generate-number', [SalesReturnController::class, 'generateReturnNumber']);
Route::get('/search-items', [SalesReturnController::class, 'searchItems']);
Route::get('/search-items-by-name', [SalesReturnController::class, 'searchItemsByName']);


// Purchase Routes
Route::prefix('purchases')->group(function () {
    Route::get('/', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('/{id}/details', [PurchaseController::class, 'showDetails'])->name('purchases.details');
    Route::get('/{id}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::put('/{id}', [PurchaseController::class, 'update'])->name('purchases.update');
    Route::delete('/{id}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');
    Route::get('/{id}/print', [PurchaseController::class, 'print'])->name('purchases.print');
});
// In routes/web.php
Route::put('/purchases/{purchase}', 'PurchaseController@update')->name('purchases.update');


// Inventory Routes
Route::prefix('inventory')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/{itemId}', [InventoryController::class, 'show'])->name('inventory.show');
    
    // Reports
    Route::get('/low-stock', [InventoryController::class, 'lowStockReport'])->name('inventory.low-stock');
    Route::get('/low-stock/pdf', [InventoryController::class, 'generateLowStockPdf'])->name('inventory.low-stock-pdf');
    Route::get('/movement-report', [InventoryController::class, 'stockMovementReport'])->name('inventory.movement-report');
    Route::get('/batch-report', [InventoryController::class, 'batchReport'])->name('inventory.batch-report');
    Route::get('/expiry-report', [InventoryController::class, 'expiryReport'])->name('inventory.expiry-report');
    
    // Actions
    Route::post('/adjust', [InventoryController::class, 'stockAdjustment'])->name('inventory.adjust');
});


// Purchase Returns
Route::resource('purchase-returns', PurchaseReturnController::class);
Route::get('search-companies', [PurchaseReturnController::class, 'searchCompanies']);
Route::get('search-items', [PurchaseReturnController::class, 'searchItems']);
Route::get('/purchase-returns/{purchaseReturn}/print', [PurchaseReturnController::class, 'print'])->name('purchase-returns.print');
Route::get('/purchase-returns/{purchaseReturn}/details', [PurchaseReturnController::class, 'details'])->name('purchase-returns.details');
Route::get('search-items-by-name', [PurchaseReturnController::class, 'searchItemsByName'])->name('search-items-by-name');




// Search routes
Route::get('/search-customers', [SearchController::class, 'searchCustomers']);
Route::get('/search-employees', [SearchController::class, 'searchEmployees']);
Route::get('/search-items', [SearchController::class, 'searchItems']);
Route::get('/search-companies', [SearchController::class, 'searchCompanies']);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
