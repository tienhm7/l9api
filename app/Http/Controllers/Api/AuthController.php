<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\ManagerResource;
use App\Services\EmployeeService;
use App\Services\ManagerService;
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
    private EmployeeService $employeeService;
    private ManagerService $managerService;
    private PassportService $passportService;

    public function __construct(
        UserService     $userService,
        EmployeeService $employeeService,
        ManagerService $managerService,
        PassportService $passportService
    )
    {
        $this->userService = $userService;
        $this->employeeService = $employeeService;
        $this->managerService = $managerService;
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
     * Register employee
     */
    /**
     * @OA\Post(
     *  path="/employee/register",
     *  operationId="registerEmployee",
     *  tags={"Employee"},
     *  summary="Register $employeeRepo",
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
     *    description="Returns access token, refresh token, expire time, employee data",
     *  ),
     * )
     */
    public function registerEmployee(RegisterRequest $request): JsonResponse
    {
        $attribute = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ];

        // register new user
        $employee = $this->employeeService->store($attribute);

        if (!$employee) return response()->json(['error' => trans('auth.register_false')]);

        // Get credential information
        $credentials = request(['email', 'password']);

        // Get info client by provider
        $client = $this->passportService->getClientByProvider(PROVIDER_EMPLOYEE);

        // Get Access Token, Refresh Token and Expires In
        $result = $this->passportService->getTokenAndRefreshToken($credentials, $client->id, $client->secret);

        return response()->json([
            'accessToken' => $result['access_token'],
            'expiresIn' => $result['expires_in'],
            'refreshToken' => $result['refresh_token'],
            'user' => new EmployeeResource($employee->fresh())
        ]);
    }

    /**
     * Register manager
     */
    /**
     * @OA\Post(
     *  path="/manager/register",
     *  operationId="registerManager",
     *  tags={"Manager"},
     *  summary="Register $managerRepo",
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
     *    description="Returns access token, refresh token, expire time, manager data",
     *  ),
     * )
     */
    public function registerManager(RegisterRequest $request): JsonResponse
    {
        $attribute = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ];

        // register new user
        $manager = $this->managerService->store($attribute);

        if (!$manager) return response()->json(['error' => trans('auth.register_false')]);

        // Get credential information
        $credentials = request(['email', 'password']);

        // Get info client by provider
        $client = $this->passportService->getClientByProvider(PROVIDER_MANAGER);

        // Get Access Token, Refresh Token and Expires In
        $result = $this->passportService->getTokenAndRefreshToken($credentials, $client->id, $client->secret);

        return response()->json([
            'accessToken' => $result['access_token'],
            'expiresIn' => $result['expires_in'],
            'refreshToken' => $result['refresh_token'],
            'user' => new ManagerResource($manager->fresh())
        ]);
    }

    /**
     * Login user and create token
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
     * Login employee and create token
     */
    /**
     * @OA\Post(
     *  path="/employee/login",
     *  operationId="loginEmployee",
     *  tags={"Employee"},
     *  summary="Login employee",
     *  description="Returns access token, refresh token, expire time, employee data",
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
     *    description="Returns access token, refresh token, expire time, employee data",
     *  ),
     * )
     */
    public function loginEmployee(LoginRequest $request): JsonResponse
    {
        // Get credential information
        $credentials = request(['email', 'password']);

        // Attempt to authenticate with credentials
        if (!Auth::guard('employee')->attempt($credentials, $request->remember_me))
            return response()->json([
                'error' => 401,
                'message' => 'Unauthorized'
            ]);

        // Get info client by provider
        $client = $this->passportService->getClientByProvider(PROVIDER_EMPLOYEE);

        // Get Access Token, Refresh Token and Expires In
        $result = $this->passportService->getTokenAndRefreshToken($credentials, $client->id, $client->secret);

        return response()->json([
            'accessToken' => $result['access_token'],
            'expiresIn' => $result['expires_in'],
            'refreshToken' => $result['refresh_token'],
            'userData' => new EmployeeResource(auth()->guard('employee')->user())
        ]);
    }

    /**
     * Login manager and create token
     */
    /**
     * @OA\Post(
     *  path="/manager/login",
     *  operationId="loginManager",
     *  tags={"Manager"},
     *  summary="Login manager",
     *  description="Returns access token, refresh token, expire time, manager data",
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
     *    description="Returns access token, refresh token, expire time, manager data",
     *  ),
     * )
     */
    public function loginManager(LoginRequest $request): JsonResponse
    {
        // Get credential information
        $credentials = request(['email', 'password']);

        // Attempt to authenticate with credentials
        if (!Auth::guard('manager')->attempt($credentials, $request->remember_me))
            return response()->json([
                'error' => 401,
                'message' => 'Unauthorized'
            ]);

        // Get info client by provider
        $client = $this->passportService->getClientByProvider(PROVIDER_MANAGER);

        // Get Access Token, Refresh Token and Expires In
        $result = $this->passportService->getTokenAndRefreshToken($credentials, $client->id, $client->secret);

        return response()->json([
            'accessToken' => $result['access_token'],
            'expiresIn' => $result['expires_in'],
            'refreshToken' => $result['refresh_token'],
            'userData' => new ManagerResource(auth()->guard('manager')->user())
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
     * Get the authenticated Employee
     */
    /**
     * @OA\Get(
     *      path="/employee/info",
     *      operationId="getEmployee",
     *      tags={"Employee"},
     *      summary="Get the authenticated Employee",
     *      description="Returns employee data",
     *      @OA\Response(
     *          response=200,
     *          description="Get employee data",
     *       ),
     * )
     */
    public function employee(Request $request): EmployeeResource
    {
        return new EmployeeResource($request->user());
    }

    /**
     * Get the authenticated Manager
     */
    /**
     * @OA\Get(
     *      path="/manager/info",
     *      operationId="getManager",
     *      tags={"Manager"},
     *      summary="Get the authenticated Manager",
     *      description="Returns manager data",
     *      @OA\Response(
     *          response=200,
     *          description="Get manager data",
     *       ),
     * )
     */
    public function manager(Request $request): ManagerResource
    {
        return new ManagerResource($request->user());
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
     * Refresh Token by Provider
     * @param Request $request
     * @param $providers
     * @return mixed
     */
    public function refreshTokenByProvider(Request $request, $providers): mixed
    {
        $refresh_token = $request->header('RefreshToken');

        // Get info client by provider
        $client = $this->passportService->getClientByProvider($providers);

        // Refresh Token
        $response = $this->passportService->refreshToken($refresh_token, $client->id, $client->secret);

        return $response->json();
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
     *  @OA\Parameter(name="providers",
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
        // Refresh Token
        $response = $this->refreshTokenByProvider($request, PROVIDER_USER);

        return $response->json();
    }

    /**
     * Refresh Token Employee
     */
    /**
     * @OA\Post(
     *  path="/auth/refreshToken",
     *  operationId="refreshTokenEmployee",
     *  tags={"Employee"},
     *  summary="Refresh Token Employee",
     *  description="Refresh Token return access_token,refresh_token, and expires_in attributes",
     *  @OA\Parameter(name="providers",
     *    in="header",
     *    required=true,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Response(response="201",
     *    description="Refresh Token return",
     *  )
     * )
     */
    public function refreshTokenForEmployee(Request $request): mixed
    {
        // Refresh Token
        $response = $this->refreshTokenByProvider($request, PROVIDER_EMPLOYEE);

        return $response->json();
    }

    /**
     * Refresh Token Manager
     */
    /**
     * @OA\Post(
     *  path="/auth/refreshToken",
     *  operationId="refreshTokenManager",
     *  tags={"Manager"},
     *  summary="Refresh Token Manager",
     *  description="Refresh Token return access_token,refresh_token, and expires_in attributes",
     *  @OA\Parameter(name="providers",
     *    in="header",
     *    required=true,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Response(response="201",
     *    description="Refresh Token return",
     *  )
     * )
     */
    public function refreshTokenForManager(Request $request): mixed
    {
        // Refresh Token
        $response = $this->refreshTokenByProvider($request, PROVIDER_MANAGER);

        return $response->json();
    }
}
