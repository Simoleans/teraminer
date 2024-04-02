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
        'status',
        'user_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            // Lógica que deseas ejecutar después de guardar los datos en el método store
            $model->user_id = auth()->id();
        });
    }

    public function setProductsAttribute($value)
    {
        $this->attributes['products'] = json_encode($value);
    }

    public function setCorrelativeAttribute($value)
    {
        if (!$value) {
            $this->attributes['correlative'] = self::getNextCorrelative();
        }

    }

    public function getProductsAttribute()
    {
        return json_decode($this->attributes['products']);
    }

    public function getCreatedAtAttribute()
    {
        return date('d-m-Y', strtotime($this->attributes['created_at']));
    }


    public static function getNextCorrelative()
    {
        $lastInvoice = self::latest()->first();

        if ($lastInvoice) {
            $lastCorrelative = $lastInvoice->correlative;
            $correlativeNumber = (int) substr($lastCorrelative, 1);
            $newCorrelativeNumber = $correlativeNumber + 1;
            $correlative = 'T' . str_pad($newCorrelativeNumber, 4, '0', STR_PAD_LEFT);
        } else {
            $correlative = 'T0001';
        }

        return $correlative;
    }

    public static function getLastCorrelative()
    {
        $lastInvoice = self::latest()->first();

        if ($lastInvoice) {
            $lastCorrelative = $lastInvoice->correlative;
        } else {
            $lastCorrelative = 'T0001';
        }

        return $lastCorrelative;
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
