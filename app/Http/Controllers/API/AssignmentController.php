<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Assignment\AssignmentRepositoryInterface;

class AssignmentController extends Controller
{
    private AssignmentRepositoryInterface $assignmentRepository;
    public function __construct(AssignmentRepositoryInterface $assignmentRepository)
    {
        $this->assignmentRepository = $assignmentRepository;
    }
    public function storeAssignment(Request $request){
        $assignment = $this->assignmentRepository->storeAssignment($request->all());
        ResponseData($assignment);
    }

    public function getAssignments(){
        $assignments = $this->assignmentRepository->getAssignments();
        ResponseData($assignments);
    }

    public function getAssignmentsByTeacherId($teacherId){
        $assignments = $this->assignmentRepository->getAssignmentsByTeacherId($teacherId);
        ResponseData($assignments);
    }

    public function getAssignmentsByStudentId($studentId){
        $assignments = $this->assignmentRepository->getAssignmentsByStudentId($studentId);
        ResponseData($assignments);
    }

    public function getTeachers(){
        $teachers = $this->assignmentRepository->getTeachers();
        ResponseData($teachers);
    }

    public function getStudents(){
        $students = $this->assignmentRepository->getStudents();
        ResponseData($students);
    }

    public function getStudentAssignments($studentId)
    {
        $assignments = $this->assignmentRepository->getStudentAssignments($studentId);
        ResponseData($assignments);
    }

    public function getTeacherYearSubjects($teacher_id){
        $yearSubjects = $this->assignmentRepository->getTeacherYearSubjects($teacher_id);
        ResponseData($yearSubjects);
    }

    public function getAssignmentById($id){
        $assignment = $this->assignmentRepository->getAssignmentById($id);
        ResponseData($assignment);
    }

    public function storeAssignmentCategory(Request $request){
        $assignmentCategory = $this->assignmentRepository->storeAssignmentCategory($request->all());
        ResponseData($assignmentCategory);
    }

    public function getAssignmentCategories(){
        $assignmentCategories = $this->assignmentRepository->getAssignmentCategories();
        ResponseData($assignmentCategories);
    }

    public function getAssignmentCategoryById($id){
        $assignmentCategory = $this->assignmentRepository->getAssignmentCategoryById($id);
        ResponseData($assignmentCategory);
    }

    public function deleteAssignmentFile($id){
        $assignment = $this->assignmentRepository->deleteAssignmentFile($id);
        ResponseData($assignment);
    }

    public function storeSubmission(Request $request){
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'File input is missing.'], 400);
        }

        $submission = $this->assignmentRepository->storeSubmission($request->all() , $request->file('file'));
        ResponseData($submission);
    }

    public function updateSubmissionById($id, Request $request){
        $submission = $this->assignmentRepository->updateSubmissionById($id, $request->all());
        ResponseData($submission);
    }
    public function getSubjectListByYearId($yearId){
        $subjectList = $this->assignmentRepository->getSubjectListByYearId($yearId);
        ResponseData($subjectList);
    }
    public function getSubmissionList(Request $request){
        $submissionList = $this->assignmentRepository->getSubmissionList($request);
        ResponseData($submissionList);
    }

    public function getSubmissionById($id){
        $submission = $this->assignmentRepository->getSubmissionById($id);
        ResponseData($submission);
    }
}
