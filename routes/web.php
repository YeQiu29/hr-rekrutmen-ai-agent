<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApplicantProfileController;
use App\Http\Controllers\AiIntegrationController; // Import AiIntegrationController
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\CvAgentController;

// Redirect root to applicant login
Route::get('/', function () {
    return redirect()->route('login.pelamar');
});

// Guest routes (login & register)
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/login/pelamar', [LoginController::class, 'showLoginForm'])->name('login.pelamar');
    Route::get('/login/hrd', [LoginController::class, 'showLoginForm'])->name('login.hrd');
    Route::post('/login', [LoginController::class, 'login']);

    // Registration Routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});


// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard/hrd', [DashboardController::class, 'hrdDashboard'])->name('dashboard.hrd');
    Route::get('/dashboard/pelamar', [DashboardController::class, 'pelamarDashboard'])->name('dashboard.pelamar');

    // Applicant Profile Routes
    Route::get('/pelamar/profile', [ApplicantProfileController::class, 'show'])->name('pelamar.profile');
    Route::post('/pelamar/profile', [ApplicantProfileController::class, 'storeOrUpdate'])->name('pelamar.profile.store');

    // AI Integration Routes
    Route::post('/ai/process-cv', [AiIntegrationController::class, 'processCv'])->name('ai.process_cv');
    Route::get('/ai/batch/{batchId}', [AiIntegrationController::class, 'getBatchResults'])->name('ai.batch.status');

    // Job Vacancy (Loker) Routes - HRD Only
    Route::resource('loker', JobVacancyController::class);

    // CV Agent AI Route - HRD Only
    Route::get('/cv-agent', [CvAgentController::class, 'index'])->name('cv_agent.index');

    // Temporary route to fix filename
    Route::get('/fix-vacancy-filename', [JobVacancyController::class, 'fixVacancyFilename']);
});
