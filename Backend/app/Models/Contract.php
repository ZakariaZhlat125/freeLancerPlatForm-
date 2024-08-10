<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    public function project() {
        return $this->belongsTo(Project::class);
    }
    
    public function freelancer() {
        return $this->belongsTo(User::class, 'freelancer_id');
    }
    
    public function seeker() {
        return $this->belongsTo(User::class, 'seeker_id');
    }
    
}
