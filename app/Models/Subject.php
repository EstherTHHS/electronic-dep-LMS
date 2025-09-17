<?php

namespace App\Models;

use App\Models\User;
use App\Models\Assignment;
use App\Models\TeacherSubject;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    public function years()
    {
        return $this->belongsToMany(Year::class, 'year_subjects', 'subject_id', 'year_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_subjects', 'subject_id', 'teacher_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function teacherSubjects()
    {
        return $this->hasMany(TeacherSubject::class, 'subject_id');
    }
}
