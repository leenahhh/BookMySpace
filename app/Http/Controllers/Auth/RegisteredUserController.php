<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                'unique:' . User::class, // Ensure the email is unique in the 'users' table
                function ($attribute, $value, $fail) {
                    // Check the email format
                    if (!preg_match('/^[a-zA-Z0-9._%+-]+@students\.apiit\.lk$/', $value) &&
                        !preg_match('/^apiitsl\.sac@gmail\.com$/', $value) &&
                        !preg_match('/^afcs@students\.apiit\.lk$/', $value)) {
                        return $fail('The email must be a valid APIIT email address.');
                    }
                    
                    // Check if the email exists in the students table
                    if (!DB::table('students')->where('email', $value)->exists()) {
                        return $fail('This email address does not exist in the APIIT database.');
                    }
                },
            ],
            'password' => ['required', 'confirmed', 'min:12', Rules\Password::defaults()], // Added min:12 rule
            'role' => ['required', 'string', 'in:customer,entrepreneur,club,admin'],
        ]);

        // Create the user in the database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Fire the Registered event
        event(new Registered($user));

        // Log the user in
        Auth::login($user);

        // Redirect to the dashboard
        // return redirect(route('dashboard', absolute: false));
        // Redirect according to role
        switch ($user->role) {
            case 'customer':
                return redirect()->route('customer');
            case 'entrepreneur':
                return redirect()->route('entrepreneur');
            case 'club':
                return redirect()->route('club');
            case 'admin':
                return redirect()->route('dashboard');
            default:
                return redirect()->route('dashboard'); // fallback
        }
    }


}
