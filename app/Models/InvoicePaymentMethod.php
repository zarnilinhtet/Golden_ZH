<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoicePaymentMethod extends Model
{
    use HasFactory;

    protected $guarded = [];



    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
