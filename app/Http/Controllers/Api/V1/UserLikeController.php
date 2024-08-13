<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

 /**
 * @OA\Tag(
 *     name="User likes",
 *     description="Post management"
 * )
 */

class UserLikeController extends Controller
{

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
    public function like()
    {
        // ...
    }

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

    public function dislike()
    {
        // ...
    }

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

    public function count()
    {
        // ...
    }
}
