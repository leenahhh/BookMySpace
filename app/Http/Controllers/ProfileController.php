<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\BusinessProfile;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $processedProfile = BusinessProfile::with('user')
        ->where('user_id', Auth::id())
        ->first();

        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'processedProfile' => $processedProfile,
        ]);
        
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function profileIndex(){

        $processedProfile = BusinessProfile::with('user')
        ->where('user_id', Auth::id())
        ->first();

        return Inertia::render('BusinessProfile', [
            'processedProfile' => $processedProfile,
        ]);
        
    }

    public function businessUpdate(Request $request)
    {
        $userId = Auth::id();

        $profile = BusinessProfile::where('user_id', $userId)->first();

        if (!$profile) {
            return response()->json(['message' => 'No business profile found.'], 404);
        }

        $request->validate([
            'name' => 'required|string',
            'product_type' => 'required|string',
            'desc' => 'required|string',
            'socials' => 'nullable|string',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png',
        ]);

        $logoUrl = $profile->logo;

        if ($request->hasFile('logo')) {
            // Extract S3 object key from full URL
            $existingLogoPath = str_replace(env('AWS_URL') . '/', '', $profile->logo);

            // Delete old logo from S3 if it exists
            if (Storage::disk('s3')->exists($existingLogoPath)) {
                Storage::disk('s3')->delete($existingLogoPath);
            }

            // Upload new logo and get new URL
            $newLogoPath = $request->file('logo')->store('logos', 's3');
            $logoUrl = env('AWS_URL') . '/' . $newLogoPath;
        }

        // Update all fields except status and rejected_reason
        $profile->update([
            'name' => $request->name,
            'product_type' => $request->product_type,
            'desc' => $request->desc,
            'socials' => $request->socials,
            'logo' => $logoUrl,
        ]);

        return redirect()->route('business.profile.get')->with('success', 'Business profile updated successfully.');
    }
}
