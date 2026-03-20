<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InOut extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $guarded = [''];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function item_variation()
    {
        return $this->belongsTo(ItemVariation::class, 'item_variation_id', 'id');
    }
    public function inout_warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
