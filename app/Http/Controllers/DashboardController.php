<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();
        $events = $user->events()->paginate(12);
        $data = ['events' => $events];
        return view('dashboard', $data);
    }
}
