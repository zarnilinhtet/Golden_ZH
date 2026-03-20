<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [''];
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function transaction()
    {
        return $this->belongsto(Transaction::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'branch', 'id');
    }
}
