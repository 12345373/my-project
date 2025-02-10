<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\APIs\PostController;
use App\Http\Controllers\APIs\studentauthcontroller;
use App\Http\Controllers\GradeLevelController;
use App\Http\Controllers\APIs\studentcontroller;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix("student")->name("student.")->group(function () {
        Route::get('/index', [StudentController::class, 'index'])->name('index');
        Route::get('/show/{id}', [StudentController::class, 'show'])->name('show');
        Route::get('/edit', [StudentController::class, 'edit'])->name('edit');
        Route::get('/destory', [StudentController::class, 'destroy'])->name('destroy');
        Route::get('student/logout', [StudentController::class, 'logout'])->name('logout');
    });
    Route::prefix("posts")->name("posts.")->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::get('/{id}', [PostController::class, 'show'])->name('show');
        Route::put('/{id}', [PostController::class, 'update'])->name('update');
        Route::delete('/{id}', [PostController::class, 'destroy'])->name('destroy');
    });
    Route::prefix("comments")->name("comments.")->group(function () {
        Route::get('/', [CommentController::class, 'index'])->name('index');
        Route::post('/', [CommentController::class, 'store'])->name('store');
        Route::get('/{id}', [CommentController::class, 'show']);
        Route::put('/{id}', [CommentController::class, 'update']);
        Route::delete('/{id}', [CommentController::class, 'destroy']);
    });
    Route::prefix("points")->name("points.")->group(function () {
        Route::get('/', [PointController::class, 'index'])->name('index');
        Route::post('/', [PointController::class, 'store'])->name('store');
        Route::get('/{id}', [PointController::class, 'show']);
        Route::put('/{id}', [PointController::class, 'update']);
        Route::delete('/{id}', [PointController::class, 'destroy']);
    });
    Route::prefix("exams")->name("exams.")->group(function () {
        Route::get('/', [ExamController::class, 'index'])->name('index');
        Route::post('/', [ExamController::class, 'store'])->name('store');
        Route::get('/{id}', [ExamController::class, 'show']);
        Route::put('/{id}', [ExamController::class, 'update']);
        Route::delete('/{id}', [ExamController::class, 'destroy']);
    });
    Route::prefix("questions")->name("questions.")->group(function () {
        Route::get('', [QuestionController::class, 'index'])->name('index');
        Route::post('', [QuestionController::class, 'store'])->name('store');
        Route::get('/{id}', [QuestionController::class, 'show']);
        Route::put('/{id}', [QuestionController::class, 'update']);
        Route::delete('/{id}', [QuestionController::class, 'destroy']);
    });
    Route::prefix("friends")->name("friends.")->group(function () {
        Route::get('', [FriendController::class, 'index'])->name('index');
        Route::post('', [FriendController::class, 'store'])->name('store');
        Route::get('/{id}', [FriendController::class, 'show']);
        Route::put('/{id}', [FriendController::class, 'update']);
        Route::delete('/{id}', [FriendController::class, 'destroy']);
    });
    Route::prefix("subjects")->name("subjects.")->group(function () {
        Route::get('', [SubjectController::class, 'index'])->name('index');
        Route::post('', [SubjectController::class, 'store'])->name('store');
        Route::get('/{id}', [SubjectController::class, 'show']);
        Route::put('/{id}', [SubjectController::class, 'update']);
        Route::delete('/{id}', [SubjectController::class, 'destroy']);
    });
    Route::prefix("grade-levels")->name("grade-levels.")->group(function () {
        Route::get('', [GradeLevelController::class, 'index'])->name('index');
        Route::post('', [GradeLevelController::class, 'store'])->name('store');
        Route::get('/{id}', [GradeLevelController::class, 'show']);
        Route::put('/{id}', [GradeLevelController::class, 'update']);
        Route::delete('/{id}', [GradeLevelController::class, 'destroy']);
    });
});
Route::post('student/store', [StudentController::class, 'store'])->name('store');

Route::prefix('student')->group(function () {
    Route::get('/login', [studentauthcontroller::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [studentauthcontroller::class, 'logout']);
    });
});
