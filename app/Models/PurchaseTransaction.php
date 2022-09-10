<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseTransaction extends Model
{
    protected $fillable = [ 'customer_id', 'total_spent', 'total_saving', 'transaction_at' ];
    protected $table = 'purchase_transaction';

    public function customer()
    {
      return $this->belongsTo(Customer::class);
    }
}
