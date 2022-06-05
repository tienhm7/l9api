<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\PassportService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    private UserService $userService;
    private PassportService $passportService;

    public function __construct(
        UserService     $userService,
        PassportService $passportService
    )
    {
        $this->userService = $userService;
        $this->passportService = $passportService;
    }

    /**
     * Register user
     */
    /**
     * @OA\Post(
     *  path="/auth/register",
     *  operationId="registerUser",
     *  tags={"User"},
     *  summary="Register user",
     *  description="Returns message and status",
     *  @OA\Parameter(name="name",
     *    in="path",
     *    required=true,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(name="email",
     *    in="path",
     *    required=true,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(name="password",
     *    in="path",
     *    required=true,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Response(response="200",
     *    description="Returns access token, refresh token, expire time, user data",
     *  ),
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $attribute = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ];

        // register new user
        $user = $this->userService->store($attribute);

        if (!$user) return response()->json(['error' => trans('auth.register_false')]);

        // Get credential information
        $credentials = request(['email', 'password']);

        // Get info client by provider
        $client = $this->passportService->getClientByProvider(PROVIDER_USER);

        // Get Access Token, Refresh Token and Expires In
        $result = $this->passportService->getTokenAndRefreshToken($credentials, $client->id, $client->secret);

        return response()->json([
            'accessToken' => $result['access_token'],
            'expiresIn' => $result['expires_in'],
            'refreshToken' => $result['refresh_token'],
            'user' => new UserResource($user->fresh())
        ]);
    }

    /**
     * Login user and create token
     */
    /**
     * Register user
     */
    /**
     * @OA\Post(
     *  path="/auth/login",
     *  operationId="loginUser",
     *  tags={"User"},
     *  summary="Login user",
     *  description="Returns access token, refresh token, expire time, user data",
     *  @OA\Parameter(name="email",
     *    in="path",
     *    required=true,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(name="password",
     *    in="path",
     *    required=true,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(name="remember_me",
     *    in="path",
     *    required=true,
     *    @OA\Schema(type="boolean")
     *  ),
     *  @OA\Response(response="401",
     *    description="Unauthorized",
     *  ),
     *  @OA\Response(response="200",
     *    description="Returns access token, refresh token, expire time, user data",
     *  ),
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Get credential information
        $credentials = request(['email', 'password']);

        // Attempt to authenticate with credentials
        if (!Auth::attempt($credentials, $request->remember_me)) {
            return response()->json([
                'error' => HTTP_UNAUTHORIZED,
                'message' => trans('auth.unauthorized')
            ]);
        }

        // Get info client by provider
        $client = $this->passportService->getClientByProvider(PROVIDER_USER);

        // Get Access Token, Refresh Token and Expires In
        $result = $this->passportService->getTokenAndRefreshToken($credentials, $client->id, $client->secret);

        return response()->json([
            'accessToken' => $result['access_token'],
            'expiresIn' => $result['expires_in'],
            'refreshToken' => $result['refresh_token'],
            'userData' => new UserResource($request->user())
        ]);
    }

    /**
     * Get the authenticated User
     */
    /**
     * @OA\Get(
     *      path="/auth/user",
     *      operationId="getUser",
     *      tags={"User"},
     *      summary="Get the authenticated User",
     *      description="Returns user data",
     *      @OA\Response(
     *          response=200,
     *          description="Get user data",
     *       ),
     * )
     */
    public function user(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    /**
     * Logout user (Revoke the token)
     */
    /**
     * @OA\Get(
     *      path="/auth/logout",
     *      operationId="logout",
     *      tags={"Common"},
     *      summary="Logout user/customer (Revoke the token)",
     *      description="Returns message",
     *      @OA\Response(
     *          response=200,
     *          description="Returns message",
     *       ),
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => trans('auth.logout_success')
        ]);
    }

    /**
     * Refresh Token User
     */
    /**
     * @OA\Post(
     *  path="/auth/refreshToken",
     *  operationId="refreshTokenUser",
     *  tags={"User"},
     *  summary="Refresh Token User",
     *  description="Refresh Token return access_token,refresh_token, and expires_in attributes",
     *  @OA\Parameter(name="RefreshToken",
     *    in="header",
     *    required=true,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Response(response="201",
     *    description="Refresh Token return",
     *  )
     * )
     */
    public function refreshToken(Request $request): mixed
    {
        $refresh_token = $request->header('RefreshToken');

        // Get info client by provider
        $client = $this->passportService->getClientByProvider(PROVIDER_USER);

        // Refresh Token
        $response = $this->passportService->refreshToken($refresh_token, $client->id, $client->secret);

        return $response->json();
    }
}
