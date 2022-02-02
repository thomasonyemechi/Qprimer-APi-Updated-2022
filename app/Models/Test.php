<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'program_id', 'questions', 'correct', 'start', 'end', 'answered', 'answers',
    ];

    protected $table = 'test';

    function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    function answers()
    {
        return $this->hasMany(Answer::class, 'test_id');
    }



}
