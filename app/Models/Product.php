<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code_number',
        'unit_price',
    ];

    public function getUnitPriceAttribute($value)
    {
        return '$' . $value;
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
