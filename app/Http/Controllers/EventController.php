<?php

namespace App\Http\Controllers;

use App\Events\NewEventAdded;
use App\Http\Requests\EventPostRequest;
use App\Models\Event;
use App\Notifications\EventAdded;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Display all events for the current user.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $events = $user->events()->latest('event_date')->paginate(12);
        return view('dashboard', ['events' => $events]);
    }

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
    public function show(int $id): View
    {
        $event = Auth::user()->events()->findOrFail($id);
        return view('events.show', ['event' => $event]);
    }

    /**
     * Create a new Event for the user
     */
    public function store(EventPostRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = Auth::user();

        $isBackgroundImage = $request->boolean('is_background_image');
        $background = $request->input('background_gradient', 'from-purple-500 to-indigo-600');

        if ($isBackgroundImage && $request->hasFile('background_image')) {
            $image = $request->file('background_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/backgrounds'), $imageName);
            $background = $imageName;
        }

        $fields = !empty($validated['fields']) ? implode(',', $validated['fields']) : 'days,months,years';

        $event = Event::create([
            'event_name' => $validated['event_name'],
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'] ?? null,
            'event_type' => $validated['event_type'],
            'fields' => $fields,
            'is_background_image' => $isBackgroundImage && $request->hasFile('background_image'),
            'background' => $background,
            'user_id' => $user->id,
        ]);

        // Dispatching the event added event
        event(new NewEventAdded($event));

        // Dispatching event added notification
        $user->notify(new EventAdded($event));

        return Redirect::route('dashboard')->with('success', 'Event created successfully!');
    }

    /**
     * Show the form for editing an event.
     */
    public function edit(int $id): View
    {
        $event = Auth::user()->events()->findOrFail($id);
        return view('events.edit', ['event' => $event]);
    }

    /**
     * Update the specified event.
     */
    public function update(EventPostRequest $request, int $id): RedirectResponse
    {
        $event = Auth::user()->events()->findOrFail($id);
        $validated = $request->validated();

        $isBackgroundImage = $request->boolean('is_background_image');
        $background = $event->background;

        if ($isBackgroundImage) {
            if ($request->hasFile('background_image')) {
                $image = $request->file('background_image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/backgrounds'), $imageName);
                $background = $imageName;
            }
        } else {
            $background = $request->input('background_gradient', 'from-purple-500 to-indigo-600');
        }

        $fields = !empty($validated['fields']) ? implode(',', $validated['fields']) : 'days,months,years';

        $event->update([
            'event_name' => $validated['event_name'],
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'] ?? null,
            'event_type' => $validated['event_type'],
            'fields' => $fields,
            'is_background_image' => $isBackgroundImage,
            'background' => $background,
        ]);

        return Redirect::route('event.show', $event->id)->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event.
     */
    public function delete(int $id): RedirectResponse
    {
        $event = Auth::user()->events()->findOrFail($id);
        $event->delete();

        return Redirect::route('dashboard')->with('success', 'Event deleted successfully!');
    }
}
