<?php

use App\Http\Controllers\PredictionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PredictionController::class, 'index'])->name('prediction.index');
Route::get('/prediksi', [PredictionController::class, 'predictionPage'])->name('prediction.page');
Route::get('/riwayat', [PredictionController::class, 'historyPage'])->name('prediction.history.page');
Route::get('/informasi-model', [PredictionController::class, 'modelPage'])->name('prediction.model.page');
Route::get('/tentang-sistem', [PredictionController::class, 'aboutPage'])->name('prediction.about.page');
Route::post('/predict', [PredictionController::class, 'predict'])->name('prediction.predict');
Route::post('/history/clear', [PredictionController::class, 'clearHistory'])->name('prediction.history.clear');
Route::get('/reset', [PredictionController::class, 'reset'])->name('prediction.reset');
