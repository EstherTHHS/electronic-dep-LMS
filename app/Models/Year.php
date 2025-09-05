<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $fillable = [
        'name',
        'is_active',
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'year_subjects', 'year_id', 'subject_id');
    }
}
