<?php

namespace App\Repositories\Assignment;

use Illuminate\Http\Request;


interface AssignmentRepositoryInterface
{
    public function getTeachers();
    public function getStudents();
    public function getTeacherYearSubjects($teacher_id);
    public function getAssignments();
    public function storeAssignment($data);
    public function getAssignmentById($id);
    public function storeAssignmentCategory($data);
    public function getAssignmentCategories();
    public function getAssignmentCategoryById($id);
    public function deleteAssignmentFile($id);
    public function storeSubmission($data);
    public function updateSubmissionById($id, $data);
    public function getSubjectListByYearId($yearId);
    public function getSubmissionList(Request $request);
    public function getSubmissionById($id);
}
