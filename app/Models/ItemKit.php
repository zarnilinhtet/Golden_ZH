<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemKit extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [''];


    public function item()
    {
        return $this->belongsTo(Item::class, 'product_id');
    }

    public function variations()
    {
        return $this->hasMany(ItemVariation::class, 'id', 'variation_id');
    }
}
