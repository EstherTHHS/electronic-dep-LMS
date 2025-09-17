<?php

namespace App\Repositories\Subject;

use App\Models\Year;
use App\Models\Subject;
use App\Models\TeacherSubject;
use App\Models\YearSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectRepository implements SubjectRepositoryInterface
{

    public function getYears()
    {
        return Year::all();
    }
    public function getSubjects($request)
    {
        $subjects = Subject::query();
        if ($request->has('search')) {
            $subjects->where('name', 'like', '%' . $request->search . '%');
            $subjects->orWhere('code', 'like', '%' . $request->search . '%');
        }
        return $subjects->orderBy('id', 'desc')->get();
    }
    public function getSubjectById($id)
    {
        return Subject::find($id);
    }

    public function storeSubject($data)
    {
        return DB::transaction(function () use ($data) {
            $subject = Subject::create([
                'name'        => $data['name'],
                'code'        => $data['code'],
                'description' => $data['description'] ?? null,
                'is_active'   => $data['is_active'] ?? true,
            ]);

            if (!empty($data['teacher_ids'])) {
                foreach ($data['teacher_ids'] as $teacherId) {
                    TeacherSubject::create([
                        'teacher_id' => $teacherId,
                        'subject_id' => $subject->id,
                    ]);
                }
            }

            return $subject->load('teacherSubjects.teacher'); // return with teacher data
        });
    }

    public function updateSubjectById($id, $data)
    {
        $subject = Subject::findOrFail($id);

        $yearIds = $data['year_id'] ?? [];
        $teacherIds = $data['teacher_id'] ?? [];

        unset($data['year_id'], $data['teacher_id']);

        $subject->update($data);

        if (!empty($yearIds)) {
            $subject->years()->sync($yearIds);
        } else {
            $subject->years()->sync([]);
        }

        if (!empty($teacherIds)) {
            $subject->teachers()->sync($teacherIds);
        } else {
            $subject->teachers()->sync([]);
        }
        return $subject;
    }

    public function deleteSubjectById($id)
    {
        $subject = Subject::findOrFail($id);
        return $subject->delete();
    }

    public function toggleStatus($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        $subject->is_active = !$subject->is_active;
        return $subject->save();
    }

    public function attachSubjectToYear($data)
    {
        DB::beginTransaction();
        try {
            if (isset($data['subjects'])) {
                $subjects = json_decode($data['subjects'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return ResponseMessage('Invalid JSON data provided for supplier phone.', 400);
                }
            }
            $attachedData = [];
            foreach ($subjects as $subject) {
                $exists = YearSubject::where('year_id', $data['year_id'])
                    ->where('subject_id', $subject)
                    ->exists();

                if ($exists) {
                    DB::rollBack();
                    ResponseMessage("Subject ID {$subject} is already attached to the year.", 409);
                }
                $attachedData[] = YearSubject::firstOrCreate([
                    'year_id' => $data['year_id'],
                    'subject_id' => $subject
                ]);
            }
            DB::commit();
            return  $attachedData;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function storeTeacherSubject($data)
    {
        DB::beginTransaction();
        try {
            if (isset($data['year_subject_id'])) {
                $yearSubjectIds = json_decode($data['year_subject_id'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return ResponseMessage('Invalid JSON data provided for supplier phone.', 400);
                }
            }
            $attachedData = [];
            foreach ($yearSubjectIds as $year_subject_id) {
                $attachedData[] = TeacherSubject::firstOrCreate([
                    'teacher_id' => $data['teacher_id'],
                    'year_subject_id' => $year_subject_id
                ]);
            }
            DB::commit();
            return  $attachedData;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function getTeacherSubjects($teacherId)
    {
        $teacherSubjects = TeacherSubject::with('subject')
            ->where('teacher_id', $teacherId)
            ->get();

        return $teacherSubjects->map(fn($ts) => $ts->subject)->filter();
    }
}
