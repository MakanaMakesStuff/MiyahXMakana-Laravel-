<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\AppController;
use Illuminate\Http\Request;

Route::post('/notifications/subscribe', function (Request $request) {
    $request->user()->updatePushSubscription(
        $request->endpoint,
        $request->publicKey,
        $request->authToken,
        $request->contentEncoding
    );

    return response()->json(['success' => true]);
})->middleware(['auth']);

Route::get("/", function () {
    return inertia("home");
})->name("home");

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
        
    // The Chat App Routes
    Route::get('/chat', [AppController::class, 'index'])->name('chat.index');
    Route::post('/chat/messages', [AppController::class, 'store'])->name('chat.store');
});

require __DIR__.'/settings.php';
