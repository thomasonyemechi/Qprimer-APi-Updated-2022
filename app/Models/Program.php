<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject', 'type', 'year', 'status', 'li',
    ];

    protected $table = 'program';

    public function questions()
    {
        return $this->hasMany(Question::class, 'program_id');
    }

    public function questions_pics()
    {
        return $this->hasMany(QuestionPics::class, 'program_id');
    }

    public function sub()
    {
        return $this->belongsTo(Subject::class, 'subject');
    }

    public function typ()
    {
        return $this->belongsTo(Type::class, 'type');
    }

    public function yer()
    {
        return $this->belongsTo(Year::class, 'year');
    }


}
