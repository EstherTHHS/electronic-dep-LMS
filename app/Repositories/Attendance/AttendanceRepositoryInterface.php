<?php

namespace App\Repositories\Attendance;

use Illuminate\Http\Request;

interface AttendanceRepositoryInterface
{
    // public function getAttendances(Request $request);

    public function storeAttendance($data);

    // public function getAttendanceById($id);

    // public function deleteAttendanceById($id);
}