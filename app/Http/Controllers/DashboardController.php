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
        $query = $user->events();

        // Search filter
        if ($search = $request->input('search')) {
            $query->where('event_name', 'like', "%{$search}%");
        }

        // Category / Type filter
        $filter = $request->input('filter');
        if ($filter) {
            if ($filter === 'Past') {
                $query->where('event_date', '<', now()->toDateString());
            } elseif ($filter === 'Future') {
                $query->where('event_date', '>=', now()->toDateString());
            } elseif (in_array($filter, ['Personal', 'Private', 'Work', 'Reminder'])) {
                $query->where('event_type', $filter);
            }
        }

        $viewMode = $request->input('view', 'cards'); // cards, timeline, calendar

        if ($viewMode === 'timeline') {
            $events = $query->orderBy('event_date', 'asc')->paginate(20)->withQueryString();
        } elseif ($viewMode === 'calendar') {
            $events = $query->orderBy('event_date', 'asc')->get();
        } else {
            $events = $query->latest('event_date')->paginate(12)->withQueryString();
        }

        return view('dashboard', [
            'events' => $events,
            'currentFilter' => $filter,
            'currentSearch' => $search,
            'viewMode' => $viewMode,
        ]);
    }
}
