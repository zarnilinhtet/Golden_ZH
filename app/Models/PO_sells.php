<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PO_sells extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [''];

    public function variations()
    {
        return $this->hasMany(ItemVariation::class, 'id', 'variation_id');
    }
    public function warehouse_name()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse');
    }
    public function item_variation()
    {
        return $this->belongsTo(ItemVariation::class, 'variation_id', 'id');
    }
}
