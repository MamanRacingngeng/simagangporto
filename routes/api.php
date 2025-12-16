<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LamaranController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public auth endpoints
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected API routes (require token)
Route::middleware('auth:sanctum')->group(function () {
	Route::post('/logout', [AuthController::class, 'logout']);

	// lamaran / laporan endpoints
	Route::get('/lamaran/status', [LamaranController::class, 'status']);
	Route::post('/laporan/upload', [LamaranController::class, 'uploadLaporan']);

	// sample protected endpoints for frontend pages
	Route::get('/lowongan', function () {
		return response()->json([
			['nama' => 'Magang UI/UX Designer', 'deskripsi' => 'Membuat desain interface BBKB.'],
			['nama' => 'Magang Web Developer', 'deskripsi' => 'Membangun sistem magang digital.']
		]);
	});

	Route::get('/lamaran', function () {
		return response()->json([
			['posisi' => 'Magang Web Developer', 'status' => 'Menunggu Proses']
		]);
	});
});
