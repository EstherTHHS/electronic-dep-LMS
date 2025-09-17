<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentYear extends Model
{
    protected $fillable = [
        'student_id',
        'year_id',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id', 'id');
    }
}
