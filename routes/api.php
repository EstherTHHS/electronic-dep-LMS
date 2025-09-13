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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::controller(UserController::class)->group(function () {
        Route::get('/users',  'index');
        Route::post('/users', 'store');
        Route::get('/users/{id}',  'getById');
        Route::post('/users/{id}',  'update');
        Route::delete('/users/{id}',  'delete');
        Route::post('/users/{id}/status',  'updateStatus');
        Route::post('/users/{id}/change-password',  'changePassword');
    });
    Route::controller(SubjectController::class)->group(function () {
        Route::get('/years',  'getYears');
        Route::get('/subjects',  'getSubjects');
        Route::get('/subjects/{id}',  'getSubjectById');
        Route::post('/subjects',  'storeSubject');
        Route::post('/subjects/{id}',  'updateSubjectById');
        Route::delete('/subjects/{id}',  'deleteSubjectById');
        Route::post('/subjects/{id}/toggle-status',  'toggleStatus');
        Route::post('/year-subjects',  'attachSubjectToYear');
        Route::post('/teacher-subjects',  'storeTeacherSubject');
    });
    Route::controller(AssignmentController::class)->group(function () {
        Route::get('/assignments',  'getAssignments');
        Route::post('/assignments',  'storeAssignment');
        Route::get('/teachers',  'getTeachers');
        Route::get('/students',  'getStudents');
        Route::get('/teacher-subjects/{teacher_id}',  'getTeacherYearSubjects');

        Route::get('/assignments/{id}',  'getAssignmentById');
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
        Route::post('/events',  'storeEvent');
        Route::get('/events', 'getEvents');
        Route::get('/events/{id}', 'getEventById');
        Route::delete('/events/{id}', 'deleteEventById');

        Route::post('/labs',  'updateOrCreateLab');
        Route::get('/labs', 'getLabs');
        Route::get('/labs/{id}', 'getLabById');
        Route::delete('/labs/{id}', 'deleteLabById');

        Route::get('/timetables', 'getTimetables');
        Route::post('/timetables',  'updateOrCreateTimetable');
        Route::get('/timetables/year/{yearId}', 'getTimetablesByYearId');
        Route::get('/timetables/{id}', 'getTimetableById');
        Route::delete('/timetables/{id}', 'deleteTimetableById');
    });
    Route::controller(AttendanceController::class)->group(function () {
        Route::post('/attendances',  'storeAttendance');
        Route::get('/attendances' , 'index');
        Route::get('/attendances/{id}' , 'show');
        Route::post('/attendances/{id}' , 'update');
        Route::delete('/attendances/{id}' , 'destroy');
    });
    Route::controller(StatsController::class)->group(function() {
        Route::get('/stats', 'index');
    });
});
