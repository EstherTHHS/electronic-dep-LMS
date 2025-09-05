<?php

namespace App\Models;

use App\Models\Year;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;

class YearSubject extends Model
{
    protected $fillable = [
        'year_id',
        'subject_id',
    ];

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_subjects', 'year_subject_id', 'teacher_id');
    }
}
