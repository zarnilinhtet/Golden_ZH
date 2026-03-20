<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sell extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $guarded = [''];

    public function variations()
    {
        return $this->hasMany(ItemVariation::class, 'id', 'variation_id');
    }
    public function GetVar()
    {
        return $this->belongsTo(ItemVariation::class, 'variation_id', 'id');
    }
}
