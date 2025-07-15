<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PegawaiTabs extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'm_user_role_tabs_id',
        'name',
        'email',
        'alamat',
        'gender',
        'avatar',
        'password',
    ];

    public function role()
    {
        return $this->hasOne(MUserRoleTabs::class, 'id', 'm_user_role_tabs_id');
    }
}
