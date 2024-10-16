<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserAuthRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserAuthService;

class UserAuthController extends Controller
{
    public function __construct(private UserAuthService $userAuthService)
    {
    }

    public function login(UserAuthRequest $request)
    {
        $validatedData = $request->validated();
        $details = $this->userAuthService->login($validatedData);
        return $this->successResponse($this->resource($details, UserResource::class), 'userSuccessfullySignedIn', 200, $details['token']);
    }

    public function changePassword(UserAuthRequest $request)
    {
        $validatedData = $request->validated();
        $this->userAuthService->changePassword($validatedData);
        return $this->successResponse(null, 'passwordChangedSuccessfully');
    }

    public function getProfileDetails()
    {
        $user = Auth::guard('user')->user();
        return $this->successResponse(
            $this->resource($user, UserResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function logout()
    {
        $this->userAuthService->logout();

        return $this->successResponse(
            null,
            'userSuccessfullySignedOut'
        );
    }

    public function profileUpdate(UserRequest $request)
    {
        $validatedData = $request->validated();
        $this->userAuthService->profileUpdate($validatedData);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }
}
