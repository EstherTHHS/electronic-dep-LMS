<?php

namespace App\Models;

use App\Models\Year;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Timetable extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'year_id',
        'name',
        'description',
    ];

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    // You can define collections if needed
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('timetables');
    }
}
