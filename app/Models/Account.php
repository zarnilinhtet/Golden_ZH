<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'location', 'id');
    }
}
