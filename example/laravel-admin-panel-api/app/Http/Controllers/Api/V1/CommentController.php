<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Http\Requests\Comment\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Actions\Comment\GetCommentsAction;
use App\Actions\Comment\CreateCommentAction;
use App\Actions\Comment\UpdateCommentAction;
use App\Actions\Comment\DeleteCommentAction;

/**
 * @group Comment Management
 *
 * APIs for managing comments
 */

/**
 * @OA\Tag(
 *     name="Comments",
 *     description="Comment management"
 * )
 */

class CommentController extends Controller
{
    /**
     * List all comments.
     *
     * This endpoint returns a list of all comments.
     *
     * @authenticated
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "post_id": 1,
     *       "author": "John Doe",
     *       "content": "This is a comment.",
     *       "created_at": "2024-01-01T00:00:00.000000Z",
     *       "updated_at": "2024-01-01T00:00:00.000000Z"
     *     }
     *   ]
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to view comments."
     * }
     *
     * @return ResourceCollection
     */

    /**
     * @OA\Get(
     *     path="/comments",
     *     tags={"Comments"},
     *     summary="List all comments",
     *     description="This endpoint returns a list of all comments.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to view comments.")
     *         )
     *     )
     * )
     */

    public function index(GetCommentsAction $action): ResourceCollection
    {
        Gate::authorize("viewAny", Comment::class);

        return CommentResource::collection($action->handle());
    }

    /**
     * Create a new comment.
     *
     * This endpoint allows an authorized user to create a new comment.
     *
     * @authenticated
     *
     * @bodyParam post_id integer required The ID of the post to which the comment belongs. Example: 1
     * @bodyParam author string required The author of the comment. Example: John Doe
     * @bodyParam content string required The content of the comment. Example: This is a comment.
     *
     * @response 201 {
     *   "success": true,
     *   "message": "Comment created successfully.",
     *   "data": {
     *     "id": 1,
     *     "post_id": 1,
     *     "author": "John Doe",
     *     "content": "This is a comment.",
     *     "created_at": "2024-01-01T00:00:00.000000Z",
     *     "updated_at": "2024-01-01T00:00:00.000000Z"
     *   }
     * }
     *
     * @response 400 {
     *   "success": false,
     *   "message": "Validation error.",
     *   "errors": {
     *     "post_id": [
     *       "The post_id field is required."
     *     ],
     *     "author": [
     *       "The author field is required."
     *     ],
     *     "content": [
     *       "The content field is required."
     *     ]
     *   }
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to create comments."
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Something went wrong.",
     *   "data": null,
     *   "errors": "Detailed error message here."
     * }
     *
     * @param CommentRequest $request
     * @return JsonResponse
     */

    /**
     * @OA\Post(
     *     path="/comments",
     *     tags={"Comments"},
     *     summary="Create a new comment",
     *     description="This endpoint allows an authorized user to create a new comment.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="post_id", type="integer", example=1),
     *             @OA\Property(property="author", type="string", example="John Doe"),
     *             @OA\Property(property="content", type="string", example="This is a comment.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Comment created successfully."),
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
     *             @OA\Property(property="message", type="string", example="You are not authorized to create comments.")
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

    public function store(CommentRequest $request, CreateCommentAction $action): JsonResponse
    {
        // Gate::authorize("create", Comment::class);

        $data = $request->all();

        if ($comment = $action->handle($data))
            return response()->json([
                "success" => true,
                "message" => "Comment created successfully.",
                "data" => new CommentResource($comment)
            ], 201);
        else
            return response()->json([
                "success" => false,
                "message" => "Something went wrong.",
                "data" => null,
                "errors" => null
            ], 500);

    }

    /**
     * Retrieve a specific comment.
     *
     * This endpoint returns the details of a specific comment.
     *
     * @authenticated
     *
     * @urlParam comment integer required The ID of the comment to retrieve. Example: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "post_id": 1,
     *   "author": "John Doe",
     *   "content": "This is a comment.",
     *   "created_at": "2024-01-01T00:00:00.000000Z",
     *   "updated_at": "2024-01-01T00:00:00.000000Z"
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to view this comment."
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "Comment not found."
     * }
     *
     * @param Comment $comment
     * @return CommentResource
     */

    /**
     * @OA\Get(
     *     path="/comments/{comment}",
     *     tags={"Comments"},
     *     summary="Retrieve a specific comment",
     *     description="This endpoint returns the details of a specific comment.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="comment",
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
     *             @OA\Property(property="message", type="string", example="You are not authorized to view this comment.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Comment not found.")
     *         )
     *     )
     * )
     */

    public function show(Comment $comment)
    {
        Gate::authorize("view", $comment);

        return new CommentResource($comment);
    }

    /**
     * Update a specific comment.
     *
     * This endpoint allows an authorized user to update the details of a specific comment.
     *
     * @authenticated
     *
     * @urlParam comment integer required The ID of the comment to update. Example: 1
     *
     * @bodyParam author string optional The new author of the comment. Example: Jane Doe
     * @bodyParam content string optional The new content of the comment. Example: Updated comment content.
     *
     * @response 204 {
     *   "success": true,
     *   "message": "Comment updated successfully.",
     *   "data": {
     *     "id": 1,
     *     "post_id": 1,
     *     "author": "Jane Doe",
     *     "content": "Updated comment content.",
     *     "created_at": "2024-01-01T00:00:00.000000Z",
     *     "updated_at": "2024-01-02T00:00:00.000000Z"
     *   }
     * }
     *
     * @response 400 {
     *   "success": false,
     *   "message": "Validation error.",
     *   "errors": {
     *     "author": [
     *       "The author must be at least 3 characters."
     *     ],
     *     "content": [
     *       "The content must be at least 10 characters."
     *     ]
     *   }
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to update this comment."
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "Comment not found."
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Something went wrong.",
     *   "data": null,
     *   "errors": "Detailed error message here."
     * }
     *
     * @param CommentRequest $request
     * @param Comment $comment
     * @return JsonResponse
     */

    /**
     * @OA\Put(
     *     path="/comments/{comment}",
     *     tags={"Comments"},
     *     summary="Update a specific comment",
     *     description="This endpoint allows an authorized user to update the details of a specific comment.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="comment",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         example=1
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="author", type="string", example="Jane Doe"),
     *             @OA\Property(property="content", type="string", example="Updated comment content.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Comment updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Comment updated successfully."),
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
     *             @OA\Property(property="message", type="string", example="You are not authorized to update this comment.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Comment not found.")
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

    public function update(CommentRequest $request, Comment $comment, UpdateCommentAction $action): JsonResponse
    {
        Gate::authorize("update", $comment);

        if ($comment = $action->handle($comment, $request->all()))
            return response()->json([
                "success" => true,
                "message" => "Comment updated successfully.",
                "data" => new CommentResource($comment)
            ], 204);
        else
            return response()->json([
                "success" => false,
                "message" => "Something went wrong.",
                "data" => null,
                "errors" => null
            ], 500);
    }

    /**
     * Delete a specific comment.
     *
     * This endpoint allows an authorized user to delete a specific comment.
     *
     * @authenticated
     *
     * @urlParam comment integer required The ID of the comment to delete. Example: 1
     *
     * @response 204 {
     *   "success": true,
     *   "message": "Comment deleted successfully.",
     *   "data": null
     * }
     *
     * @response 403 {
     *   "success": false,
     *   "message": "You are not authorized to delete this comment."
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "Comment not found."
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Something went wrong.",
     *   "data": null,
     *   "errors": "Detailed error message here."
     * }
     *
     * @param Comment $comment
     * @return JsonResponse
     */

    /**
     * @OA\Delete(
     *     path="/comments/{comment}",
     *     tags={"Comments"},
     *     summary="Delete a specific comment",
     *     description="This endpoint allows an authorized user to delete a specific comment.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="comment",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         example=1
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Comment deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Comment deleted successfully."),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to delete this comment.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Comment not found.")
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

    public function destroy(Comment $comment, DeleteCommentAction $action): JsonResponse
    {
        Gate::authorize("delete", $comment);

        if ($action->handle($comment))
            return response()->json([
                "success" => true,
                "message" => "Comment deleted successfully.",
            ], 204);
        else
            return response()->json([
                "success" => false,
                "message" => "Something went wrong.",
                "data" => null,
                "errors" => null
            ], 500);
    }
}
