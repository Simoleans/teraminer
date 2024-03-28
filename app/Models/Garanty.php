<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Garanty extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    //hasmany garanty_products
    public function garanty_products()
    {
        return $this->hasMany(GarantyProduct::class);
    }

}
