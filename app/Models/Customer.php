<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [ 'first_name', 'last_name', 'gender', 'date_of_birth', 'contact_number', 'email' ];
    protected $table = 'customers';

    public function purchase_transaction()
    {
      return $this->hasMany(PurchaseTransaction::class);
    }
}
