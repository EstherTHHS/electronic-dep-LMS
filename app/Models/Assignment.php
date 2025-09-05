<?php

namespace App\Models;

use App\Models\Submission;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Assignment extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'assignment_category_id',
        'subject_id',
        'teacher_id',
        'title',
        'description',
        'assignment_date',
        'due_date',
        'given_marks',
    ];
    public function assignmentCategory()
    {
        return $this->belongsTo(AssignmentCategory::class);
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function teacher()
    {
        return $this->belongsTo(User::class);
    }
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
