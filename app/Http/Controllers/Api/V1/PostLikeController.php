<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

 /**
 * @OA\Tag(
 *     name="Post likes",
 *     description="Post management"
 * )
 */

class PostLikeController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/v1/posts/likes/{postId}",
     *     summary="Like a post",
     *     description="Allows a user to like a post. If the user is already authenticated and has not liked the post, the like is recorded.",
     *     operationId="storeLike",
     *     tags={"Post likes"},
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
     *             @OA\Property(property="message", type="string", example="Post liked!")
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
    public function store_like()
    {
        // ...
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/posts/dislike/{postId}",
     *     summary="Dislike a post",
     *     description="Allows a user to remove their like from a post. If the user is authenticated and has liked the post, the like is removed.",
     *     operationId="destroyLike",
     *     tags={"Post likes"},
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
     *             @OA\Property(property="message", type="string", example="Post Disliked")
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

    public function destroy_like()
    {
        // ...
    }

    /**
     * @OA\Get(
     *     path="/api/v1/posts/likes/{postId}",
     *     summary="Get like count of a post",
     *     description="Retrieves the total number of likes for a specific post.",
     *     operationId="likeCount",
     *     tags={"Post likes"},
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         required=true,
     *         description="ID of the post to get like count for",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved like count",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Post liked count"),
     *             @OA\Property(property="data", type="integer", example=123)
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

    public function like_count()
    {
        // ...
    }
}
