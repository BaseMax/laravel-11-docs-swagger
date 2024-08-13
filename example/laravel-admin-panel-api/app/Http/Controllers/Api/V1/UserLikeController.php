<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Post;

 /**
 * @OA\Tag(
 *     name="User likes",
 *     description="Post management"
 * )
 */

class UserLikeController extends Controller
{

    /**
     * Like a specific post by the authenticated user.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     *
     * @group User Likes
     *
     * @response 201 {
     *   "success": true,
     *   "message": "User post liked"
     * }
     * @response 500 {
     *   "success": false,
     *   "message": "Internal server error"
     * }
     */

    /**
     * @OA\Post(
     *     path="/api/v1/users/likes/{postId}",
     *     summary="Like a post",
     *     description="Allows the authenticated user to like a specific post.",
     *     operationId="likePost",
     *     tags={"User Likes"},
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         required=true,
     *         description="ID of the post to like",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post liked successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User post liked")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error message")
     *         )
     *     )
     * )
     */
    public function like(Post $post): JsonResponse
    {
    }

    /**
     * Dislike a specific post by the authenticated user.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     *
     * @group User Likes
     *
     * @response 204 {
     *   "success": true,
     *   "message": "User post disliked"
     * }
     * @response 500 {
     *   "success": false,
     *   "message": "Internal server error"
     * }
     */

    /**
     * @OA\Delete(
     *     path="/api/v1/users/dislike/{postId}",
     *     summary="Dislike a post",
     *     description="Allows the authenticated user to remove their like from a specific post.",
     *     operationId="dislikePost",
     *     tags={"User Likes"},
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         required=true,
     *         description="ID of the post to dislike",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Post disliked successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User post disliked")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error message")
     *         )
     *     )
     * )
     */

    public function dislike(Post $post): JsonResponse
    {
    }

    /**
     * Get the count of posts liked by the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @group User Likes
     *
     * @response 200 {
     *   "success": true,
     *   "message": "User likes count",
     *   "data": 5
     * }
     * @response 500 {
     *   "success": false,
     *   "message": "Internal server error"
     * }
     */

    /**
     * @OA\Get(
     *     path="/api/v1/users/likes/count",
     *     summary="Get the count of liked posts by the user",
     *     description="Returns the total number of posts liked by the authenticated user.",
     *     operationId="getUserLikeCount",
     *     tags={"User Likes"},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved like count",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User likes count"),
     *             @OA\Property(property="data", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error message")
     *         )
     *     )
     * )
     */

    public function count(): JsonResponse
    {
    }
}
