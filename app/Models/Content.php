<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    // Table name (optional if it follows the default Laravel naming convention)
    protected $table = 'content';

    // Fillable attributes
    protected $fillable = [
        'business_id',
        'content_url',
        'content_desc',
    ];

    // Relationship: A BusinessContent belongs to a BusinessProfile
    public function businessProfile()
    {
        return $this->belongsTo(BusinessProfile::class, 'business_id');
    }
}
