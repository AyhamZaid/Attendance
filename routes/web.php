<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Quick test route to auto-login first user (for testing only - remove in production)
Route::get('/test-login', function () {
    $user = App\User::first();
    if ($user) {
        Auth::guard('lms')->login($user);
        return redirect('/')->with('success', 'Logged in as: ' . $user->email);
    }
    return redirect('/login')->with('error', 'No users found. Please create a user first.');
})->name('test.login');

// Trainer routes
Route::middleware(['auth:lms'])->group(function () {
    // List all sessions (for easy access)
    Route::get('/trainer/sessions', function () {
        $sessions = App\Models\TrainingSession::orderBy('starts_at', 'desc')->get();
        if ($sessions->isEmpty()) {
            return redirect('/')->with('error', 'No training sessions found. Please seed the database: php artisan db:seed --class=TrainingSessionSeeder');
        }
        // Redirect to first session
        return redirect()->route('trainer.sessions.show', $sessions->first()->id);
    })->name('trainer.sessions.index');
    
    Route::get('/trainer/sessions/{session}', [App\Http\Controllers\TrainerSessionController::class, 'show'])
        ->name('trainer.sessions.show');
    Route::post('/trainer/sessions/{session}/challenge', [App\Http\Controllers\TrainerSessionController::class, 'challenge'])
        ->name('trainer.sessions.challenge');
});

// Attendance routes
Route::middleware(['auth:lms', 'risk.context'])->group(function () {
    Route::post('/attendance/check-in', [App\Http\Controllers\AttendanceController::class, 'checkIn'])
        ->name('attendance.check-in');
    Route::post('/sessions/{session}/challenge', [App\Http\Controllers\AttendanceController::class, 'submitChallenge'])
        ->middleware('session.active')
        ->name('attendance.challenge');
    Route::post('/sessions/{session}/beacon', [App\Http\Controllers\AttendanceController::class, 'beacon'])
        ->middleware('session.active')
        ->name('attendance.beacon');
});

// Trainee check-in page
Route::get('/trainee/checkin', function () {
    return view('trainee.checkin');
})->name('trainee.checkin');
