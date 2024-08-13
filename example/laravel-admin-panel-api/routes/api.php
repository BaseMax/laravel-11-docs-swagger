<?php

use App\Http\Controllers\Api\V1\PostLikeController;
use App\Http\Controllers\Api\V1\UserLikeController;
use App\Http\Controllers\Api\V1\VisitPostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\UserController;

Route:: as("api.")->group(function () {
    Route::group([], function () {
        Route::post("/register", [AuthController::class, "register"])->name("register");
        Route::post("/login", [AuthController::class, "login"])->name("login");
    });

    Route::group(["middleware" => ["auth:sanctum"]], function () {
        Route::post("/logout", [AuthController::class, "logout"])->name("logout");

        Route::prefix("/v1")->group(function () {
            Route::apiResource("/posts", PostController::class);
            Route::apiResource("/categories", CategoryController::class);
            Route::apiResource("/comments", CommentController::class);
            Route::apiResource("/users", UserController::class);

            Route::post("users/likes/{post}", [UserLikeController::class, "like"])->name("users.likes.like");
            Route::post("users/dislikes/{post}", [UserLikeController::class, "dislike"])->name("users.likes.dislike");
            Route::get("users/likes/count", [UserLikeController::class, "count"])->name("users.likes.count");

            Route::post("posts/visits/{post}", [VisitPostController::class, "store_visit"])->name("posts.visits.store");
            Route::get("posts/visits/{post}", [VisitPostController::class, "post_visits_count"])->name("posts.visits.count");

            Route::post("posts/likes/{post}", [PostLikeController::class, "store_like"])->name("posts.likes.store");
            Route::post("posts/dislike/{post}", [PostLikeController::class, "destroy_like"])->name("posts.dislike.store");
            Route::get("posts/likes/{post}", [PostLikeController::class, "like_count"])->name("posts.likes.count");
        });
    });
});
