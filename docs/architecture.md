# TrackMyEvents — Architecture & Developer Documentation

## 1. Domain Model: `App\Models\Event`

### Target DateTime & Recurrence Algorithm
The `getTargetDateTime(): Carbon` method on `Event` calculates the effective upcoming or reference date for countdown timers:
- If `is_recurring` is `false`, it returns `event_date` + `event_time`.
- If `is_recurring` is `true`:
  - `yearly`: Computes the month and day in the current year. If already past, advances to next year.
  - `monthly`: Computes the day in the current month. If already past, advances to next month.
  - `weekly`: Advances to the next matching day of the week.
  - `daily`: Sets to today's target time; if past, advances to tomorrow.

### Countdown Computations
- `getYears()`, `getMonths()`, `getDays()`, `getHours()`, `getMinutes()`, `getSeconds()` compute precise time deltas against `now()`.
- `getWorkdays()` computes Monday–Friday business days remaining using Carbon's `diffInWeekdays()`.

### Milestone Progress Calculation
- `getMilestonesProgress()` parses JSON array `milestones` and returns an integer percentage `(completed_count / total_count) * 100`.

### Calendar Feeds & Integration
- `toIcs()` generates compliant RFC 5545 `.ics` VCALENDAR and VEVENT payloads with unique UIDs.
- `google_calendar_url` generates pre-filled Google Calendar event creation URLs.

---

## 2. Notification Pipeline & Scheduler

- Command `php artisan events:send-reminders` runs hourly via `routes/console.php`.
- Dispatches `App\Notifications\EventReminder` across `mail` and `database` channels for events occurring within a 24-hour window.
- New events trigger `App\Events\NewEventAdded` and notify users via `App\Notifications\EventAdded`.

---

## 3. Data Portability (CSV & JSON)

- Handled in `ProfileController` and `EventController`.
- CSV headers: `['event_name', 'event_date', 'event_time', 'event_type', 'fields', 'is_recurring', 'recurrence_type', 'tags', 'description']`.
- JSON structure encapsulates nested tags and milestone checklists.

---

## 4. Frontend Design System & Live Reactivity

- Powered by **Alpine.js** and **Tailwind CSS**.
- `Alpine.data('countdown', (date, time, isPast))` initializes a 1000ms timer that recalculates live without server roundtrips.
- `Alpine.data('dropzone', (preview))` handles drag-and-drop file inputs with `FileReader` instant data URL preview.
- `Alpine.data('toastSystem')` listens for global `window.dispatchEvent(new CustomEvent('toast', ...))` events and automatically catches session flash messages.
- Global keyboard listener captures `N` (new event), `/` (search focus), and `Esc` (modal close).
