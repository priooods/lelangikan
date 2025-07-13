<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TIkanStockTab extends Model
{
    protected $fillable = [
        'm_ikan_tabs_id',
        'stock',
    ];

    public function ikan()
    {
        return $this->belongsTo(MIkanTabs::class, 'm_ikan_tabs_id');
    }
}
