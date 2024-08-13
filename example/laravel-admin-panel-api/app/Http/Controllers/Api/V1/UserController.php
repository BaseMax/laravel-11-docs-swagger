<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\User\UserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Actions\User\GetUsersAction;
use App\Actions\User\CreateUserAction;
use App\Actions\User\UpdateUserAction;
use App\Actions\User\DeleteUserAction;

/**
 * @group User Management
 *
 * APIs for managing users
 */

/**
 * @OA\Tag(
 *     name="Users",
 *     description="User management"
 * )
 */

class UserController extends Controller
{

    /**
     * Retrieve a list of users.
     *
     * This endpoint returns a paginated list of all users.
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "john@example.com",
     *       "role_id": 2,
     *       "created_at": "2024-01-01T00:00:00.000000Z",
     *       "updated_at": "2024-01-01T00:00:00.000000Z"
     *     },
     *     {
     *       "id": 2,
     *       "name": "Jane Smith",
     *       "email": "jane@example.com",
     *       "role_id": 2,
     *       "created_at": "2024-01-02T00:00:00.000000Z",
     *       "updated_at": "2024-01-02T00:00:00.000000Z"
     *     }
     *   ],
     *   "links": {
     *     "first": "http://example.com/api/users?page=1",
     *     "last": "http://example.com/api/users?page=10",
     *     "prev": null,
     *     "next": "http://example.com/api/users?page=2"
     *   },
     *   "meta": {
     *     "current_page": 1,
     *     "from": 1,
     *     "last_page": 10,
     *     "links": [
     *       {
     *         "url": "http://example.com/api/users?page=1",
     *         "label": "&laquo; Previous",
     *         "active": false
     *       },
     *       {
     *         "url": "http://example.com/api/users?page=2",
     *         "label": "Next &raquo;",
     *         "active": true
     *       }
     *     ],
     *     "per_page": 15,
     *     "to": 15,
     *     "total": 150
     *   }
     * }
     *
     * @response 500 {
     *   "message": "Internal Server Error."
     * }
     *
     * @return ResourceCollection
     */

    /**
     * @OA\Get(
     *     path="api/v1/users",
     *     summary="Retrieve a list of users",
     *     description="Returns a paginated list of all users.",
     *     tags={"User"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of users.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john@example.com"),
     *                     @OA\Property(property="role_id", type="integer", example=2),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00.000000Z")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string", example="http://example.com/api/users?page=1"),
     *                 @OA\Property(property="last", type="string", example="http://example.com/api/users?page=10"),
     *                 @OA\Property(property="prev", type="string", example="null"),
     *                 @OA\Property(property="next", type="string", example="http://example.com/api/users?page=2")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=10),
     *                 @OA\Property(
     *                     property="links",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="url", type="string", example="http://example.com/api/users?page=1"),
     *                         @OA\Property(property="label", type="string", example="« Previous"),
     *                         @OA\Property(property="active", type="boolean", example=false)
     *                     )
     *                 ),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="to", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=150)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal Server Error.")
     *         )
     *     )
     * )
     */

    public function index(GetUsersAction $action): ResourceCollection
    {
        return UserResource::collection($action->handle());
    }

    /**
     * Create a new user.
     *
     * This endpoint allows an authorized user to create a new user.
     *
     * @authenticated
     *
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam email string required The email of the user. Example: john@example.com
     * @bodyParam password string required The password of the user. Example: password123
     * @bodyParam role_id integer optional The role ID for the user. Example: 2
     *
     * @response 201 {
     *   "success": true,
     *   "message": "User created successfully.",
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "role_id": 2,
     *     "created_at": "2024-01-01T00:00:00.000000Z",
     *     "updated_at": "2024-01-01T00:00:00.000000Z"
     *   }
     * }
     *
     * @response 400 {
     *   "success": false,
     *   "message": "Validation error.",
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
     * @response 500 {
     *   "success": false,
     *   "message": "Something went wrong.",
     *   "data": null,
     *   "errors": "Detailed error message here."
     * }
     *
     * @param UserRequest $request
     * @return JsonResponse
     */

    /**
     * @OA\Post(
     *     path="api/v1/users",
     *     summary="Create a new user",
     *     description="Allows an authorized user to create a new user.",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"name", "email", "password"},
     *                 @OA\Property(property="name", type="string", example="John Doe", description="The name of the user"),
     *                 @OA\Property(property="email", type="string", example="john@example.com", description="The email of the user"),
     *                 @OA\Property(property="password", type="string", example="password123", description="The password of the user"),
     *                 @OA\Property(property="role_id", type="integer", example=2, description="The role ID for the user")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User created successfully."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="role_id", type="integer", example=2),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error."),
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
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Something went wrong."),
     *             @OA\Property(property="errors", type="string", example="Detailed error message here.")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */

    public function store(UserRequest $request, CreateUserAction $action): JsonResponse
    {
        Gate::authorize("create", User::class);

        if ($user = $action->handle($request->validated()))
            return response()->json([
                "success" => true,
                "message" => "User created successfully",
                "data" => $user->toArray()
            ], 201);
        else
            return response()->json([
                "success" => false
            ], 500);
    }

    /**
     * Retrieve a specific user by ID.
     *
     * This endpoint returns the details of a specific user. Authorization is required to view the user.
     *
     * @authenticated
     *
     * @urlParam user integer required The ID of the user. Example: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "John Doe",
     *   "email": "john@example.com",
     *   "role_id": 2,
     *   "created_at": "2024-01-01T00:00:00.000000Z",
     *   "updated_at": "2024-01-01T00:00:00.000000Z"
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to view this user."
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "User not found."
     * }
     *
     * @param User $user
     * @return UserResource
     */

    /**
     * @OA\Get(
     *     path="api/v1/users/{user}",
     *     summary="Retrieve a specific user by ID",
     *     description="Returns the details of a specific user. Authorization is required to view the user.",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         example=1
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User found successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="role_id", type="integer", example=2),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to view this user.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User not found.")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */

    public function show(User $user): UserResource
    {
        Gate::authorize("view", $user);

        return new UserResource($user);
    }

    /**
     * Update an existing user.
     *
     * This endpoint allows an authorized user to update the details of a specific user.
     *
     * @authenticated
     *
     * @urlParam user integer required The ID of the user to update. Example: 1
     *
     * @bodyParam name string optional The new name of the user. Example: Jane Doe
     * @bodyParam email string optional The new email of the user. Example: jane@example.com
     * @bodyParam password string optional The new password of the user. Example: newpassword123
     * @bodyParam role_id integer optional The new role ID for the user. Example: 3
     *
     * @response 204 {
     *   "success": true,
     *   "message": "User updated successfully.",
     *   "data": {
     *     "id": 1,
     *     "name": "Jane Doe",
     *     "email": "jane@example.com",
     *     "role_id": 3,
     *     "created_at": "2024-01-01T00:00:00.000000Z",
     *     "updated_at": "2024-01-02T00:00:00.000000Z"
     *   }
     * }
     *
     * @response 400 {
     *   "success": false,
     *   "message": "Validation error.",
     *   "errors": {
     *     "name": [
     *       "The name field is required if included."
     *     ],
     *     "email": [
     *       "The email must be a valid email address."
     *     ],
     *     "password": [
     *       "The password must be at least 8 characters."
     *     ]
     *   }
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to update this user."
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "User not found."
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Something went wrong.",
     *   "data": null,
     *   "errors": "Detailed error message here."
     * }
     *
     * @param UserRequest $request
     * @param User $user
     * @return JsonResponse
     */

    /**
     * @OA\Put(
     *     path="api/v1/users/{user}",
     *     summary="Update an existing user",
     *     description="Allows an authorized user to update the details of a specific user.",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         example=1
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="Jane Doe"),
     *                 @OA\Property(property="email", type="string", example="jane@example.com"),
     *                 @OA\Property(property="password", type="string", example="newpassword123"),
     *                 @OA\Property(property="role_id", type="integer", example=3)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="User updated successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User updated successfully."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Jane Doe"),
     *                 @OA\Property(property="email", type="string", example="jane@example.com"),
     *                 @OA\Property(property="role_id", type="integer", example=3),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-02T00:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"The name field is required if included."}
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"The email must be a valid email address."}
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"The password must be at least 8 characters."}
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to update this user.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Something went wrong."),
     *             @OA\Property(property="errors", type="string", example="Detailed error message here.")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */

    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $action): JsonResponse|Response|ResponseFactory
    {
        Gate::authorize("update", $user);

        if ($user = $action->handle($user, $request->validated()))
            return response()->json([
                "success" => true,
                "message" => "User updated successfully!",
                "data" => []
            ], 204);
        else
            return response()->json([
                "success" => false,
                "message" => "something wrong!",
                "data" => null
            ], 500);
    }

    /**
     * Delete a specific user.
     *
     * This endpoint allows an authorized user to delete a specific user.
     *
     * @authenticated
     *
     * @urlParam user integer required The ID of the user to delete. Example: 1
     *
     * @response 204 {
     *   "success": true,
     *   "message": "User deleted successfully.",
     *   "data": null
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to delete this user."
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "User not found."
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Something went wrong.",
     *   "data": null,
     *   "errors": "Detailed error message here."
     * }
     *
     * @param User $user
     * @return JsonResponse
     */

    /**
     * @OA\Delete(
     *     path="api/v1/users/{user}",
     *     summary="Delete a specific user",
     *     description="Allows an authorized user to delete a specific user.",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         example=1
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="User deleted successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User deleted successfully."),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to delete this user.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Something went wrong."),
     *             @OA\Property(property="errors", type="string", example="Detailed error message here.")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */

    public function destroy(User $user, DeleteUserAction $action): JsonResponse
    {
        Gate::authorize("delete", $user);

        if ($user = $action->handle($user))
            return response()->json([
                "success" => true,
                "message" => "User deleted successfully!"
            ], 204);
        else
            return response()->json([
                "success" => false,
                "message" => "Something wrong!"
            ], 500);
    }
}
