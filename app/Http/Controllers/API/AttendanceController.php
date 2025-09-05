<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Attendance\AttendanceRepositoryInterface;

class AttendanceController extends Controller
{
    private AttendanceRepositoryInterface $attendanceRepository;
    public function __construct(AttendanceRepositoryInterface $attendanceRepository)
    {
        $this->attendanceRepository = $attendanceRepository;
    }

    public function storeAttendance(Request $request){
        $attendance = $this->attendanceRepository->storeAttendance($request->all());
        ResponseData($attendance);
    }
}
