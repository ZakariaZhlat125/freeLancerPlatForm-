<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
         'contract_content', 'amount', 'status','freelancer_id','seeker_id'
         ,'admin_id','freelancer_public_key','freelancer_signature',
         'seeker_public_key','seeker_signature',
         'admin_public_key','admin_signature',
    ];

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
