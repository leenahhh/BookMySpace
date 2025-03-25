<?php

namespace App\Http\Controllers;

use App\Models\Stall;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessProfile;
use Inertia\Inertia;

class StallController extends Controller
{
    public function entrepreneurDashboard() 
    {
        $profile= BusinessProfile::where("user_id",Auth::user()->id)->exists();
        if(!$profile){
            return redirect()->route('business.reg');
        }

        $user= Auth::id();
        $profiles= BusinessProfile::where("user_id",$user)->where("status","accepted")->exists();
        $stalls= Stall::with(['user', 'businessProfile'])->where("user_id",$user)->get();

        return Inertia::render('Entrepreneur', [
            'accepted_reg'=> $profiles,
            'user_stall'=>$stalls
        ]);
    }

    public function store(Request $request)
    {
        // Check if the user already has a business profile
        $userId = Auth::id();

        // Validation
        $validated = $request->validate([
            'stallDate' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $dayOfWeek = \Carbon\Carbon::parse($value)->dayOfWeek; // Sunday=0, Monday=1, Tuesday=2, ...
                    if (!in_array($dayOfWeek, [2,3,4])) {
                        $fail('The ' . $attribute . ' must be a Tuesday, Wednesday or Thursday.');
                    }
                },
            ],
            'stallLocation' => 'required|string',
            'timeRange' => 'required|string',
            'contactEmail' => 'required|email',
        ]);


        $overlapping = Stall::where('stall_date', $request->stallDate)
                            ->where('stall_location', $request->stallLocation)
                            ->where('time_range', $request->timeRange)
                            ->exists();

        if ($overlapping) {
            return response()->json([
                'error' => 'This time slot and location are already booked. Please choose another.'
            ], 409); // 409 Conflict
        }

        return response()->json([
            'success' => true,
            'message' => 'Stall booking valid. Proceed to payment.',
        ]);
    }

    public function stallPayment(Request $request){

        $receiptPath = $request->file('receipt')->store('receipts', 's3');
        $receiptUrl = env('AWS_URL') . '/' . $receiptPath;

        // Calculate stall price based on location
        $stallPrice = 0;
        if ($request->stallLocation == 'Level 6') {
            $stallPrice = 3000;
        } elseif ($request->stallLocation == 'Level 4') {
            $stallPrice = 1500;
        }

        $businessId = BusinessProfile::where('user_id', Auth::id())->first()->id;

        // Save the stall data to the database
        Stall::create([
            'stall_date' => $request->stallDate,
            'stall_location' => $request->stallLocation,
            'time_range' => $request->timeRange,
            'contact_email' => $request->contactEmail,
            'stall_price' => $stallPrice,
            'user_id'=>Auth::id(),
            'receipt'=>$receiptUrl,
            'business_id'=>$businessId,
        ]);

        return redirect()->route('entrepreneur');
    }

    

}
