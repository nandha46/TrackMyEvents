<?php

namespace App\Http\Controllers;

use App\Events\NewEventAdded;
use App\Http\Requests\EventPostRequest;
use App\Models\Event;
use App\Notifications\EventAdded;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventController extends Controller
{
    /**
     * Display all events for the current user.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = $user->events();

        $selectedTag = $request->input('tag');
        $filter = $request->input('filter');

        // Tag filter (primary organization system)
        if ($selectedTag) {
            $query->where(function ($q) use ($selectedTag) {
                $q->whereJsonContains('tags', $selectedTag)
                  ->orWhere('tags', 'like', "%\"{$selectedTag}\"%")
                  ->orWhere('tags', 'like', "%{$selectedTag}%")
                  ->orWhere('event_type', $selectedTag);
            });
        } elseif ($filter) {
            // Legacy/fallback filter support
            if ($filter === 'Past') {
                $query->where('event_date', '<', now()->toDateString());
            } elseif ($filter === 'Future') {
                $query->where('event_date', '>=', now()->toDateString());
            } else {
                $query->where(function ($q) use ($filter) {
                    $q->where('event_type', $filter)
                      ->orWhereJsonContains('tags', $filter)
                      ->orWhere('tags', 'like', "%\"{$filter}\"%");
                });
            }
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%")
                  ->orWhere('event_type', 'like', "%{$search}%");
            });
        }

        $events = $query->orderBy('event_date', 'asc')->paginate(12)->withQueryString();
        $viewMode = $request->input('view', 'cards');

        // Extract all unique tags used across this user's events
        $allUserEvents = $user->events()->select('tags', 'event_type')->get();
        $extractedTags = collect();
        foreach ($allUserEvents as $e) {
            if (is_array($e->tags)) {
                foreach ($e->tags as $t) {
                    if ($t = trim($t)) $extractedTags->push($t);
                }
            }
            if ($e->event_type && !in_array($e->event_type, ['Personal', 'Private'])) {
                $extractedTags->push($e->event_type);
            }
        }
        $availableTags = $extractedTags->unique()->values()->all();

        return view('dashboard', [
            'events' => $events,
            'viewMode' => $viewMode,
            'currentFilter' => $filter,
            'currentTag' => $selectedTag,
            'currentSearch' => $search,
            'availableTags' => $availableTags,
        ]);
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
        $background = $request->input('background_gradient', 'gradient-1');
        $pexelsId = $request->input('pexels_id');
        $pexelsPhotographer = $request->input('pexels_photographer');
        $pexelsPhotographerUrl = $request->input('pexels_photographer_url');
        $pexelsPhotoUrl = $request->input('pexels_photo_url');
        $pexelsDownloadUrl = $request->input('pexels_download_url');

        // Handle uploaded file
        if ($isBackgroundImage && $request->hasFile('background_image')) {
            $image = $request->file('background_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/backgrounds'), $imageName);
            $background = $imageName;
            // Clear Pexels info if user uploaded custom photo
            $pexelsId = null;
            $pexelsPhotographer = null;
            $pexelsPhotographerUrl = null;
            $pexelsPhotoUrl = null;
        } elseif ($isBackgroundImage && !empty($pexelsDownloadUrl)) {
            // Handle Pexels image selection
            $pexelsService = app(\App\Services\PexelsService::class);
            $savedFilename = $pexelsService->downloadAndSaveLocally($pexelsDownloadUrl, $pexelsId, $validated['event_name']);
            if ($savedFilename) {
                $background = $savedFilename;
            }
        }

        $fields = !empty($validated['fields']) ? implode(',', $validated['fields']) : 'days,months,years';

        // Parse tags if provided as comma-separated string
        $tags = [];
        if (!empty($validated['tags'])) {
            if (is_array($validated['tags'])) {
                $tags = $validated['tags'];
            } else {
                $tags = array_values(array_filter(array_map('trim', explode(',', $validated['tags']))));
            }
        }

        // Parse milestones if provided
        $milestones = [];
        if (!empty($validated['milestones'])) {
            if (is_array($validated['milestones'])) {
                $milestones = $validated['milestones'];
            } elseif (is_string($validated['milestones'])) {
                $lines = explode("\n", str_replace("\r", "", $validated['milestones']));
                foreach ($lines as $idx => $line) {
                    $trimmed = trim($line);
                    if ($trimmed) {
                        $milestones[] = [
                            'id' => (string)($idx + 1),
                            'title' => $trimmed,
                            'completed' => false,
                        ];
                    }
                }
            }
        }

        $event = Event::create([
            'event_name' => $validated['event_name'],
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'] ?? null,
            'event_type' => $validated['event_type'],
            'fields' => $fields,
            'is_background_image' => $isBackgroundImage && ($request->hasFile('background_image') || !empty($pexelsDownloadUrl)),
            'background' => $background,
            'pexels_id' => $pexelsId,
            'pexels_photographer' => $pexelsPhotographer,
            'pexels_photographer_url' => $pexelsPhotographerUrl,
            'pexels_photo_url' => $pexelsPhotoUrl,
            'is_recurring' => $request->boolean('is_recurring'),
            'recurrence_type' => $validated['recurrence_type'] ?? 'none',
            'tags' => $tags,
            'milestones' => $milestones,
            'description' => $validated['description'] ?? null,
            'share_token' => Str::random(32),
            'user_id' => $user->id,
        ]);

        // Dispatching the event added event
        event(new NewEventAdded($event));

        // Dispatching event added notification
        try {
            $user->notify(new EventAdded($event));
        } catch (\Throwable $e) {
            Log::warning('Could not send event added notification: ' . $e->getMessage());
        }

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
        $pexelsId = $event->pexels_id;
        $pexelsPhotographer = $event->pexels_photographer;
        $pexelsPhotographerUrl = $event->pexels_photographer_url;
        $pexelsPhotoUrl = $event->pexels_photo_url;

        if ($isBackgroundImage) {
            if ($request->hasFile('background_image')) {
                $image = $request->file('background_image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/backgrounds'), $imageName);
                $background = $imageName;
                $pexelsId = null;
                $pexelsPhotographer = null;
                $pexelsPhotographerUrl = null;
                $pexelsPhotoUrl = null;
            } elseif ($request->filled('pexels_download_url')) {
                $pexelsService = app(\App\Services\PexelsService::class);
                $savedFilename = $pexelsService->downloadAndSaveLocally(
                    $request->input('pexels_download_url'),
                    $request->input('pexels_id'),
                    $validated['event_name']
                );
                if ($savedFilename) {
                    $background = $savedFilename;
                    $pexelsId = $request->input('pexels_id');
                    $pexelsPhotographer = $request->input('pexels_photographer');
                    $pexelsPhotographerUrl = $request->input('pexels_photographer_url');
                    $pexelsPhotoUrl = $request->input('pexels_photo_url');
                }
            }
        } else {
            $background = $request->input('background_gradient', 'gradient-1');
            $pexelsId = null;
            $pexelsPhotographer = null;
            $pexelsPhotographerUrl = null;
            $pexelsPhotoUrl = null;
        }

        $fields = !empty($validated['fields']) ? implode(',', $validated['fields']) : 'days,months,years';

        // Parse tags
        $tags = [];
        if (!empty($validated['tags'])) {
            if (is_array($validated['tags'])) {
                $tags = $validated['tags'];
            } else {
                $tags = array_values(array_filter(array_map('trim', explode(',', $validated['tags']))));
            }
        }

        // Maintain existing milestones if not replaced
        $milestones = $event->milestones ?? [];
        if ($request->has('milestones_raw')) {
            $raw = $request->input('milestones_raw');
            $lines = explode("\n", str_replace("\r", "", $raw));
            $milestones = [];
            foreach ($lines as $idx => $line) {
                $trimmed = trim($line);
                if ($trimmed) {
                    $milestones[] = [
                        'id' => (string)($idx + 1),
                        'title' => $trimmed,
                        'completed' => false,
                    ];
                }
            }
        }

        $event->update([
            'event_name' => $validated['event_name'],
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'] ?? null,
            'event_type' => $validated['event_type'],
            'fields' => $fields,
            'is_background_image' => $isBackgroundImage,
            'background' => $background,
            'pexels_id' => $pexelsId,
            'pexels_photographer' => $pexelsPhotographer,
            'pexels_photographer_url' => $pexelsPhotographerUrl,
            'pexels_photo_url' => $pexelsPhotoUrl,
            'is_recurring' => $request->boolean('is_recurring'),
            'recurrence_type' => $validated['recurrence_type'] ?? 'none',
            'tags' => $tags,
            'milestones' => $milestones,
            'description' => $validated['description'] ?? null,
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

    /**
     * Export event as iCal (.ics) file.
     */
    public function exportIcal(int $id): Response
    {
        $event = Auth::user()->events()->findOrFail($id);
        $icsData = $event->toIcs();
        $filename = Str::slug($event->event_name) . '.ics';

        return response($icsData, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Public shareable view for an event.
     */
    public function publicShare(string $token): View
    {
        $event = Event::where('share_token', $token)->firstOrFail();
        return view('events.public_share', ['event' => $event]);
    }

    /**
     * Toggle milestone checkbox.
     */
    public function toggleMilestone(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $event = Auth::user()->events()->findOrFail($id);
        $milestoneId = (string) $request->input('milestone_id');

        $milestones = $event->milestones ?? [];
        foreach ($milestones as &$m) {
            if ((string) ($m['id'] ?? '') === $milestoneId) {
                $m['completed'] = !($m['completed'] ?? false);
                break;
            }
        }
        unset($m);

        $event->milestones = $milestones;
        $event->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'progress' => $event->getMilestonesProgress(),
                'milestones' => $event->milestones,
            ]);
        }

        return redirect()->back()->with('success', 'Milestone updated!');
    }

    /**
     * Bulk delete events.
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $idsRaw = $request->input('ids');
        $ids = is_array($idsRaw) ? $idsRaw : json_decode($idsRaw, true);

        if (!empty($ids) && is_array($ids)) {
            $count = Auth::user()->events()->whereIn('id', $ids)->delete();
            return Redirect::route('dashboard')->with('success', "Deleted {$count} events successfully.");
        }

        return Redirect::route('dashboard')->with('error', 'No events selected for deletion.');
    }

    /**
     * Bulk update category.
     */
    public function bulkCategory(Request $request): RedirectResponse
    {
        $idsRaw = $request->input('ids');
        $ids = is_array($idsRaw) ? $idsRaw : json_decode($idsRaw, true);
        $category = $request->input('category');

        if (!empty($ids) && is_array($ids) && in_array($category, ['Personal', 'Work', 'Reminder', 'Private', 'Birthday'])) {
            $count = Auth::user()->events()->whereIn('id', $ids)->update(['event_type' => $category]);
            return Redirect::route('dashboard')->with('success', "Updated category for {$count} events.");
        }

        return Redirect::route('dashboard')->with('error', 'Invalid category or no events selected.');
    }

    /**
     * Bulk export selected events as CSV or JSON.
     */
    public function bulkExport(Request $request): StreamedResponse|RedirectResponse
    {
        $idsRaw = $request->input('ids');
        $ids = is_array($idsRaw) ? $idsRaw : json_decode($idsRaw, true);
        $format = $request->input('format', 'csv');

        $events = Auth::user()->events();
        if (!empty($ids) && is_array($ids)) {
            $events = $events->whereIn('id', $ids);
        }
        $eventList = $events->get();

        if ($format === 'json') {
            $data = $eventList->map(fn($e) => [
                'event_name' => $e->event_name,
                'event_date' => $e->event_date,
                'event_time' => $e->event_time,
                'event_type' => $e->event_type,
                'is_recurring' => $e->is_recurring,
                'recurrence_type' => $e->recurrence_type,
                'tags' => $e->tags,
                'milestones' => $e->milestones,
                'description' => $e->description,
            ]);

            return response()->streamDownload(function () use ($data) {
                echo json_encode($data, JSON_PRETTY_PRINT);
            }, 'events_export_' . date('Y-m-d_His') . '.json', [
                'Content-Type' => 'application/json',
            ]);
        }

        // CSV export
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="events_export_' . date('Y-m-d_His') . '.csv"',
        ];

        return response()->stream(function () use ($eventList) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Event Name', 'Date', 'Time', 'Type', 'Recurring', 'Tags', 'Description']);

            foreach ($eventList as $event) {
                fputcsv($file, [
                    $event->id,
                    $event->event_name,
                    $event->event_date,
                    $event->event_time ?? '',
                    $event->event_type,
                    $event->is_recurring ? $event->recurrence_type : 'No',
                    is_array($event->tags) ? implode(', ', $event->tags) : '',
                    $event->description ?? '',
                ]);
            }
            fclose($file);
        }, 200, $headers);
    }
}
