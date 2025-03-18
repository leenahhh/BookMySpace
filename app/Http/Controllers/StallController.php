<?php

namespace App\Http\Controllers;

use App\Models\Stall;
use Illuminate\Http\Request;

class StallController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'stallName' => 'required|string|max:255',
            'stallDescription' => 'required|string',
            'stallDate' => 'required|date|after:today', // Must be in the future
            'stallLocation' => 'required|string|max:255',
            'startTime' => 'required|date_format:H:i',
            'endTime' => 'required|date_format:H:i|after:startTime', // End time must be later than start time
            'contactEmail' => 'required|email',
            'stallBookingCost' => 'required|numeric|min:1', // Must be at least 1
            'isAvailable' => 'required|boolean',
        ]);

        Stall::create($validatedData);

        return response()->json(['message' => 'Stall created successfully!']);
    }
}
