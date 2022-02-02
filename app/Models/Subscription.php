<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;


    protected $fillable = [
        'exam_id', 'buyer_id', 'owner_id', 'price', 'status', 'trno', 
    ];

    protected $table = 'subscription';

    function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    function exam()
    {
        return $this->belongsTo(Type::class, 'exam_id');
    }


    
}
