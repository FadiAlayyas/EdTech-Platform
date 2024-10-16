<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\ModelHelper;
use Exception;

class UserAuthService
{
    use ModelHelper;

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle user login.
     *
     * @param array $validatedData
     * @return User
     * @throws Exception
     */
    public function login(array $validatedData): User
    {
        $user = $this->findUserByEmail($validatedData['email']);

        $this->ensureUserIsActive($user);

        $this->attemptLogin($user, $validatedData['password']);

        $user['token'] = Auth::guard('user')->attempt([
            'email' => $user->email,
            'password' => $validatedData['password']
        ]);

        return $user;
    }

    /**
     * Change the authenticated user's password.
     *
     * @param array $validatedData
     * @return void
     * @throws Exception
     */
    public function changePassword(array $validatedData): void
    {
        $user = Auth::guard('user')->user();

        DB::transaction(function () use ($user, $validatedData) {
            $user->update(['password' => Hash::make($validatedData['new_password'])]);
        });
    }

    /**
     * Logout the authenticated user.
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::guard('user')->logout();
    }

    /**
     * Update the profile of the authenticated user.
     *
     * @param array $validatedData
     * @return bool
     */
    public function profileUpdate(array $validatedData): bool
    {
        $user = Auth::guard('user')->user();

        DB::transaction(function () use ($user, $validatedData) {
            if (isset($validatedData['new_password'])) {
                $validatedData['password'] = Hash::make($validatedData['new_password']);
            }

            $user->update($validatedData);
            $user->syncRoles([$validatedData['role']]);
        });

        return true;
    }

    /**
     * Find user by email.
     *
     * @param string $email
     * @return User
     * @throws Exception
     */
    private function findUserByEmail(string $email): User
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new Exception(__('messages.credentialsError'), 401);
        }

        return $user;
    }

    /**
     * Ensure user account is active.
     *
     * @param User $user
     * @return void
     * @throws Exception
     */
    private function ensureUserIsActive(User $user): void
    {
        if (!$user->is_active) {
            throw new Exception(__('messages.activationAccountError'), 401);
        }
    }

    /**
     * Attempt to login user with given credentials.
     *
     * @param User $user
     * @param string $password
     * @return void
     * @throws Exception
     */
    private function attemptLogin(User $user, string $password): void
    {
        $attemptedData = [
            'email' => $user->email,
            'password' => $password
        ];

        if (!Auth::guard('user')->attempt($attemptedData)) {
            throw new Exception(__('messages.incorrect_password'), 401);
        }
    }
}
