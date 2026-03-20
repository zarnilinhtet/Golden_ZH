<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }



    public function inout()
    {
        return $this->hasMany(Inout::class);
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'location', 'id');
    }

    public function invoice_payment_methods()
    {
        return $this->hasMany(InvoicePaymentMethod::class, 'transaction_id', 'id');
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'transaction_id', 'id');
    }



    public function expenses()
    {
        return $this->hasMany(Expense::class, 'transaction_id', 'id');
    }
}
