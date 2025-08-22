<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response_handler('Validation failed', false,422, ['errors' => $validator->errors()->toArray()]);
            }

            $user = $this->userRepository->create($validator->validated());

            return response_handler('User registered successfully', true, 201, ['user' => $user->toArray()]);
        } catch (\Exception $e) {
            log(''. $e->getMessage());
            print("ERROR :: $e");
            return response_handler('An unexpected error occurred.',false,  500, ['error' => $e->getMessage()]);
        }
    }
}