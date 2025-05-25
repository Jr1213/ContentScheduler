<?php

namespace App\Http\Controllers\API;

use App\Actions\User\DeleteProfileAction;
use App\Actions\User\UpdatePasswordAction;
use App\Actions\User\UpdateProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\DeleteProfileRequest;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    public function __construct(
        private readonly UpdateProfileAction $updateProfileAction,
        private readonly UpdatePasswordAction $updatePasswordAction,
        private readonly DeleteProfileAction $deleteProfileAction
    ) {}

    public function index(): JsonResponse
    {
        try {
            return $this->response(data: ['user' => request()->user()]);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $data = $this->updateProfileAction->handle($request->validated());

            return $this->response(data: $data, message: 'Profile updated successfully');
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        try {
            $this->updatePasswordAction->handle($request->validated()['password']);

            return $this->response(message: 'Password updated successfully');
        } catch (Exception $e) {
            return $this->error($e);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteProfileRequest $request)
    {
        try {
            $this->deleteProfileAction->handle();

            return $this->response(message: 'Profile deleted successfully', status: Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }
}
