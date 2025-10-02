<?php

namespace App\Repositories\Assignment;

use App\Models\User;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\YearSubject;
use App\Models\TeacherSubject;
use App\Models\AssignmentCategory;
use App\Models\Year;
use App\Models\StudentYear;
use Illuminate\Support\Facades\DB;

class AssignmentRepository implements AssignmentRepositoryInterface
{
    public function getTeachers()
    {
        return User::role('teacher')->orderBy('id', 'desc')->paginate(config('common.list_count'));
    }

    public function getStudentAssignments($studentId)
    {
        $studentYear = StudentYear::with('year.subjects.assignments.media')
            ->where('student_id', $studentId)
            ->firstOrFail();

        $assignments = $studentYear->year->subjects->flatMap->assignments;

        return $assignments->map(function ($assignment) use ($studentId) {
            $isSubmitted = $assignment->submissions()
                ->where('student_id', $studentId)
                ->exists();

            $assignment->is_submitted = $isSubmitted;

            $assignment->file_url = $assignment->getFirstMediaUrl('assignment');
            $assignment->file_path = $assignment->getFirstMediaPath('assignment');

            return $assignment;
        });
    }

    public function getStudents()
    {
        return User::role('student')->orderBy('id', 'desc')->paginate(config('common.list_count'));
    }

    public function getTeacherYearSubjects($teacher_id)
    {
        return TeacherSubject::with('subject', 'teacher')
            ->where('teacher_id', $teacher_id)
            ->paginate(config('common.list_count'));
    }

    public function getAssignments()
    {
        return Assignment::with(['assignmentCategory', 'subject', 'teacher', 'media', 'submissions.media'])
            ->orderBy('id', 'desc')
            ->paginate(config('common.list_count'))->map(function ($assignment) {
                $assignment->file_url = $assignment->getFirstMediaUrl('assignment');
                $assignment->file_path = $assignment->getFirstMediaPath('assignment');
                return $assignment;
            });
    }

    public function getAssignmentsByStudentId($studentId)
    {
        $assignments = Assignment::with([
            'assignmentCategory',
            'subject',
            'teacher',
            'media',
            'submissions' => function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            }
        ])
            ->orderBy('id', 'desc')
            ->paginate(config('common.list_count'))
            ->through(function ($assignment) {
                $assignment->file_url = $assignment->getFirstMediaUrl('assignment');
                $assignment->file_path = $assignment->getFirstMediaPath('assignment');
                $submission = $assignment->submissions->first();
                $assignment->is_submitted = (bool) $submission;
                unset($assignment->submissions);
                return $assignment;
            });

        return $assignments;
    }

    public function getAssignmentsByTeacherId($teacherId) {
        return Assignment::with(['assignmentCategory', 'subject', 'teacher', 'media', 'submissions.media'])
            ->orderBy('id', 'desc')
            ->where('teacher_id', $teacherId)
            ->paginate(config('common.list_count'))->map(function ($assignment) {
                $assignment->file_url = $assignment->getFirstMediaUrl('assignment');
                $assignment->file_path = $assignment->getFirstMediaPath('assignment');
                return $assignment;
            });
    }
    public function storeAssignment($data)
    {
        DB::beginTransaction();
        try {
            $uploadedFile = $data['file'] ?? null;
            $assignment = Assignment::updateOrCreate(['id' => $data['id'] ?? null], $data);

            if ($uploadedFile) {
                $assignment->clearMediaCollection('assignment');
                $assignment->addMedia($uploadedFile)
                    ->usingName($uploadedFile->getClientOriginalName())
                    ->toMediaCollection('assignment');
            }
            DB::commit();
            return $assignment;
        } catch (\Throwable $th) {
            DB::rollBack();
            ResponseMessage($th->getMessage(), 402);
            throw $th;
        }
    }

    public function getAssignmentById($id)
    {
        $assignment = Assignment::with(['assignmentCategory', 'subject', 'teacher', 'media', 'submissions.media'])
            ->findOrFail($id);
        $assignment->file_url  = $assignment->getFirstMediaUrl('assignment');
        $assignment->file_path = $assignment->getFirstMediaPath('assignment');
        return $assignment;
    }
    public function storeAssignmentCategory($data)
    {
        DB::beginTransaction();
        try {
            $assignmentCategory = AssignmentCategory::updateOrCreate(
                ['id' => $data['id'] ?? null],
                $data
            );
            DB::commit();
            return $assignmentCategory;
        } catch (\Throwable $th) {
            DB::rollBack();
            ResponseMessage($th->getMessage(), 402);
            throw $th;
        }
    }
    public function getAssignmentCategories()
    {
        return AssignmentCategory::orderBy('id', 'desc')->get();
    }

    public function getAssignmentCategoryById($id)
    {
        return AssignmentCategory::findOrFail($id);
    }

    public function deleteAssignmentFile($id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->deleteMedia('assignment');
    }

    public function storeSubmission($data , $file)
    {
        DB::beginTransaction();
        try {
            $data['submitted_at'] = now();
            $uploadedFile = $file ?? null;
            $data['total_mark'] = $data['total_mark'] ?? 0;
            $data['mark_in_percentage'] = $data['mark_in_percentage'] ?? 0;
            $submission = Submission::create($data);
            if ($uploadedFile) {
                $submission->addMedia($uploadedFile)
                    ->usingName($uploadedFile->getClientOriginalName())
                    ->toMediaCollection('submission');
            }
            DB::commit();
            return $submission;
        } catch (\Throwable $th) {
            DB::rollBack();
            ResponseMessage($th->getMessage(), 402);
            throw $th;
        }
    }
    public function updateSubmissionById($id, $data)
    {
        DB::beginTransaction();
        try {
            $submission = Submission::findOrFail($id);
            $data['total_mark'] = $data['total_mark'] ?? 0;
            $data['mark_in_percentage'] = $data['mark_in_percentage'] ?? 0;
            $data['graded_by'] = $data['graded_by'] ?? null;
            $submission->update($data);
            DB::commit();
            return $submission;
        } catch (\Throwable $th) {
            DB::rollBack();
            ResponseMessage($th->getMessage(), 402);
            throw $th;
        }
    }

    public function getSubjectListByYearId($yearId)
    {
        return YearSubject::with(['subject.assignments.assignmentCategory', 'subject.assignments.media', 'subject.assignments.submissions.media'])->where('year_id', $yearId)->get();
    }

    public function getSubmissionList($request)
    {
        $submission = Submission::with(
            [
                'assignment.assignmentCategory',
                'assignment.subject',
                'assignment.subject.years',
                'student',
                'gradedBy',
                'assignment.teacher',
                'assignment.media',
                'media'
            ]
        )
            ->orderBy('id', 'desc');

        if ($request->has('teacher_id')) {
            $submission = $submission->whereHas('assignment', function ($query) use ($request) {
                $query->where('teacher_id', $request->teacher_id);
            });
        }

        if ($request->has('subject_id')) {
            $submission = $submission->whereHas('assignment', function ($query) use ($request) {
                $query->where('subject_id', $request->subject_id);
            });
        }

        if ($request->has('year_id')) {
            $submission = $submission->whereHas('assignment.subject.years', function ($query) use ($request) {
                $query->where('years.id', $request->year_id);
            });
        }
        return $submission->paginate(config('common.list_count'));
    }

    public function getSubmissionById($id)
    {
        return Submission::with(
            [
                'assignment',
                'assignment.assignmentCategory',
                'assignment.subject',
                'assignment.subject.years',
                'student',
                'gradedBy',
                'assignment.teacher',
                'assignment.media',
                'media'
            ]
        )
            ->findOrFail($id);
    }
}
