<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TIkanTabs extends Model
{
    protected $fillable = [
        'm_ikan_tabs_id',
        'm_nelayan_tabs_id',
        'type',
        'count',
        'weight',
        'description',
    ];

    public function stock(){
        return $this->hasOne(TIkanStockTab::class, 'm_ikan_tabs_id', 'm_ikan_tabs_id');
    }
    public function penjualan()
    {
        return $this->hasOne(TPenjualanTabs::class, 't_ikan_tabs_id', 'id');
    }
    public function pelelangan()
    {
        return $this->hasOne(TLelangTabs::class, 't_ikan_tabs_id', 'id');
    }
    public function ikan(){
        return $this->belongsTo(MIkanTabs::class,'m_ikan_tabs_id');
    }
    public function nelayan()
    {
        return $this->belongsTo(MNelayanTab::class, 'm_nelayan_tabs_id');
    }
}
