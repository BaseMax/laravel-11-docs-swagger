<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Post;

 /**
 * @OA\Tag(
 *     name="Post visits",
 *     description="Post management"
 * )
 */

class VisitPostController extends Controller
{
    /**
     * Record a visit for a specific post.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     *
     * @group Visits
     * @bodyParam ip string The IP address of the user. Example: 192.168.1.1
     * @bodyParam user_agent string The user agent of the user's browser. Example: Mozilla/5.0
     *
     * @response 201 {
     *   "success": true,
     *   "message": "Post visited successfully!"
     * }
     * @response 400 {
     *   "success": false,
     *   "message": "Invalid input or parameters"
     * }
     */

    /**
     * @OA\Post(
     *     path="api/v1/posts/visits/{postId}",
     *     summary="Store a visit for a post",
     *     description="Records a visit for a specific post, ensuring the visit is not duplicated for the same IP on the same day.",
     *     operationId="storeVisit",
     *     tags={"Post visits"},
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         required=true,
     *         description="ID of the post to record the visit for",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="ip", type="string", example="192.168.1.1"),
     *                 @OA\Property(property="user_agent", type="string", example="Mozilla/5.0")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Visit successfully recorded",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Post visited successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid input or parameters")
     *         )
     *     )
     * )
     */

    public function store_visit(Request $request, Post $post): JsonResponse
    {
        $ip = \Hash::make($request->ip());

        $user_agent = $request->userAgent();

        if ($post->visits()->where("visited_at", today())->where("ip_address", $ip)->count() < 1)
            $post->visits()->create([
                "ip_address" => $ip,
                "user_agent" => $user_agent
            ]);

        return response()->json([
            "success" => true,
            "message" => "Post visited successfully!"
        ], 201);
    }

    /**
     * Get the number of visits for a specific post.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     *
     * @group Visits
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Post visits count",
     *   "data": 123
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Post not found"
     * }
     */

    /**
     * @OA\Get(
     *     path="/api/v1/posts/visits/{postId}",
     *     summary="Get the visit count for a post",
     *     description="Returns the total number of visits recorded for a specific post.",
     *     operationId="getPostVisitsCount",
     *     tags={"Post visits"},
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         required=true,
     *         description="ID of the post to get visit count for",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with visit count",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Post visits count"),
     *             @OA\Property(property="data", type="integer", example=42)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Post not found")
     *         )
     *     )
     * )
     */

    public function post_visits_count(Post $post): JsonResponse
    {
        $visits_count = $post->visits()->count();

        return response()->json([
            "success" => true,
            "message" => "Post vists count",
            "data" => $visits_count
        ], 200);
    }
}
