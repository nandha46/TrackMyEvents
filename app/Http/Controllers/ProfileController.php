<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update user avatar.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('avatars', $filename, 'public');

            // Delete old avatar if not default
            if ($user->avatar && $user->avatar !== 'default.png' && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            $user->avatar = $filename;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Export user events in CSV or JSON format with optional filtering.
     */
    public function exportEvents(Request $request): StreamedResponse|Response
    {
        $user = $request->user();
        $format = $request->input('format', 'csv');
        $filter = $request->input('filter', 'all'); // all, upcoming, past, Personal, Work, etc.

        $query = $user->events();

        if ($filter === 'upcoming') {
            $query->where('event_date', '>=', now()->toDateString());
        } elseif ($filter === 'past') {
            $query->where('event_date', '<', now()->toDateString());
        } elseif (in_array($filter, ['Personal', 'Work', 'Reminder', 'Private', 'Birthday'])) {
            $query->where('event_type', $filter);
        }

        $events = $query->orderBy('event_date', 'asc')->get();

        if ($format === 'json') {
            $jsonData = $events->map(fn($e) => [
                'event_name' => $e->event_name,
                'event_date' => $e->event_date,
                'event_time' => $e->event_time,
                'event_type' => $e->event_type,
                'fields' => $e->fields,
                'is_recurring' => $e->is_recurring,
                'recurrence_type' => $e->recurrence_type,
                'tags' => $e->tags,
                'milestones' => $e->milestones,
                'description' => $e->description,
            ]);

            return response(json_encode($jsonData, JSON_PRETTY_PRINT), 200, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="my_events_' . date('Y-m-d') . '.json"',
            ]);
        }

        // CSV default
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="my_events_' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream(function () use ($events) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['Event Name', 'Date', 'Time', 'Type', 'Fields', 'Recurring', 'Recurrence Type', 'Tags', 'Description']);

            foreach ($events as $event) {
                fputcsv($file, [
                    $event->event_name,
                    $event->event_date,
                    $event->event_time ?? '',
                    $event->event_type,
                    $event->fields,
                    $event->is_recurring ? 'Yes' : 'No',
                    $event->recurrence_type ?? 'none',
                    is_array($event->tags) ? implode(', ', $event->tags) : '',
                    $event->description ?? '',
                ]);
            }
            fclose($file);
        }, 200, $headers);
    }

    /**
     * Download sample template for CSV or JSON.
     */
    public function downloadTemplate(string $format): Response|StreamedResponse
    {
        if ($format === 'json') {
            $sample = [
                [
                    'event_name' => 'Tokyo Summer Trip',
                    'event_date' => date('Y') . '-08-15',
                    'event_time' => '10:30:00',
                    'event_type' => 'Personal',
                    'fields' => 'days,months,years',
                    'is_recurring' => false,
                    'recurrence_type' => 'none',
                    'tags' => ['travel', 'vacation'],
                    'milestones' => [
                        ['id' => '1', 'title' => 'Book Flights', 'completed' => true],
                        ['id' => '2', 'title' => 'Reserve Hotel', 'completed' => false],
                    ],
                    'description' => 'Family trip to Tokyo & Kyoto',
                ],
                [
                    'event_name' => 'Annual Birthday Celebration',
                    'event_date' => date('Y') . '-10-25',
                    'event_time' => '18:00:00',
                    'event_type' => 'Reminder',
                    'fields' => 'days,months,years',
                    'is_recurring' => true,
                    'recurrence_type' => 'yearly',
                    'tags' => ['family'],
                    'description' => 'Birthday milestone',
                ]
            ];

            return response(json_encode($sample, JSON_PRETTY_PRINT), 200, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="events_template.json"',
            ]);
        }

        // CSV Template
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="events_template.csv"',
        ];

        return response()->stream(function () {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['event_name', 'event_date', 'event_time', 'event_type', 'fields', 'is_recurring', 'recurrence_type', 'tags', 'description']);
            fputcsv($file, ['Tokyo Summer Trip', date('Y') . '-08-15', '10:30:00', 'Personal', 'days,months,years', '0', 'none', 'travel,vacation', 'Family trip to Tokyo & Kyoto']);
            fputcsv($file, ['Annual Birthday Celebration', date('Y') . '-10-25', '18:00:00', 'Reminder', 'days,months,years', '1', 'yearly', 'family', 'Birthday milestone']);
            fclose($file);
        }, 200, $headers);
    }

    /**
     * Import events from CSV or JSON file.
     */
    public function importEvents(Request $request): RedirectResponse
    {
        $request->validate([
            'import_file' => ['required', 'file', 'max:5120'], // max 5MB
        ]);

        $file = $request->file('import_file');
        $extension = strtolower($file->getClientOriginalExtension());
        $user = $request->user();
        $importedCount = 0;

        if ($extension === 'json') {
            $content = file_get_contents($file->getRealPath());
            $items = json_decode($content, true);

            if (!is_array($items)) {
                return redirect()->back()->with('error', 'Invalid JSON structure.');
            }

            foreach ($items as $item) {
                if (empty($item['event_name']) || empty($item['event_date'])) {
                    continue;
                }

                $tags = is_array($item['tags'] ?? null) ? $item['tags'] : [];
                $milestones = is_array($item['milestones'] ?? null) ? $item['milestones'] : [];

                Event::create([
                    'event_name' => $item['event_name'],
                    'event_date' => $item['event_date'],
                    'event_time' => $item['event_time'] ?? null,
                    'event_type' => $item['event_type'] ?? 'Personal',
                    'fields' => is_array($item['fields'] ?? null) ? implode(',', $item['fields']) : ($item['fields'] ?? 'days,months,years'),
                    'is_background_image' => false,
                    'background' => 'gradient-1',
                    'is_recurring' => !empty($item['is_recurring']),
                    'recurrence_type' => $item['recurrence_type'] ?? 'none',
                    'tags' => $tags,
                    'milestones' => $milestones,
                    'description' => $item['description'] ?? null,
                    'user_id' => $user->id,
                ]);
                $importedCount++;
            }
        } elseif (in_array($extension, ['csv', 'txt'])) {
            $handle = fopen($file->getRealPath(), 'r');
            if ($handle !== false) {
                $header = fgetcsv($handle, 1000, ',');
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    if (count($row) < 2 || empty($row[0])) {
                        continue;
                    }

                    $eventName = trim($row[0] ?? '');
                    $eventDate = trim($row[1] ?? '');
                    $eventTime = !empty($row[2]) ? trim($row[2]) : null;
                    $eventType = !empty($row[3]) ? trim($row[3]) : 'Personal';
                    $fields = !empty($row[4]) ? trim($row[4]) : 'days,months,years';
                    $isRecurring = !empty($row[5]) && in_array(strtolower(trim($row[5])), ['1', 'true', 'yes']);
                    $recurrenceType = !empty($row[6]) ? trim($row[6]) : 'none';
                    $tagsRaw = !empty($row[7]) ? trim($row[7]) : '';
                    $tags = array_values(array_filter(array_map('trim', explode(',', $tagsRaw))));
                    $description = !empty($row[8]) ? trim($row[8]) : null;

                    if ($eventName && $eventDate) {
                        Event::create([
                            'event_name' => $eventName,
                            'event_date' => $eventDate,
                            'event_time' => $eventTime,
                            'event_type' => $eventType,
                            'fields' => $fields,
                            'is_background_image' => false,
                            'background' => 'gradient-1',
                            'is_recurring' => $isRecurring,
                            'recurrence_type' => $recurrenceType,
                            'tags' => $tags,
                            'milestones' => [],
                            'description' => $description,
                            'user_id' => $user->id,
                        ]);
                        $importedCount++;
                    }
                }
                fclose($handle);
            }
        } else {
            return redirect()->back()->with('error', 'Unsupported file type. Please upload a .csv or .json file.');
        }

        return redirect()->back()->with('success', "Imported {$importedCount} events successfully!");
    }
}
