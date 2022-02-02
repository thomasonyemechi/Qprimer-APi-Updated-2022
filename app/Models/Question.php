<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id', 'topic_id', 'qn','question', 'a', 'b', 'c', 'd', 'ca', 'status', 'li',
    ];

    protected $table = 'question';

    function program(){
        return $this->belongsTo(Program::class, 'program_id');
    }

    function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }
}
