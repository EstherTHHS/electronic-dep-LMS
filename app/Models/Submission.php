<?php

namespace App\Models;

use App\Models\User;
use App\Models\Assignment;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
class Submission extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'assignment_id',
        'student_id',
        'submitted_at',
        'total_mark',
        'mark_in_percentage',
        'graded_by',
        'remark'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }
    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by', 'id');
    }
}
