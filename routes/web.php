<?php
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StallController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Models\Stall; // Import the Stall model
use App\Models\BusinessProfile; // Import the BusinessProfile model
use Illuminate\Http\Request;
use App\Http\Controllers\BusinessProfileController;
use Inertia\Inertia;
use App\Http\Controllers\BusinessContentController;

Auth::routes(['verify' => true]);  // This enables email verification routes

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});
//only verified users can access dashboard
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::post('/create-stall', [StallController::class, 'store']);

Route::get('/customer', fn () => Inertia::render('Customer'))
    ->middleware(['auth', 'verified'])
    ->name('customer');

Route::get('/club', fn () => Inertia::render('Club'))
    ->middleware(['auth', 'verified'])
    ->name('club');

Route::get('/dashboard', fn () => Inertia::render('Dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('/stalls', [StallController::class, 'store']);

    // In routes/web.php or api.php
Route::get('/stalls', [StallController::class, 'index']);  // Adjust according to your controller

Route::post('/save-business-profile', [BusinessProfileController::class, 'store']);

// Route::get('/business-profiles', function () {
//     $businessProfiles = BusinessProfile::all(); // You can adjust this query based on your needs.
//     return Inertia::render('Dashboard', [
//         'businessProfiles' => $businessProfiles
//     ]);
// });

// Route to view the profile
//Route::get('/Dashboard', [BusinessProfileController::class, 'index'])->name('profile.get');

Route::middleware(['auth', 'verified'])->group(function (){
    Route::get('/dashboard', [BusinessProfileController::class, 'index'])->name('profile.get');
    Route::get('/entrepreneur', [StallController::class, 'entrepreneurDashboard'])->name('entrepreneur');
    Route::post('/stallPayment', [StallController::class, 'stallPayment']);
    Route::get('/businessReg', [BusinessProfileController::class, 'registration'])->name('business.reg');
    Route::put('/profile/{id}/status', [BusinessProfileController::class, 'updateStatus'])->name('profile.updateStatus');
    Route::put('/stall/{id}/status', [BusinessProfileController::class, 'updateStallStatus'])->name('profile.updateStallStatus');
    Route::delete('/profile/{id}', [BusinessProfileController::class, 'destroy'])->name('profile.delete');
    Route::delete('/stall/{id}', [BusinessProfileController::class, 'destroyStall'])->name('stall.delete');
    Route::get('/businessProfile', [ProfileController::class, 'profileIndex'])->name('business.profile.get');
    Route::post('/business-profile/update', [ProfileController::class, 'businessUpdate'])->name('business.profile.update');
    Route::post('/content', [ProfileController::class, 'storeContent'])->name('content.store');
    Route::get('/business/content', [ProfileController::class, 'showBusinessContent'])->name('business.content');
    Route::delete('/content/{id}', [ProfileController::class, 'deletePost'])->name('content.delete');


});


   
        
    