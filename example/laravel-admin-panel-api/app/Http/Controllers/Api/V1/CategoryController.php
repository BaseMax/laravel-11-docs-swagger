<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Category\CategoryRequest;
use App\Http\Resources\CategoryResource;

/**
 * @group Category Management
 *
 * APIs for managing categories.
 */

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="Category management"
 * )
 */

class CategoryController extends Controller
{

    /**
     * List all categories.
     *
     * This endpoint returns a list of all categories.
     *
     * @authenticated
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Technology",
     *       "created_at": "2024-01-01T00:00:00.000000Z",
     *       "updated_at": "2024-01-01T00:00:00.000000Z"
     *     }
     *   ]
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to view categories."
     * }
     *
     * @return ResourceCollection
     */

    /**
     * @OA\Get(
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="List all categories",
     *     description="This endpoint returns a list of all categories.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object"
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to view categories.")
     *         )
     *     )
     * )
     */

    public function index(): ResourceCollection
    {

    }

    /**
     * Create a new category.
     *
     * This endpoint allows an authorized user to create a new category.
     *
     * @authenticated
     *
     * @bodyParam name string required The name of the category. Example: Technology
     *
     * @response 201 {
     *   "success": true,
     *   "message": "Category created successfully.",
     *   "data": {
     *     "id": 1,
     *     "name": "Technology",
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
     *     ]
     *   }
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to create categories."
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Something went wrong.",
     *   "data": null,
     *   "errors": "Detailed error message here."
     * }
     *
     * @param CategoryRequest $request
     * @return JsonResponse
     */

    /**
     * @OA\Post(
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="Create a new category",
     *     description="This endpoint allows an authorized user to create a new category.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Technology")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category created successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error."),
     *             @OA\Property(property="errors", type="object", additionalProperties={"type": "array", "items": {"type": "string"}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to create categories.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Something went wrong."),
     *             @OA\Property(property="errors", type="string", example="Detailed error message here.")
     *         )
     *     )
     * )
     */

    public function store(CategoryRequest $request): JsonResponse
    {

    }

    /**
     * Retrieve a specific category.
     *
     * This endpoint returns the details of a specific category.
     *
     * @authenticated
     *
     * @urlParam category integer required The ID of the category to retrieve. Example: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Technology",
     *   "created_at": "2024-01-01T00:00:00.000000Z",
     *   "updated_at": "2024-01-01T00:00:00.000000Z"
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to view this category."
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "Category not found."
     * }
     *
     * @param Category $category
     * @return CategoryResource
     */

    /**
     * @OA\Get(
     *     path="/categories/{category}",
     *     tags={"Categories"},
     *     summary="Retrieve a specific category",
     *     description="This endpoint returns the details of a specific category.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         example=1
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to view this category.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Category not found.")
     *         )
     *     )
     * )
     */

    public function show(Category $category): CategoryResource
    {

    }

    /**
     * Update a specific category.
     *
     * This endpoint allows an authorized user to update the details of a specific category.
     *
     * @authenticated
     *
     * @urlParam category integer required The ID of the category to update. Example: 1
     *
     * @bodyParam name string optional The new name of the category. Example: Updated Name
     *
     * @response 204 {
     *   "success": true,
     *   "message": "Category updated successfully.",
     *   "data": {
     *     "id": 1,
     *     "name": "Updated Name",
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
     *       "The name must be at least 3 characters."
     *     ]
     *   }
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to update this category."
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "Category not found."
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Something went wrong.",
     *   "data": null,
     *   "errors": "Detailed error message here."
     * }
     *
     * @param CategoryRequest $request
     * @param Category $category
     * @return JsonResponse
     */

    /**
     * @OA\Put(
     *     path="/categories/{category}",
     *     tags={"Categories"},
     *     summary="Update a specific category",
     *     description="This endpoint allows an authorized user to update the details of a specific category.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         example=1
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Updated Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Category updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category updated successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error."),
     *             @OA\Property(property="errors", type="object", additionalProperties={"type": "array", "items": {"type": "string"}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to update this category.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Category not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Something went wrong."),
     *             @OA\Property(property="errors", type="string", example="Detailed error message here.")
     *         )
     *     )
     * )
     */

    public function update(CategoryRequest $request, Category $category): JsonResponse
    {

    }

    /**
     * Delete a specific category.
     *
     * This endpoint allows an authorized user to delete a specific category.
     *
     * @authenticated
     *
     * @urlParam category integer required The ID of the category to delete. Example: 1
     *
     * @response 204 {
     *   "success": true,
     *   "message": "Category deleted successfully.",
     *   "data": null
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to delete this category."
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "Category not found."
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Something went wrong.",
     *   "data": null,
     *   "errors": "Detailed error message here."
     * }
     *
     * @param Category $category
     * @return JsonResponse
     */

    /**
     * @OA\Delete(
     *     path="/categories/{category}",
     *     tags={"Categories"},
     *     summary="Delete a specific category",
     *     description="This endpoint allows an authorized user to delete a specific category.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         example=1
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Category deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category deleted successfully."),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to delete this category.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Category not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Something went wrong."),
     *             @OA\Property(property="errors", type="string", example="Detailed error message here.")
     *         )
     *     )
     * )
     */

    public function destroy(Category $category): JsonResponse
    {

    }
}
