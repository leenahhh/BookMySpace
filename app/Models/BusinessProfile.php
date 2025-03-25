<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Inertia\Inertia;

class BusinessProfile extends Model
{
    
    protected $table = 'business_profiles';
    
    protected $fillable = [
        'name', 'product_type', 'desc', 'socials', 'logo', 'status', 'rejected_reason', 'user_id',
    ];    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stalls()
    {
        return $this->hasMany(Stall::class, 'business_id');
    }
    
}
