<?php

namespace App\Repositories\Attendance;

use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class AttendanceRepository implements AttendanceRepositoryInterface
{
    public function storeAttendance($data){
        DB::beginTransaction();
        try {
        if (isset($data['attendances'])) {
            $attendances = json_decode($data['attendances'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return ResponseMessage('Invalid JSON data provided for attendance.', 400);
            }
            foreach ($attendances as $attendance) {
                Attendance::updateOrCreate(
                    [
                        "id" => $attendance['id'] ?? null,
                    ],
                    [
                        'student_id' => $attendance['student_id'],
                        'teacher_subject_id' => $attendance['teacher_subject_id'],
                        'date' => $attendance['date'],
                        'attendance_in_percentage' => $attendance['attendance_in_percentage'],
                    ]
                );
            }
        }
        DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            ResponseMessage($th->getMessage(), 402);
            throw $th;
        }
    }
}
