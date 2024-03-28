<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarantyProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'garanty_id',
        'product_id',
        'serial',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
