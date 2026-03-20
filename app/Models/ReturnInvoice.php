<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnInvoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function Doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }
    public function SaleBy()
    {
        return $this->belongsTo(SalePerson::class, 'sale_by', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'location', 'id');
    }

    public function return_item()
    {
        return $this->hasMany(ReturnItem::class, 'invoice_return_id', 'id');
    }
}
