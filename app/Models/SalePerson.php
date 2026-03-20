<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePerson extends Model
{
    use HasFactory;


    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'sale_by');
    }


    public function items()
    {

        return $this->hasMany(Item::class, 'sale_people_id');
    }
    public function warehouse()
    {

        return $this->belongsTo(Warehouse::class, 'location');
    }
}
