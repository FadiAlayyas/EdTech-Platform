<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Traits\ModelHelper;

class RoleService
{
    use ModelHelper;

    public function getAllRoles()
    {
        return Role::get();
    }

    public function createRole(array $data): array
    {
        DB::transaction(function () use ($data, &$roleData) {
            $role = Role::create(['name' => $data['name']]);
            $roleData = [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions,
            ];
        });

        return $roleData;
    }

    public function getRolePermissionsForEdit(int $roleId): array
    {
        return DB::transaction(function () use ($roleId) {

            $role = $this->findByIdOrFail(Role::class, 'role', $roleId);

            $permissions = Permission::get();

            $rolePermissions = $role->permissions->pluck('id')->toArray();

            $permissions = $permissions->map(function ($permission) use ($rolePermissions) {
                $permission->status = in_array($permission->id, $rolePermissions);
                return $permission;
            });

            return [
                'role' => $role,
                'permissions' => $permissions,
            ];
        });
    }

    public function updateRolePermissions(int $roleId, array $data): Role
    {
        return DB::transaction(function () use ($roleId, $data) {
            $role = $this->findByIdOrFail(Role::class, 'role', $roleId);

            if ($data['status']) {
                $role->givePermissionTo($data['permission']);
            } else {
                $role->revokePermissionTo($data['permission']);
            }

            return $role;
        });
    }

    public function getUserPermissions()
    {
        return DB::transaction(function () {
            $user = Auth::guard('user')->user();
            return $user->getPermissionsViaRoles();
        });
    }
}
