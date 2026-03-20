<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Treatment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [''];


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'branch');
    }

    // public function treatment_sells()
    // {
    //     return $this->hasMany(TreatmentSell::class, 'treatment_id');
    // }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
