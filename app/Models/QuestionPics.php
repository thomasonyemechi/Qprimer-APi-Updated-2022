<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionPics extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id', 'image'
    ];

    protected $table = 'question_pics';

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
}
