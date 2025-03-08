<?php

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\APIs\PostController;
use App\Http\Controllers\APIs\CommentController;
use App\Http\Controllers\APIs\studentcontroller;
use App\Http\Controllers\APIs\studentauthcontroller;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// الـ Verify Token Endpoint

Route::middleware('auth:sanctum')->post('/verify-token', function (Request $request) {
    return response()->json([
        'status' => 200,
        'message' => 'Token is valid',
        'user' => $request->user()
    ]);
});

// Routes للتسجيل والتسجيل الدخول والخروج
Route::prefix('student')->group(function () {
    Route::post('/login', [studentauthcontroller::class, 'login']);
    Route::post('/register', [studentauthcontroller::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [studentauthcontroller::class, 'logout']);
    });
});

// Routes محمية بـ auth:sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Routes الطالب
    Route::prefix("student")->name("student.")->group(function () {
        Route::get('/index', [StudentController::class, 'index'])->name('index');
        Route::get('/show/{id}', [StudentController::class, 'show'])->name('show');
        Route::get('/edit', [StudentController::class, 'edit'])->name('edit');
        Route::get('/destory/{id}', [StudentController::class, 'destroy'])->name('destroy');
        Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
        Route::get('student/logout', [StudentController::class, 'logout'])->name('logout');
        Route::get('/search/{id}', [StudentController::class, 'search']);
    });


    // Routes المنشورات
    Route::prefix("posts")->name("posts.")->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::get('/{id}', [PostController::class, 'show'])->name('show');
        Route::put('/{id}', [PostController::class, 'update'])->name('update');
        Route::delete('/{id}', [PostController::class, 'destroy'])->name('destroy');
    });

    // Routes التعليقات
    Route::prefix("comments")->name("comments.")->group(function () {
        Route::get('/', [CommentController::class, 'index'])->name('index');
        Route::post('/', [CommentController::class, 'store'])->name('store');
        Route::get('/{id}', [CommentController::class, 'show']);
        Route::put('/{id}', [CommentController::class, 'update']);
        Route::delete('/{id}', [CommentController::class, 'destroy']);
    });





    // Routes الأصدقاء
    Route::prefix("friends")->name("friends.")->group(function () {
        Route::get('', [FriendController::class, 'index'])->name('index');
        Route::post('', [FriendController::class, 'store'])->name('store');
        Route::get('/{id}', [FriendController::class, 'show']);
        Route::put('/{id}', [FriendController::class, 'update']);
        Route::delete('/{id}', [FriendController::class, 'destroy']);
    });


});

// Routes خارج الـ middleware
Route::post('student/store', [StudentController::class, 'store'])->name('store');
Route::get('/students/ranks', [StudentController::class, 'getallranks']);
Route::get('students/posts/comments', [Post::class, 'getallposts']);

Route::middleware('auth:sanctum')->group(function () {
    // ... (الـ routes التانية)

    // Routes الشات الجديدة
    Route::get('/messages', [ChatController::class, 'fetchMessages']); // جلب الرسايل
    Route::post('/messages', [ChatController::class, 'sendMessage']);  // إرسال رسالة
    Route::post('/friendship/request', [ChatController::class, 'sendFriendshipRequest']); // طلب صداقة
});
