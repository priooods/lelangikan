<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MNelayanTab extends Model
{
    protected $fillable = [
        'name',
        'm_status_tabs_id',
    ];

    public function status(){
        return $this->belongsTo(MStatusTabs::class, 'm_status_tabs_id');
    }
}
