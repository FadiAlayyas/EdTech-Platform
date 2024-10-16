<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {

            // Define Roles
            $student = Role::findOrCreate('Student');
            $teacher = Role::findOrCreate('Teacher');
            $admin   = Role::findOrCreate('Admin');

            // Assign permissions to Student
            $student->givePermissionTo([
                'view-courses',
                'view-assignments',
                'create-submissions',
                'view-submissions',
                'view-roles-permissions'
            ]);

            // Assign permissions to Teacher
            $teacher->givePermissionTo([
                'view-courses',
                'create-courses',
                'update-courses',
                'delete-courses',
                'view-assignments',
                'create-assignments',
                'update-assignments',
                'delete-assignments',
                'view-submissions-log',
            ]);

            // Assign permissions to Admin
            $admin->givePermissionTo(Permission::all());
        });
    }
}
