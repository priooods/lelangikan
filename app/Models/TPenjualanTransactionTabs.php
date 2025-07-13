<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TPenjualanTransactionTabs extends Model
{
    protected $fillable = [
        't_penjualan_tabs_id',
        'users_id',
        'm_status_tabs_id',
        'count',
        'notes',
        'amount_change',
        'amount_paid',
        'payment_path',
    ];

    public function penjualan()
    {
        return $this->hasOne(TPenjualanTabs::class, 'id', 't_penjualan_tabs_id');
    }

    public function user(){
        return $this->hasOne(User::class,'id', 'users_id');
    }

    public function status()
    {
        return $this->belongsTo(MStatusTabs::class, 'm_status_tabs_id');
    }
}
