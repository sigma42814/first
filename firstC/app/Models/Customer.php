<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // app/Models/Customer.php
protected $fillable = [
    'name', 'email', 'phone', 'address', 'company', 
    'credit_limit', 'area', 'brick', 'salesman', 'usd', 'afn', 'pkr'
];

protected $casts = [
    'usd' => 'boolean',
    'afn' => 'boolean',
    'pkr' => 'boolean',
];



}
