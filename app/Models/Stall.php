<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stall extends Model
{
    use HasFactory;

    protected $fillable = [
        'stall_date',
        'stall_location',
        'time_range',
        'contact_email',
        'stall_price',
        'receipt',
        'status',
        'user_id',
        'business_id',
        'stall_rejected_reason'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function businessProfile()
    {
        return $this->belongsTo(BusinessProfile::class, 'business_id');
    }
}
