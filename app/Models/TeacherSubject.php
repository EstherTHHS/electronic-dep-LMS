<?php

namespace App\Models;

use App\Models\User;
use App\Models\Attendance;
use App\Models\YearSubject;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
    protected $table = 'teacher_subjects';
    protected $fillable = [
        'teacher_id',
        'subject_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function yearSubject()
    {
        return $this->belongsTo(YearSubject::class, 'year_subject_id', 'id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'teacher_subject_id', 'id');
    }
}
