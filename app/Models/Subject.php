<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'type_id', 'subject', 'code', 'li',
    ];

    protected $table = 'subject';

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function topics()
    {
        return $this->hasMany(Topic::class, 'subject_id');
    }


    public function programs()
    {
        return $this->hasMany(Program::class, 'subject');
    }

}
