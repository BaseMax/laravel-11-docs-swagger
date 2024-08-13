<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\User\UserRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Resources\UserResource;
use App\Actions\User\CreateUserAction;
use App\Events\UserRegistered;
use App\Jobs\GenerateSiteMapJob;

/**
 * @group Authenticate
 *
 * APIs for user management
 */

/**
 * @OA\Group(title="Authentication API", version="1.0.0")
 */

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * This endpoint registers a new user and returns an access token for the user.
     *
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam email string required The email of the user. Example: john@example.com
     * @bodyParam password string required The password of the user. Example: password123
     *
     * @response 200 {
     *   "success": true,
     *   "message": "User registered successfully!",
     *   "data": {
     *     "token": "string",
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "john@example.com",
     *       "role_id": 2,
     *       "created_at": "2024-01-01T00:00:00.000000Z",
     *       "updated_at": "2024-01-01T00:00:00.000000Z"
     *     }
     *   }
     * }
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "name": [
     *       "The name field is required."
     *     ],
     *     "email": [
     *       "The email field is required.",
     *       "The email must be a valid email address."
     *     ],
     *     "password": [
     *       "The password field is required."
     *     ]
     *   }
     * }
     *
     * @param UserRequest $request
     * @return JsonResponse
     */

    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Register a new user",
     *     description="Registers a new user and returns an access token for the user.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"name", "email", "password"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="John Doe",
     *                     description="The name of the user"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="john@example.com",
     *                     description="The email of the user"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     example="password123",
     *                     description="The password of the user"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User registered successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="token", type="string", example="your_access_token_here"),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john@example.com"),
     *                     @OA\Property(property="role_id", type="integer", example=2),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"The name field is required."}
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"The email field is required.", "The email must be a valid email address."}
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"The password field is required."}
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function register(UserRequest $request, CreateUserAction $action): JsonResponse
    {
        $data = $request->validated();

        $data['password'] = \Hash::make($data['password']);

        $data['role_id'] = 2;

        try {
            $user = $action->handle($data);

            $token = $user->createToken($user->name)->plainTextToken;

            UserRegistered::dispatch($user);

            GenerateSiteMapJob::dispatch();

            return response()->json([
                "success" => true,
                "message" => "User registered successfully!",
                "data" => [
                    "token" => $token,
                    "user" => new UserResource($user)
                ]
            ]);

        } catch (\Exception $e) {

            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "data" => null
            ], 500);

        }
    }

    /**
     * Log in a user and generate a token.
     *
     * This endpoint allows a user to log in using their email and password.
     * If credentials are valid, a token is generated and returned.
     *
     * @bodyParam email string required The email of the user. Example: john@example.com
     * @bodyParam password string required The password of the user. Example: password123
     *
     * @response 200 {
     *   "success": true,
     *   "message": "User login successfully!",
     *   "data": {
     *     "token": "string"
     *   }
     * }
     *
     * @response 401 {
     *   "success": false,
     *   "message": "Invalid credential!"
     * }
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "email": [
     *       "The email field is required.",
     *       "The email must be a valid email address."
     *     ],
     *     "password": [
     *       "The password field is required."
     *     ]
     *   }
     * }
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Log in a user",
     *     description="Allows a user to log in using their email and password. If credentials are valid, a token is generated and returned.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"email", "password"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="john@example.com",
     *                     description="The email of the user"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     example="password123",
     *                     description="The password of the user"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User login successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User login successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="token", type="string", example="your_access_token_here")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid credential!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"The email field is required.", "The email must be a valid email address."}
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"The password field is required."}
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::query()->where("email", $data["email"])->first();

        if (!$user || Hash::check($data["password"], $user->password))
            return response()->json([
                "success" => false,
                "message" => "Invalid credential!"
            ]);

        $token = $user->createToken($user->name)->plainTextToken;

        return response()->json([
            "success" => true,
            "message" => "User login successfully!",
            "data" => [
                "token" => $token
            ]
        ]);
    }

    /**
     * Log out the authenticated user.
     *
     * This endpoint allows the authenticated user to log out by deleting all of their tokens.
     *
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "message": "logged out"
     * }
     *
     * @response 401 {
     *   "success": false,
     *   "message": "Unauthenticated."
     * }
     *
     * @param Request $request
     * @return JsonResponse
     */

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Log out the authenticated user",
     *     description="Allows the authenticated user to log out by deleting all of their tokens.",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="logged out")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized, user not authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "success" => true,
            "message" => "logged out"
        ]);
    }
}
