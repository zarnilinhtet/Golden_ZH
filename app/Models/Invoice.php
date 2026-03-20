<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $guraded = [];
    public function sells()
    {
        return $this->hasMany(Sell::class, 'invoiceid');
    }

    public function po_sells()
    {
        return $this->hasMany(PO_sells::class, 'invoiceid');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
    public function treatment()
    {
        return $this->belongsTo(Treatment::class, 'treatment_id', 'id');
    }
    public function Doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }
    public function SaleBy()
    {
        return $this->belongsTo(SalePerson::class, 'sale_by', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'location', 'id');
    }

    public function invoice_return()
    {
        return $this->hasMany(ReturnInvoice::class, 'invoice_id');
    }
}
