<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Traits\ModelHelper;
use App\Models\User;

class UserService
{
    use ModelHelper;

    public function getAll()
    {
        $filters = request()->input('filters', []);
        return User::filter($filters)->get();
    }

    public function find($userId)
    {
        return $this->findByIdOrFail(User::class, 'user', $userId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $user = User::create($validatedData);

        // Assign user role
        $user->assignRole($validatedData['role']);

        DB::commit();

        return true;
    }

    public function update($validatedData, $userId)
    {
        $user = $this->find($userId);

        DB::beginTransaction();

        $user->update($validatedData);

        // Sync user roles
        $user->syncRoles([$validatedData['role']]);

        DB::commit();

        return true;
    }

    public function delete($userId)
    {
        $user = $this->find($userId);

        DB::beginTransaction();

        $user->delete();

        DB::commit();

        return true;
    }

    public function updateStatus($validatedData , $userId)
    {
        $user = $this->find($userId);

        DB::beginTransaction();

        $user->update($validatedData);

        DB::commit();

        return $user->status;
    }
}
