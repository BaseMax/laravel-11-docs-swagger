<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="API Docs",
 *     description="Admin API Documentation"
 *   )
 * @OA\PathItem(
 *     path="/example",
 *     @OA\Get(
 *         summary="Example endpoint",
 *         @OA\Response(
 *             response="200",
 *             description="Successful response"
 *         )
 *     )
 * )
 */

abstract class Controller
{
}