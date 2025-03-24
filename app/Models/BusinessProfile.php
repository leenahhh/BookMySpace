<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Inertia\Inertia;

class BusinessProfile extends Model
{
    protected $fillable = [
        'name', 'product_type', 'desc', 'socials', 'logo', 'status', 'rejected_reason', 'user_id',
    ];    

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
