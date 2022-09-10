<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class campaignVoucher extends Model
{
    protected $fillable = [ 'campaign_id', 'customer_id', 'code', 'redeemed_at', 'locked_at' ];
    protected $table = 'campaign_vouchers';
}
