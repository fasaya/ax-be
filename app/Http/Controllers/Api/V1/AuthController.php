<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Http\Requests\V1\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
            'token' => $user->createToken('Indonesia')->plainTextToken,
        ];

        return ResponseHelper::sendResponse($data, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = auth()->user();

            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
                'token' => $user->createToken('Indonesia')->plainTextToken,
            ];

            return ResponseHelper::sendResponse($data, 'User login successfully.');
        }

        return ResponseHelper::sendErrorResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
    }
}
