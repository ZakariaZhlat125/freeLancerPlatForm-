<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'desciption',
        'cost',
        'duration',
        'status',
        'offers',
        'is_active',
        'category_id',
        'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function profile()
    {
        return $this->belongsTo(Profile::class, 'user_id', 'user_id');
    }
}
