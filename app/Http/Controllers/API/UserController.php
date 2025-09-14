<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Repositories\Admin\AdminRepositoryInterface;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller
{
    private AdminRepositoryInterface $adminRepository;
    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function index(Request $request)
    {
        $data = $this->adminRepository->getAll($request);
        ResponseData($data);
    }
    public function store(AdminRequest $request)
    {
        $validatedData = $request->validated();
        if ($request->hasFile('user_image')) {
            $uploadedFile = UploadFileToServer($request, 'user_image', 'user_images');
            $validatedData['image_url'] = $uploadedFile['file_url'];
            $validatedData['image_path'] = $uploadedFile['file_path'];
        }

        $data = $this->adminRepository->create($validatedData);
        ResponseData($data);
    }

    public function getById($id)
    {
        $data = $this->adminRepository->getById($id);
        ResponseData($data);
    }
    public function update($id, Request $request)
    {
        $data = $this->adminRepository->update($id, $request->all());
        ResponseData($data);
    }

    public function delete($id)
    {
        $data = $this->adminRepository->delete($id);
        ResponseData($data);
    }

    public function changePassword($id, Request $request)
    {
        $data = $this->adminRepository->changePassword($id, $request->all());
        ResponseData($data);
    }

    public function updateStatus($id)
    {
        $data = $this->adminRepository->updateStatus($id);
        ResponseData($data);
    }
}
