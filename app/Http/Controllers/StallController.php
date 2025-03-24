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

        return Inertia::render('Entrepreneur', [
            'accepted_reg'=> $profiles
        ]);
    }

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'stallName' => 'required|string|max:255',
            'stallDescription' => 'required|string|max:1000',
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


        // Calculate stall price based on location
        $stallPrice = 0;
        if ($request->stallLocation == 'Level 6') {
            $stallPrice = 3000;
        } elseif ($request->stallLocation == 'Level 4') {
            $stallPrice = 1500;
        }

        $receiptPath = $request->file('receipt')->store('receipts', 's3');
        $receiptUrl = env('AWS_URL') . '/' . $receiptPath;


        // Save the stall data to the database
        Stall::create([
            'stall_name' => $request->stallName,
            'stall_description' => $request->stallDescription,
            'stall_date' => $request->stallDate,
            'stall_location' => $request->stallLocation,
            'time_range' => $request->timeRange,
            'contact_email' => $request->contactEmail,
            'stall_price' => $stallPrice,
            'user_id'=>Auth::id(),
            'receipt'=>$receiptUrl,

        ]);

        return redirect()->route('entrepreneur');
    }

    public function index() {
        $stalls = Stall::all(); // Assuming you have a Stall model
        return response()->json($stalls);
    }

}
