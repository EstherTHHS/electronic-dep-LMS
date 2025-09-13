<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Attendance\AttendanceRepositoryInterface;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    private AttendanceRepositoryInterface $attendanceRepository;
    public function __construct(AttendanceRepositoryInterface $attendanceRepository)
    {
        $this->attendanceRepository = $attendanceRepository;
    }

    public function index()
    {
        $attendances = Attendance::with(['student', 'teacherSubject'])->get();

        return response()->json($attendances);
    }

    public function storeAttendance(Request $request){
        $attendance = $this->attendanceRepository->storeAttendance($request->all());
        ResponseData($attendance);
    }

    public function show($id)
    {
        $attendance = Attendance::with(['student', 'teacherSubject'])->findOrFail($id);

        return response()->json($attendance);
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $validated = $request->validate([
            'student_id' => 'sometimes|exists:users,id',
            'teacher_subject_id' => 'sometimes|exists:teacher_subjects,id',
            'date' => 'sometimes|date',
            'attendance_in_percentage' => 'sometimes|numeric|min:0|max:100',
        ]);

        $attendance->update($validated);

        return response()->json([
            'message' => 'Attendance updated successfully.',
            'data' => $attendance
        ]);
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return response()->json([
            'message' => 'Attendance deleted successfully.'
        ]);
    }
}
