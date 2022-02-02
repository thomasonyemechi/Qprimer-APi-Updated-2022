<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id', 'year', 'other',
    ];

    protected $table = 'year';

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

}
