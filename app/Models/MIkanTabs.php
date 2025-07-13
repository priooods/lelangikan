<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MIkanTabs extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'fish_name',
        'fish_picture',
    ];

    public function ikan(){
        return $this->hasMany(TIkanTabs::class, 'm_ikan_tabs_id','id');
    }

    public function stock()
    {
        return $this->hasOne(TIkanStockTab::class, 'm_ikan_tabs_id', 'id');
    }
}
