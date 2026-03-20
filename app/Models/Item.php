<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [''];


    public function inOuts()
    {
        return $this->hasMany(InOut::class, 'items_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function itemKits()
    {
        return $this->hasMany(ItemKit::class, 'product_id');
    }

    public function variations()
    {
        return $this->hasMany(ItemVariation::class, 'item_id', 'id');
    }
    public function single_variation()
    {
        return $this->belongsTo(ItemVariation::class, 'item_id', 'id');
    }
    public function getVariation()
    {
        return $this->belongsTo(ItemVariation::class, 'id', 'item_id');
    }
    public function pricePercent()
    {
        return $this->belongsTo(PricePercent::class, 'warehouse_id', 'branch');
    }
    public function BrandName()
    {
        return $this->belongsTo(Brand::class, 'brand', 'id');
    }
}
