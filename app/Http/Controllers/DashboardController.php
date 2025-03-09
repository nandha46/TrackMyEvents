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
        $events = Event::where('user_id', $user->id)->get();
        $data = ['events' => $events];
        return view('dashboard', $data);
    }
}
