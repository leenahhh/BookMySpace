<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessProfile;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function customerDashboard(): Response
    {
        return Inertia::render('Customer');
    }

    public function entrepreneurDashboard(): Response
    {
        $profile= BusinessProfile::where("user_id",Auth::user()->id)->exists();
        if(!$profile){
            return Inertia::render('BusinessReg');
        }
        return Inertia::render('entrepreneur');
    }

    public function clubDashboard(): Response
    {
        return Inertia::render('Club');
    }

    public function adminDashboard(): Response
    {
        return Inertia::render('Dashboard');
    }
}