<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return Inertia::render('Entrepreneur');
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