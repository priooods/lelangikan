<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TLelangDetailTabs extends Model
{
    protected $fillable = [
        'users_id',
        't_lelang_tabs_id',
        'm_status_tabs_id',
        'description',
    ];

    public function status()
    {
        return $this->belongsTo(MStatusTabs::class, 'm_status_tabs_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'users_id');
    }

    public function lelang()
    {
        return $this->hasOne(TLelangTabs::class, 'id', 't_lelang_tabs_id');
    }
}
