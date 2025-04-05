<?php

namespace App\Http\Controllers;

use App\Events\NewEventAdded;
use App\Http\Requests\EventPostRequest;
use App\Models\Event;
use App\Notifications\EventAdded;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Shows the create event form
     */
    public function create(): View
    {
        return view('forms.addEvent');
    }

    /**
     * Shows an event in full page
     */
    public function show(): View
    {
        return view('layout.single');
    }
    
    /**
     * Create a new Event for the user
     */
    public function store(EventPostRequest $request): RedirectResponse
    {
       $validated = $request->validated();

       $user = $request->user();

       Log::info($validated);

      $event = Event::create([
        'event_name' => 'New Day',
        'event_date' => Carbon::today(),
        'event_type' => 'Work',
        'fields' => "days",
        'is_background_image' => false,
        'background' => 'gradient2',
        'user_id' => $user->id,
       ]);

       // Dispatching the event added event
       event(new NewEventAdded($event));

       // dispathching event added notification
       $user->notify(new EventAdded($event));

        return Redirect::route('dashboard');
    }
}
