<?php

namespace App\Http\Controllers;

use App\Models\Stall;
use Illuminate\Http\Request;

class StallController extends Controller
{
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

        // Calculate stall price based on location
        $stallPrice = 0;
        if ($request->stallLocation == 'Main Hall') {
            $stallPrice = 5000;
        } elseif ($request->stallLocation == 'Outdoor Area') {
            $stallPrice = 3000;
        }

        // Save the stall data to the database
        Stall::create([
            'stall_name' => $request->stallName,
            'stall_description' => $request->stallDescription,
            'stall_date' => $request->stallDate,
            'stall_location' => $request->stallLocation,
            'time_range' => $request->timeRange,
            'contact_email' => $request->contactEmail,
            'stall_price' => $stallPrice,
        ]);

        return response()->json(['success' => 'Stall booked successfully!']);
    }

    public function index() {
        $stalls = Stall::all(); // Assuming you have a Stall model
        return response()->json($stalls);
    }
}
