<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stall extends Model
{
    use HasFactory;

    protected $fillable = [
        'stall_name',
        'stall_description',
        'stall_date',
        'stall_location',
        'time_range',
        'contact_email',
        'stall_price',
    ];
}
