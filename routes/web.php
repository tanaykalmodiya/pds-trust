<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FpsStockController;
use App\Http\Controllers\RationDistributionController;
use App\Http\Controllers\OfficialDashboardController;
use App\Http\Controllers\SmsSimulatorController;
use App\Http\Controllers\GrievanceController;
use App\Http\Controllers\OfficialGrievanceController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'beneficiary' => redirect()->route('beneficiary.dashboard'),
            'fps_owner' => redirect()->route('fps.dashboard'),
            'government_official' => redirect()->route('official.dashboard'),
            default => abort(403),
        };
    })->name('dashboard');

    Route::get('/beneficiary/dashboard', function () {
        return view('dashboards.beneficiary');
    })->middleware('role:beneficiary')
        ->name('beneficiary.dashboard');

    Route::get('/fps/dashboard', function () {
        return view('dashboards.fps-owner');
    })->middleware('role:fps_owner')
        ->name('fps.dashboard');

    Route::get('/official/dashboard', function () {
        return view('dashboards.government-official');
    })->middleware('role:government_official')
        ->name('official.dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

Route::middleware(['auth', 'role:fps_owner'])->group(function () {
    Route::get('/fps/stock', [FpsStockController::class, 'index'])
        ->name('fps.stock.index');

    Route::post('/fps/stock/{allocation}/receive', [FpsStockController::class, 'receive'])
        ->name('fps.stock.receive');
});

Route::middleware(['auth', 'role:fps_owner'])->group(function () {
    Route::get('/fps/distributions', [RationDistributionController::class, 'index'])
        ->name('fps.distributions.index');

    Route::post('/fps/distributions', [RationDistributionController::class, 'store'])
        ->name('fps.distributions.store');
});

Route::middleware(['auth', 'role:beneficiary'])->group(function () {
    Route::get('/beneficiary/verify', [RationDistributionController::class, 'beneficiaryIndex'])
        ->name('beneficiary.verify.index');

    Route::post('/beneficiary/verify/{distribution}', [RationDistributionController::class, 'verify'])
        ->name('beneficiary.verify.store');
});

Route::middleware(['auth', 'role:government_official'])->group(function () {
    Route::get('/official/discrepancies', [OfficialDashboardController::class, 'index'])
        ->name('official.discrepancies');
});

Route::middleware(['auth', 'role:beneficiary'])->group(function () {
    Route::get('/beneficiary/sms', [SmsSimulatorController::class, 'index'])
        ->name('beneficiary.sms.index');

    Route::post('/beneficiary/sms/{sms}/reply', [SmsSimulatorController::class, 'reply'])
        ->name('beneficiary.sms.reply');
});

Route::middleware(['auth', 'role:beneficiary'])->group(function () {
    Route::get('/beneficiary/grievances', [GrievanceController::class, 'index'])
        ->name('beneficiary.grievances.index');

    Route::post('/beneficiary/grievances', [GrievanceController::class, 'store'])
        ->name('beneficiary.grievances.store');
});

Route::middleware(['auth', 'role:government_official'])->group(function () {
    Route::get('/official/grievances', [OfficialGrievanceController::class, 'index'])
        ->name('official.grievances.index');

    Route::patch('/official/grievances/{grievance}', [OfficialGrievanceController::class, 'update'])
        ->name('official.grievances.update');
});