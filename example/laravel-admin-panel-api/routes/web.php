<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect("/", "admin");
Route::redirect("/home", "admin");
Route::group(
    ["prefix" => "admin", "middleware" => ["auth", "can:isAdmin"], 'as' => 'admin.'],
    function () {
        Route::get("/", DashboardController::class)->name("dashboard");
        Route::resource("/users", UserController::class);
        Route::resource("/posts", PostController::class);
        Route::resource("/categories", CategoryController::class);
    }
);
