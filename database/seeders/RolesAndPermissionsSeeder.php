<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        $teacher = Role::create(['name' => 'teacher']);
        $student = Role::create(['name' => 'student']);
        $guest = Role::create(['name' => 'guest']);

        //admin controller
        $adminList = Permission::create(['name' => 'adminList']);
        $adminCreate = Permission::create(['name' => 'adminCreate']);
        $adminEdit = Permission::create(['name' => 'adminEdit']);
        $adminUpdate = Permission::create(['name' => 'adminUpdate']);
        $adminDelete = Permission::create(['name' => 'adminDelete']);
        $adminStatus = Permission::create(['name' => 'adminStatus']);
        $adminChangePassword = Permission::create(['name' => 'adminChangePassword']);
        
        //subject controller
        $getYears = Permission::create(['name' => 'getYears']);
        $getSubjects = Permission::create(['name' => 'getSubjects']);
        $getSubjectById = Permission::create(['name' => 'getSubjectById']);
        $storeSubject = Permission::create(['name' => 'storeSubject']);
        $updateSubjectById = Permission::create(['name' => 'updateSubjectById']);
        $deleteSubjectById = Permission::create(['name' => 'deleteSubjectById']);
        $toggleStatus = Permission::create(['name' => 'toggleStatus']);
        $attachSubjectToYear = Permission::create(['name' => 'attachSubjectToYear']);
        $storeTeacherSubject = Permission::create(['name' => 'storeTeacherSubject']);


        $admin->givePermissionTo(
            [
                $adminList,
                $adminCreate,
                $adminEdit,
                $adminUpdate,
                $adminDelete,
                $adminStatus,
                $adminChangePassword,
                $getYears,
                $getSubjects,
                $getSubjectById,
                $storeSubject,
                $updateSubjectById,
                $deleteSubjectById,
                $toggleStatus,
                $attachSubjectToYear,
                $storeTeacherSubject,
            ]
        );

        $teacher->givePermissionTo(
            [
                $adminList,
                $adminChangePassword,
                $getYears,
                $getSubjects,
                $getSubjectById,
                $storeSubject,
                $updateSubjectById,
                $deleteSubjectById,
                $toggleStatus,
                $attachSubjectToYear,
                $storeTeacherSubject,
            ]
        );

        $student->givePermissionTo(
            [
                $adminChangePassword,
                $getYears,
                $getSubjects,
            ]
        );

        $guest->givePermissionTo(
            [
                $getYears,
                $getSubjects,
            ]
        );
    }
}
