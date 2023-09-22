<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'products',
        'customer_id',
        'seller_id',
        'subtotal',
        'shipment_id',
        'discount',
        'total',
        'correlative',
    ];

    public function setProductsAttribute($value)
    {
        $this->attributes['products'] = json_encode($value);
    }

    public function setCorrelativeAttribute($value)
    {
        $lastInvoice = self::latest()->first();

        if ($lastInvoice) {
            $lastCorrelative = $lastInvoice->correlative;
            $correlativeNumber = (int) substr($lastCorrelative, 1);
            $newCorrelativeNumber = $correlativeNumber + 1;
            $value = 'T' . str_pad($newCorrelativeNumber, 4, '0', STR_PAD_LEFT);
        } else {
            $value = 'T0001';
        }

        $this->attributes['correlative'] = $value;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}
