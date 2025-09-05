<?php

namespace App\Repositories\Subject;

interface SubjectRepositoryInterface
{
    public function getYears();
    public function getSubjects($request);
    public function getSubjectById($id);
    public function storeSubject($data);
    public function updateSubjectById($id, $data);
    public function deleteSubjectById($id);
    public function toggleStatus($id);
    public function attachSubjectToYear($data);
    public function storeTeacherSubject($data);
}
