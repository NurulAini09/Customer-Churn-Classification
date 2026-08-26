<?php

use App\Http\Controllers\PredictionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PredictionController::class, 'landingPage'])->name('landing');
Route::get('/login', [PredictionController::class, 'loginPage'])->name('login');
Route::post('/login', [PredictionController::class, 'login'])->middleware('throttle:5,1')->name('login.attempt');
Route::get('/daftar', [PredictionController::class, 'registerPage'])->name('register');
Route::post('/daftar', [PredictionController::class, 'register'])->middleware('throttle:10,1')->name('register.store');
Route::get('/reset-password', [PredictionController::class, 'resetPasswordPage'])->name('password.request');
Route::post('/reset-password', [PredictionController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.update');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [PredictionController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [PredictionController::class, 'index'])->name('prediction.index');
    Route::get('/klasifikasi', [PredictionController::class, 'predictionPage'])->name('prediction.page');
    Route::get('/riwayat', [PredictionController::class, 'historyPage'])->name('prediction.history.page');
    Route::get('/informasi-model', [PredictionController::class, 'modelPage'])->name('prediction.model.page');
    Route::get('/tentang-sistem', [PredictionController::class, 'aboutPage'])->name('prediction.about.page');
    Route::get('/profile', [PredictionController::class, 'profilePage'])->name('profile.page');
    Route::post('/profile/photo', [PredictionController::class, 'updateProfilePhoto'])->middleware('throttle:10,1')->name('profile.photo.update');
    Route::post('/profile/photo/delete', [PredictionController::class, 'deleteProfilePhoto'])->name('profile.photo.delete');
    Route::post('/profile/reset-password', [PredictionController::class, 'updateProfilePassword'])->middleware('throttle:5,1')->name('profile.password.update');
    Route::get('/users', [PredictionController::class, 'usersPage'])->name('users.page');
    Route::post('/predict', [PredictionController::class, 'predict'])->middleware('throttle:60,1')->name('prediction.predict');
    Route::post('/history/clear', [PredictionController::class, 'clearHistory'])->name('prediction.history.clear');
    Route::post('/history/{history}/delete', [PredictionController::class, 'deleteHistory'])->name('prediction.history.delete');
    Route::get('/api/history', [PredictionController::class, 'historyJson'])->name('prediction.history.json');
    Route::get('/reset', [PredictionController::class, 'reset'])->name('prediction.reset');
});
