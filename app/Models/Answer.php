<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id', 'test_id', 'qn', 'option', 'score'
    ];

    protected $table = 'answer';

    function test()
    {
        return $this->belongsTo(Test::class, 'test_id');
    }
    

    function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

}
