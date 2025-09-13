<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\User;

class StatsController extends Controller
{
    public function index()
    {
        $totalStudents = User::role('student')->count();
        $totalTeachers = User::role('teacher')->count();

        $totalSubjects = Subject::count();

        $totalEvents = Event::count();

        return response()->json([
            'students' => $totalStudents,
            'teachers' => $totalTeachers,
            'total_users' => $totalStudents + $totalTeachers,
            'subjects' => $totalSubjects,
            'events' => $totalEvents,
        ]);
    }
}
