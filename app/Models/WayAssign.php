<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WayAssign extends Model
{
    use HasFactory;
    public function SalePerson()
    {
        return $this->belongsTo(SalePerson::class, 'sale_person', 'id');
    }
}
