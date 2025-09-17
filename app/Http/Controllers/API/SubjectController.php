<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Subject\SubjectRepositoryInterface;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SubjectController extends Controller
{
    private SubjectRepositoryInterface $subjectRepository;
    public function __construct(SubjectRepositoryInterface $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    public function getYears()
    {
        $data = $this->subjectRepository->getYears();
        ResponseData($data);
    }
    public function getSubjects(Request $request)
    {
        $data = $this->subjectRepository->getSubjects($request);
        ResponseData($data);
    }
    public function getSubjectById($id)
    {
        $data = $this->subjectRepository->getSubjectById($id);
        ResponseData($data);
    }
    public function storeSubject(Request $request)
    {
        $data = $this->subjectRepository->storeSubject($request->all());
        ResponseData($data);
    }
    public function updateSubjectById($id, Request $request)
    {
        $data = $this->subjectRepository->updateSubjectById($id, $request->all());
        ResponseData($data);
    }
    public function deleteSubjectById($id)
    {
        $data = $this->subjectRepository->deleteSubjectById($id);
        ResponseData($data);
    }

    public function toggleStatus($id)
    {
        $data = $this->subjectRepository->toggleStatus($id);
        ResponseData($data);
    }
    public function attachSubjectToYear(Request $request)
    {
        $data = $this->subjectRepository->attachSubjectToYear($request->all());
        ResponseData($data);
    }

    public function storeTeacherSubject(Request $request)
    {
        $data = $this->subjectRepository->storeTeacherSubject($request->all());
        ResponseData($data);
    }
    public function getTeacherSubjects($teacherId)
    {
        $data = $this->subjectRepository->getTeacherSubjects($teacherId);
         ResponseData($data);
    }
}
