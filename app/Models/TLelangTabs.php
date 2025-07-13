<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TLelangTabs extends Model
{
    protected $fillable = [
        't_ikan_tabs_id',
        'start_date_lelang',
        'end_date_lelang',
        'description',
        'm_status_tabs_id',
    ];

    public function status()
    {
        return $this->belongsTo(MStatusTabs::class, 'm_status_tabs_id');
    }

    public function ikan()
    {
        return $this->belongsTo(TIkanTabs::class, 't_ikan_tabs_id');
    }
}
