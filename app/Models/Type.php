<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'description', 'slug', 'owner',
    ];

    protected $table = 'type';


    public function subjects()
    {
        return $this->hasMany(Subject::class, 'type_id');
    }

    public function years()
    {
        return $this->hasMany(Year::class, 'type_id');
    }

    public function programs()
    {
        return $this->hasMany(Program::class, 'type');
    }

    function subscription()
    {
        return $this->hasMany(Subscription::class, 'exam_id');
    }

}
