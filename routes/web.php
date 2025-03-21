<?php
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StallController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Stall; // Import the Stall model
use Illuminate\Http\Request;

Auth::routes(['verify' => true]);  // This enables email verification routes

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::post('/create-stall', [StallController::class, 'store']);

Route::get('/customer', fn () => Inertia::render('Customer'))
    ->middleware(['auth', 'verified'])
    ->name('customer');

Route::get('/entrepreneur', fn () => Inertia::render('Entrepreneur'))
    ->middleware(['auth', 'verified'])
    ->name('entrepreneur');

Route::get('/club', fn () => Inertia::render('Club'))
    ->middleware(['auth', 'verified'])
    ->name('club');

Route::get('/dashboard', fn () => Inertia::render('Dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('/stalls', [StallController::class, 'store']);

    // In routes/web.php or api.php
Route::get('/stalls', [StallController::class, 'index']);  // Adjust according to your controller

    
