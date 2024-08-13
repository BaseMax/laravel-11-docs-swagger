<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\PostResource;
use App\Jobs\NewPostSendMailJob;

/**
 * @group Post Management
 *
 * APIs for managing blog posts
 */

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="Post management"
 * )
 */

class PostController extends Controller
{

    /**
     * List all posts.
     *
     * This endpoint returns a list of all posts.
     *
     * @authenticated
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Sample Post",
     *       "content": "This is a sample post content.",
     *       "cover": "covers/sample-cover.jpg",
     *       "created_at": "2024-01-01T00:00:00.000000Z",
     *       "updated_at": "2024-01-01T00:00:00.000000Z"
     *     }
     *   ]
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to view posts."
     * }
     *
     * @return ResourceCollection
     */

    /**
     * @OA\Get(
     *     path="/api/v1/posts",
     *     summary="List all posts",
     *     description="Retrieve a list of all posts with pagination.",
     *     tags={"Post"},
     *     @OA\Response(
     *         response=200,
     *         description="List of posts retrieved successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer", example=100),
     *                 @OA\Property(property="count", type="integer", example=10),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="total_pages", type="integer", example=10)
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string", example="/posts?page=1"),
     *                 @OA\Property(property="last", type="string", example="/posts?page=10"),
     *                 @OA\Property(property="next", type="string", example="/posts?page=2"),
     *                 @OA\Property(property="prev", type="string", example="/posts?page=0")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to view posts.")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */

    public function index(): ResourceCollection
    {
    }

    /**
     * Create a new post.
     *
     * This endpoint allows an authorized user to create a new post.
     *
     * @authenticated
     *
     * @bodyParam title string required The title of the post. Example: My New Post
     * @bodyParam content string required The content of the post. Example: This is the content of the post.
     * @bodyParam cover file optional The cover image for the post.
     *
     * @response 201 {
     *   "success": true,
     *   "message": "Post created successfully",
     *   "data": {
     *     "id": 1,
     *     "title": "My New Post",
     *     "content": "This is the content of the post.",
     *     "cover": "covers/my-new-post-cover.jpg",
     *     "created_at": "2024-01-01T00:00:00.000000Z",
     *     "updated_at": "2024-01-01T00:00:00.000000Z"
     *   }
     * }
     *
     * @response 400 {
     *   "success": false,
     *   "message": "Validation error.",
     *   "errors": {
     *     "title": [
     *       "The title field is required."
     *     ],
     *     "content": [
     *       "The content field is required."
     *     ]
     *   }
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to create posts."
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Something went wrong.",
     *   "data": null,
     *   "errors": "Detailed error message here."
     * }
     *
     * @param PostRequest $request
     * @return JsonResponse
     */

    /**
     * @OA\Post(
     *     path="/api/v1/posts",
     *     summary="Create a new post",
     *     description="Allows an authorized user to create a new post.",
     *     tags={"Post"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="My New Post"),
     *                 @OA\Property(property="content", type="string", example="This is the content of the post."),
     *                 @OA\Property(property="cover", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Post created successfully."),
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
     *                     property="title",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"The title field is required."}
     *                 ),
     *                 @OA\Property(
     *                     property="content",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"The content field is required."}
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to create posts.")
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

    public function store(PostRequest $request): JsonResponse
    {

    }

    /**
     * Retrieve a specific post.
     *
     * This endpoint returns the details of a specific post.
     *
     * @authenticated
     *
     * @urlParam post integer required The ID of the post to retrieve. Example: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "title": "Sample Post",
     *   "content": "This is a sample post content.",
     *   "cover": "covers/sample-cover.jpg",
     *   "created_at": "2024-01-01T00:00:00.000000Z",
     *   "updated_at": "2024-01-01T00:00:00.000000Z"
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to view this post."
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "Post not found."
     * }
     *
     * @param Post $post
     * @return PostResource
     */

    /**
     * @OA\Get(
     *     path="/api/v1/posts/{post}",
     *     summary="Retrieve a specific post",
     *     description="Returns the details of a specific post.",
     *     tags={"Post"},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         example=1
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post retrieved successfully.",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to view this post.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Post not found.")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */

    public function show(Post $post): PostResource
    {

    }

    /**
     * Update a specific post.
     *
     * This endpoint allows an authorized user to update the details of a specific post.
     *
     * @authenticated
     *
     * @urlParam post integer required The ID of the post to update. Example: 1
     *
     * @bodyParam title string optional The new title of the post. Example: Updated Title
     * @bodyParam content string optional The new content of the post. Example: Updated content.
     * @bodyParam cover file optional The new cover image for the post.
     *
     * @response 204 {
     *   "success": true,
     *   "message": "Post updated successfully.",
     *   "data": {
     *     "id": 1,
     *     "title": "Updated Title",
     *     "content": "Updated content.",
     *     "cover": "covers/updated-cover.jpg",
     *     "created_at": "2024-01-01T00:00:00.000000Z",
     *     "updated_at": "2024-01-02T00:00:00.000000Z"
     *   }
     * }
     *
     * @response 400 {
     *   "success": false,
     *   "message": "Validation error.",
     *   "errors": {
     *     "title": [
     *       "The title must be at least 3 characters."
     *     ],
     *     "content": [
     *       "The content must be at least 10 characters."
     *     ]
     *   }
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to update this post."
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "Post not found."
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Something went wrong.",
     *   "data": null,
     *   "errors": "Detailed error message here."
     * }
     *
     * @param PostRequest $request
     * @param Post $post
     * @return JsonResponse
     */

    /**
     * @OA\Put(
     *     path="/api/v1/posts/{post}",
     *     summary="Update a specific post",
     *     description="Allows an authorized user to update the details of a specific post.",
     *     tags={"Post"},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         example=1
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="Updated Title"),
     *                 @OA\Property(property="content", type="string", example="Updated content."),
     *                 @OA\Property(property="cover", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Post updated successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Post updated successfully."),
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
     *                     property="title",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"The title must be at least 3 characters."}
     *                 ),
     *                 @OA\Property(
     *                     property="content",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"The content must be at least 10 characters."}
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to update this post.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Post not found.")
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

    public function update(PostRequest $request, Post $post)
    {

    }

    /**
     * Delete a specific post.
     *
     * This endpoint allows an authorized user to delete a specific post.
     *
     * @authenticated
     *
     * @urlParam post integer required The ID of the post to delete. Example: 1
     *
     * @response 204 {
     *   "success": true,
     *   "message": "Post deleted successfully.",
     *   "data": null
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to delete this post."
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "Post not found."
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Something went wrong.",
     *   "data": null,
     *   "errors": "Detailed error message here."
     * }
     *
     * @param Post $post
     * @return JsonResponse
     */

    /**
     * @OA\Delete(
     *     path="/api/v1/posts/{post}",
     *     summary="Delete a specific post",
     *     description="Allows an authorized user to delete a specific post.",
     *     tags={"Post"},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         example=1
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Post deleted successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Post deleted successfully."),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to delete this post.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Post not found.")
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

    public function destroy(Post $post)
    {
        
    }
}
