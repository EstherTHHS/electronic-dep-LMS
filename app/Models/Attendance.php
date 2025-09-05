<?php

namespace App\Models;

use App\Models\User;
use App\Models\TeacherSubject;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'student_id',
        'teacher_subject_id',
        'date',
        'attendance_in_percentage',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }   

    public function teacherSubject()
    {
        return $this->belongsTo(TeacherSubject::class, 'teacher_subject_id', 'id');
    }
}
