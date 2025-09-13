<?php

namespace App\Models;

use App\Models\Year;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Timetable extends Model
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
    ];

    public function year()
    {
        return $this->belongsTo(Year::class);
    }
}
