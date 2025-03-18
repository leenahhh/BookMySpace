<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Store the user's role in the session
        $role = Auth::user()->role;

        // Redirect based on role
        if ($role === 'customer') {
            return redirect()->route('customer');
        } elseif ($role === 'entrepreneur') {
            return redirect()->route('entrepreneur');
        } elseif ($role === 'club') {
            return redirect()->route('club');
        } elseif ($role === 'admin') {
            return redirect()->route('dashboard');
        }

        // Default redirect (in case no role matches)
        return redirect()->route('customer');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
