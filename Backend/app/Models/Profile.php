<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $primaryKey = 'user_id';
    public function getAvatarAttribute($value)
    {
        return url("images/") . "/" . $value;
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    function user_role()
    {
        return $this->hasMany(Role::class);
    }
}
