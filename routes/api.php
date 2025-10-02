<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\SubjectController;
use App\Http\Controllers\API\TeacherController;
use App\Http\Controllers\API\AssignmetController;
use App\Http\Controllers\API\AssignmentController;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\StatsController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/users', [UserController::class, 'store']);

Route::get('/subjects', [SubjectController::class, 'getSubjects']);
Route::get('/events', [EventController::class, 'getEvents']);
Route::get('/timetables', [EventController::class, 'getTimetables']);
Route::get('/labs', [EventController::class, 'getLabs']);
Route::get('/teachers', [AssignmentController::class , 'getTeachers']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index');
        // Removed: Route::post('/users', 'store'); (moved above as public registration)
        Route::get('/users/{id}', 'getById');
        Route::post('/users/{id}', 'update');
        Route::delete('/users/{id}', 'delete');
        Route::post('/users/{id}/status', 'updateStatus');
        Route::post('/users/{id}/change-password', 'changePassword');
    });

    Route::controller(SubjectController::class)->group(function () {
        Route::get('/years', 'getYears');
        // Removed: Route::get('/subjects', 'getSubjects')->withoutMiddleware('auth.sanctum'); (moved above as public)
        Route::get('/subjects/{id}', 'getSubjectById');
        Route::post('/subjects', 'storeSubject');
        Route::post('/subjects/{id}', 'updateSubjectById');
        Route::delete('/subjects/{id}', 'deleteSubjectById');
        Route::post('/subjects/{id}/toggle-status', 'toggleStatus');
        Route::post('/year-subjects', 'attachSubjectToYear');
        Route::post('/teacher-subjects', 'storeTeacherSubject');
        Route::get('/teacher-subjects/{teacherId}', 'getTeacherSubjects');
    });

    Route::controller(AssignmentController::class)->group(function () {
        Route::get('/assignments', 'getAssignments');
        Route::get('/assignments/{teacherId}/teacher', 'getAssignmentsByTeacherId');
        Route::post('/assignments', 'storeAssignment');
        // Route::get('/teachers', 'getTeachers');
        Route::get('/students', 'getStudents');
        Route::get('/teacher-year-subjects/{teacherId}', 'getTeacherYearSubjects');

        Route::get('/assignments/{id}', 'getAssignmentById');
        Route::get('/assignments/{studentId}/student', 'getStudentAssignments');
        Route::post('/assignment-categories', 'storeAssignmentCategory');
        Route::get('/assignment-categories', 'getAssignmentCategories');
        Route::get('/assignment-categories/{id}', 'getAssignmentCategoryById');
        Route::get('/delete-assignment-file/{id}', 'deleteAssignmentFile');

        Route::get('/years/{yearId}/subjects', 'getSubjectListByYearId');

        Route::post('/submissions', 'storeSubmission');
        Route::post('/submissions/{id}', 'updateSubmissionById');
        Route::get('/submissions', 'getSubmissionList');
        Route::get('/submissions/{id}', 'getSubmissionById');
    });

    Route::controller(EventController::class)->group(function () {
        Route::post('/events', 'storeEvent');
        Route::post('/events/{id}', 'updateEvent');
        // Removed: Route::get('/events', 'getEvents')->withoutMiddleware('auth.sanctum');; (moved above as public)
        Route::get('/events/{id}', 'getEventById');
        Route::delete('/events/{id}', 'deleteEventById');

        Route::post('/labs', 'updateOrCreateLab');
        Route::post('/labs/{id}' , 'updateLab');
        // Route::get('/labs', 'getLabs');
        Route::get('/labs/{id}', 'getLabById');
        Route::delete('/labs/{id}', 'deleteLabById');

        // Route::get('/timetables', 'getTimetables');
        Route::post('/timetables', 'storeTimetable');
        Route::post('/timetables/{id}', 'updateTimetable');
        Route::get('/timetables/{id}', 'getTimetable');
        Route::delete('/timetables/{id}', 'deleteTimetable');
    });

    Route::controller(AttendanceController::class)->group(function () {
        Route::post('/attendances', 'storeAttendance');
        Route::get('/attendances' , 'index');
        Route::get('/attendances/{id}' , 'show');
        Route::post('/attendances/{id}' , 'update');
        Route::delete('/attendances/{id}' , 'destroy');
    });

    Route::controller(StatsController::class)->group(function() {
        Route::get('/stats', 'index');
    });
});
