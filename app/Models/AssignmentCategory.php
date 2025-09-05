<?php

namespace App\Models;

use App\Models\Assignment;
use Illuminate\Database\Eloquent\Model;

class AssignmentCategory extends Model
{
    protected $fillable = [
        'name',
    ];

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
