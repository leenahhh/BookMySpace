<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\Stall;
use Illuminate\Http\Request;
use App\Models\BusinessProfile;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessProfileHistory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\EmailVerificationRequest;



class BusinessProfileController extends Controller
{

    public function registration(){
        $profile= BusinessProfile::where("user_id",Auth::user()->id)->exists();
        if($profile){
            return redirect()->route('entrepreneur');
        }
        return Inertia::render('BusinessReg');
        
    }

   

    public function store(Request $request)
    {
        // Check if the user already has a business profile
        $userId = Auth::id();

        // Check if the user already has a business profile
        if (BusinessProfile::where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'You have already submitted a business profile.'], 403);
        }
        
        $request->validate([
            'name' => 'required|string',
            'product_type' => 'required|string',
            'desc' => 'required|string',
            'socials' => 'nullable|string',
            'logo' => 'required|file|mimes:jpg,jpeg,png',
        ]);

        $validated['user_id'] = Auth::id(); // This assigns the logged-in user's ID
        $logoPath = $request->file('logo')->store('logos', 's3');
        $logoUrl = env('AWS_URL') . '/' . $logoPath;
        

        BusinessProfile::create([
            'name' => $request->name,
            'product_type' => $request->product_type,
            'desc' => $request->desc,
            'socials' => $request->socials,
            'logo' => $logoUrl,
            'status' => 'pending', // default
            'rejected_reason' => null,
            'user_id' => Auth::id(), 
        ]);

        return redirect()->route('entrepreneur');
    }

    public function index()
    {
        // Fetch profiles where status is 'pending' and eager load the associated user
        $profiles = BusinessProfile::with('user')
            ->where('status', 'pending')
            ->get();

        // Fetch accepted or rejected profiles together
        $processedProfiles = BusinessProfile::with('user')
        ->whereIn('status', ['accepted', 'rejected'])
        ->get();

       // Fetch pending stalls with user and business profile
        $stalls = Stall::with(['user', 'businessProfile'])
        ->where('status', 'pending')
        ->get();

        // Fetch accepted or rejected stalls with user and business profile
        $processedStalls = Stall::with(['user', 'businessProfile'])
        ->whereIn('status', ['accepted', 'rejected'])
        ->get();


            
        // Pass pending profiles data to the front end with Inertia and render Dashboard.vue
        return Inertia::render('Dashboard', [
            'profiles' => $profiles,
            'processedProfiles' => $processedProfiles,
            'stalls' => $stalls,
            'processedStalls' => $processedStalls,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
            'rejected_reason' => 'required_if:status,rejected|max:255',
        ]);
    
        $profile = BusinessProfile::findOrFail($id);
        $profile->status = $request->status;
        $profile->rejected_reason = $request->rejected_reason ?? null;
        $profile->save();
        return redirect()->route('profile.get'); // or Inertia::render, but redirect is quick
    }

    public function destroy($id)
    {
        $profile = BusinessProfile::findOrFail($id);
        $profile->delete();

        return redirect()->route('profile.get');
    }

    public function updateStallStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
            'stall_rejected_reason' => 'required_if:status,rejected|max:255',
        ]);
    
        $stall = Stall::findOrFail($id);
        $stall->status = $request->status;
        $stall->stall_rejected_reason = $request->stall_rejected_reason ?? null;
        $stall->save();
        return redirect()->route('profile.get'); // or Inertia::render, but redirect is quick
    }

    public function destroyStall($id)
    {
        $stall = Stall::findOrFail($id);
        $stall->delete();

        return redirect()->route('profile.get');
    }



}
