<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceReturnPaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function invoice_return()
    {
        return $this->belongsTo(ReturnInvoice::class, 'invoice_return_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
