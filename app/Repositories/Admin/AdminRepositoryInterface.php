<?php

namespace App\Repositories\Admin;

interface AdminRepositoryInterface
{

    public function getAll($request);
    public function getById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function changePassword($id, array $data);
    // public function resetPassword($id, array $data);
    public function updateStatus($id);
}
