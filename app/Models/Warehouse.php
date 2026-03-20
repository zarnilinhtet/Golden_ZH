<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [''];

    // public function items()
    // {
    //     return $this->hasMany(Item::class);
    // }

    public function inouts()
    {
        return $this->hasMany(InOut::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'branch');
    }
    public function from_location()
    {
        return $this->belongsTo(TransferHistory::class, 'id', 'from_location');
    }
    public function to_location()
    {
        return $this->belongsTo(TransferHistory::class, 'id', 'to_location');
    }
    public function pricePercent()
    {
        return $this->belongsTo(PricePercent::class, 'id', 'branch');
    }
}
