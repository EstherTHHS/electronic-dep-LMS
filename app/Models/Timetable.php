<?php

namespace App\Models;

use App\Models\Year;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $fillable = [
        'year_id',
        'subject_id',
        'start_time',
        'end_time',
        'day',
        'classroom',
        'class_type',
        'academic_session',
    ];

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
