<?php

namespace App\Repositories\Admin;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Models\Role as ModelsRole;

class AdminRepository implements AdminRepositoryInterface
{
    public function getAll($request)
    {
        $query = User::with('roles')->orderBy('id', 'desc');

        if ($request && $request->has('keyword') && !empty($request->keyword)) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('email', 'LIKE', "%{$keyword}%");
                $q->orWhereHas('roles', function ($qRole) use ($keyword) {
                    $qRole->where('name', 'LIKE', "%{$keyword}%");
                });
            });
        }

        return $query->paginate(config('common.list_count'));
    }

    public function getById($id)
    {
        $user = User::with('roles' , 'studentYear.year')->find($id);
        if (!$user) {
            ResponseMessage('Admin not found', 404);
        }
        return $user;
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $user = User::create($data);

            $user->assignRole($data['role'] ?? 'admin');

            if (($data['role'] ?? '') === 'student' && isset($data['year_id'])) {
                $user->studentYear()->create([
                    'year_id' => $data['year_id'],
                ]);
            }

            DB::commit();
            ResponseData($user);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

public function update($id, array $data)
{
    $user = User::find($id);
    if (! $user) {
        ResponseMessage('Admin not found', 404);
    }
    DB::beginTransaction();
    try {
        $user->update($data);

        if (isset($data['year_id']) && $data['year_id'] !== null) {
            $user->studentYear()->updateOrCreate(
                ['student_id' => $user->id],
                ['year_id' => $data['year_id']]
            );
        }

        if (isset($data['role']) && $data['role'] !== null) {
            $user->syncRoles($data['role']);
        }

        DB::commit();
        ResponseData($user);
    } catch (\Exception $e) {
        DB::rollback();
        ResponseMessage($e->getMessage(), 402);
        throw $e;
    }
}

    public function delete($id)
    {
        $user = User::find($id);
        if (! $user) {
            ResponseMessage('Admin not found', 404);
        }
        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
            ResponseMessage('Admin deleted successfully', 200);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function changePassword($id, array $data)
    {
        $user = User::find($id);
        if (!$user) {
            ResponseMessage('Admin not found', 404);
        }
        DB::beginTransaction();
        try {
            if (isset($data['current_password']) && !password_verify($data['current_password'], $user->password)) {
                ResponseMessage('Old password is incorrect', 402);
            }
            if (isset($data['new_password']) && $data['new_password'] !== $data['confirm_new_password']) {
                ResponseMessage('New Password and confirm password do not match', 402);
            }
            $user->update([
                'password' => ($data['confirm_new_password']),
            ]);
            DB::commit();
            ResponseMessage('Password changed successfully', 200);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    // public function resetPassword($id, array $data)
    // {
    //     $user = User::find($id);
    //     if (!$user) {
    //         ResponseMessage('Admin not found', 404);
    //     }
    //     DB::beginTransaction();
    //     try {
    //         if (isset($data['new_password']) && $data['new_password'] !== $data['confirm_new_password']) {
    //             ResponseMessage('New Password and confirm password do not match', 402);
    //         }
    //         $user->update([
    //             'password' => ($data['confirm_new_password']),
    //         ]);
    //         DB::commit();
    //         ResponseMessage('Password reset successfully', 200);
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         ResponseMessage($e->getMessage(), 402);
    //         throw $e;
    //     }
    // }

    public function updateStatus($id)
    {
        $user = User::find($id);
        if (!$user) {
            ResponseMessage('user not found', 404);
        }
        DB::beginTransaction();
        try {
            $user->update([
                'is_active' => !$user->is_active,
            ]);
            DB::commit();
            ResponseMessage('Status updated successfully', 200);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
