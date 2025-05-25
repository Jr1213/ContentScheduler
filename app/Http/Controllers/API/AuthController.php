<?php

namespace App\Http\Controllers\API;

use App\Actions\User\CreateUserAction;
use App\Actions\User\LoginAction;
use App\Dtos\Auth\LoginDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(private readonly CreateUserAction $createUserAction, private readonly LoginAction $loginAction) {}
    public function register(RegisterRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();

            $response = $this->createUserAction->handle($data);

            return $this->response(data: $response, message: 'User created successfully', status: Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $response = $this->loginAction->handle(LoginDto::create($data));
            return $this->response(data: $response, message: 'User logged in successfully', status: Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

    // public function logout() :JsonResponse
    // {

    // }
}
