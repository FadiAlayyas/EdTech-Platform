<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Services\RoleService;
use App\Traits\ModelHelper;

class RoleController extends Controller
{
    use ModelHelper;

    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function getAll()
    {
        $roles = $this->roleService->getAllRoles();
        return $this->successResponse($roles, 'dataFetchedSuccessfully');
    }

    public function create(RoleRequest $request)
    {
        $role = $this->roleService->createRole($request->all());

        return $this->successResponse($role, 'dataAddedSuccessfully');
    }

    public function edit($id)
    {
        $rolePermissions = $this->roleService->getRolePermissionsForEdit($id);

        return $this->successResponse($rolePermissions, 'dataFetchedSuccessfully');
    }

    public function update(RoleRequest $request, $id)
    {
        $role = $this->roleService->updateRolePermissions($id, $request->all());

        return $this->successResponse($role, 'dataUpdatedSuccessfully');
    }

    public function getPermissions()
    {
        $permissions = $this->roleService->getUserPermissions();

        return $this->successResponse($permissions, 'dataFetchedSuccessfully');
    }
}
