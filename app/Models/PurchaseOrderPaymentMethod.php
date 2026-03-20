<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderPaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }
}
