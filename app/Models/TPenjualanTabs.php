<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TPenjualanTabs extends Model
{
    protected $fillable = [
        't_ikan_tabs_id',
        'm_status_tabs_id',
        'price',
        'description',
    ];

    public function status()
    {
        return $this->belongsTo(MStatusTabs::class, 'm_status_tabs_id');
    }

    public function ikan(){
        return $this->belongsTo(TIkanTabs::class, 't_ikan_tabs_id');
    }

    public function transaction(){
        return $this->hasOne(TPenjualanTransactionTabs::class, 't_penjualan_tabs_id','id');
    }
}
