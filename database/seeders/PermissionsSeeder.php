<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            // User permissions
            Permission::findOrCreate('create-users');
            Permission::findOrCreate('view-users');
            Permission::findOrCreate('update-users');
            Permission::findOrCreate('delete-users');

            // Course permissions
            Permission::findOrCreate('create-courses');
            Permission::findOrCreate('view-courses');
            Permission::findOrCreate('update-courses');
            Permission::findOrCreate('delete-courses');

            // Assignment permissions
            Permission::findOrCreate('create-assignments');
            Permission::findOrCreate('view-assignments');
            Permission::findOrCreate('update-assignments');
            Permission::findOrCreate('delete-assignments');

            // Submission log permissions
            Permission::findOrCreate('create-submissions-log');
            Permission::findOrCreate('view-submissions-log');
            Permission::findOrCreate('update-submissions-log');
            Permission::findOrCreate('delete-submissions-log');

            // Submission permissions
            Permission::findOrCreate('create-submissions');
            Permission::findOrCreate('view-submissions');
            Permission::findOrCreate('update-submissions');
            Permission::findOrCreate('delete-submissions');

            // Roles and permissions management
            Permission::findOrCreate('create-roles-permissions');
            Permission::findOrCreate('view-roles-permissions');
            Permission::findOrCreate('update-roles-permissions');
            Permission::findOrCreate('delete-roles-permissions');
        });
    }
}
