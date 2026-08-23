# Project Tasks & To-Do List

## 1. Core Event Management (CRUD & Views)
- [x] Implement `EventController::store()` to use actual request inputs (`event_name`, `event_date`, `event_type`, `fields`, background image/gradient upload) instead of hardcoded data and remove `dd($validated)`.
- [x] Create Single Event view (`resources/views/events/show.blade.php`) and implement `EventController::show()`.
- [x] Implement `EventController::index()` or standardize dashboard vs events listing routes.
- [x] Implement `EventController::edit()`, `EventController::update()`, and edit event view template.
- [x] Implement `EventController::delete()` (destroy) method to handle event deletion.
- [x] Add Form Request validation rules for file uploads, backgrounds, and time fields in `EventPostRequest`.

## 2. Event Display & Time Calculation
- [x] Fix/complete dynamic background rendering on event cards (handling custom uploaded backgrounds and various gradient options).
- [x] Expand countdown / time-lapse calculations in `App\Models\Event` to support remaining fields (weeks, minutes, seconds, workdays, hours).
- [x] Add Calendar View and Timeline Mode for events as outlined in the project README.
- [x] Implement event categorization filters (Personal, Work, Reminder, Past, Future, Birthday) in the dashboard and navigation bar.
- [x] Implement the search bar functionality in the navigation bar (`layouts/navigation.blade.php`).

## 3. CSV Import & Export
- [x] Complete Livewire `UploadCSV` component: re-enable validation, map imported rows with user ID, and handle error logging properly.
- [x] Implement CSV Export functionality (currently `profile/partials/export-events.blade.php` points to `profile.update` without export logic).
- [x] Implement a dedicated controller/action for exporting events data as CSV.

## 4. User Profile & Avatar
- [x] Implement `ProfileController::updateAvatar()` to handle avatar file upload, validation, storage, and database update.
- [x] Connect the file input in `resources/views/profile/partials/update-avatar-form.blade.php` to the form submission.
- [x] Add Settings page and route (currently `#` in navigation user menu).

## 5. Authentication & OAuth
- [x] Fix Google OAuth callback in `GoogleAuthController::callback()`: change `Auth::login($user)` to `Auth::login($existingUser)` (currently passing Socialite user object instead of Eloquent model).
- [x] Fix Google OAuth avatar download & storage handling.
- [x] Remove `dd($e)` debug dump in Google OAuth exception handler and replace with proper error feedback.

## 6. Notifications & Mail
- [x] Update `App\Notifications\EventAdded` to use real event data for Slack and Mail notifications instead of sample invoice text.
- [x] Configure and test Google SMTP mail delivery and Slack webhook integration.
- [x] Add in-app notification center / dropdown UI for the bell icon in the top navigation bar.

## 7. Testing & Quality Assurance
- [x] Write Feature tests for Event CRUD operations (Create, Read, Update, Delete).
- [x] Write Feature tests for CSV Import and Export.
- [x] Write Feature tests for Google OAuth login flow.
- [x] Write Unit tests for `Event` model date/time calculations and helper methods.

## UI/Visual Improvements
- [x] in grid view, some cards are invisible and it's height is not uniform
- [x] profile menu is always open/visible
- [x] Add live live-ticking client-side countdown timer (seconds/minutes ticking in real-time) on single event view & dashboard cards.
- [x] Add dark mode toggle with persistent local storage / user preference support.
- [x] Implement skeleton loading state placeholders for event cards in grid and timeline view.
- [x] Add interactive toast notifications (success / error popups) when creating, editing, and deleting events.
- [x] Implement responsive mobile navigation drawer with active route highlights and smooth transitions.

## UX & Interaction Enhancements
- [x] Implement custom modal confirmation dialog for event deletion instead of default browser confirm alert.
- [x] Add drag-and-drop image dropzone with instant client-side preview for event background and avatar uploads.
- [x] Add quick-action menu on event cards (Edit, Share, Export to iCal, Delete).
- [x] Implement keyboard shortcuts (e.g. `n` for new event, `/` to focus search, `Esc` to close modals).
- [x] Add batch actions in dashboard (bulk delete, bulk export selected events, bulk change category).

## New Features & Capabilities
- [x] **iCal / Google Calendar Export**: Generate `.ics` files and "Add to Google Calendar" links for events.
- [x] **PDF Export & Event Summary Cards**: Export high-resolution countdown cards or summary reports as PDF.
- [x] **Event Sharing & Public Links**: Allow users to generate a secure shareable public link / QR code for any countdown.
- [x] **Recurring Events**: Support recurring schedules (yearly birthdays/anniversaries, monthly milestones, weekly meetings).
- [x] **Email & Push Reminders**: Scheduled queue worker to dispatch reminder emails / notifications (e.g. 1 day before, 1 hour before).
- [x] **Event Tags & Multi-Categorization**: Allow custom tags (e.g. `#travel`, `#family`, `#fitness`) alongside core categories.
- [x] **Milestone & Milestone Checklist**: Add sub-tasks/milestones checklist inside each event with progress percentage.

## Data Import & Export
- [x] Add JSON data export and import alongside existing CSV capabilities.
- [x] Add export filtering (export only past events, only upcoming events, or specific categories).
- [x] Provide a downloadable CSV template with sample data on the import page.
- [x] Implement import preview & validation step before committing imported rows to database.

## Public Frontend & Landing Website
- [x] Redesign `welcome.blade.php` into a bespoke, high-converting landing page showcasing product features, live interactive demo countdown widgets, testimonials, and feature highlights.
- [x] Add interactive live demo widget on landing page where visitors can create and preview a countdown without logging in.
- [x] Create dedicated `Privacy Policy`, `Terms of Service`, and `About / Roadmap` public pages.
- [x] Add OpenGraph / Twitter meta tags, favicon assets, and SEO structured data schema.

## Testing & Quality Assurance
- [x] Add Feature tests for Search and Filtering across all view modes (Grid, Timeline, Calendar).
- [x] Add Feature tests for Profile update and avatar file uploads.
- [x] Add Feature test for unauthorized access (ensuring users cannot view, edit, or delete another user's events).
- [x] Add Livewire component unit tests for `UploadCSV`.
- [x] Add browser/E2E tests with Laravel Dusk or Pest for live client interactions.

## Documentation & Developer Experience
- [x] Update `README.md` with comprehensive setup instructions, environment variables breakdown, architecture overview, and screenshots.
- [x] Add API / internal architecture documentation detailing Event model calculations, notification dispatchers, and seeder formats.
- [x] Add Docker / Laravel Sail setup configuration for quick local onboarding.
- [x] Add GitHub Actions CI workflow for running linting (`php-cs-fixer`/`pint`) and automated test suites on push/PR.

- [x] update to latest minor version of laravel 11
- [x] modern date picker
- [x] birthdays as a category
- [x] fix error on new note blade page
- [x] when switching back to gradient theme from custom photo background it's not working
- [x] make the fullpage event view bigger 
- [x] improve mobile views

- [x] add pexels.com as an background image service alongside gradient and custom photo background
- [x] hit the pexel search endpoint and show the paginated results for user to select and store a copy in the disk for later user with search keyword
- [x] if the users tries to select image before entering event name show them Curated Photos from pexels
- [x] add prominent link to Pexels in homepage 

- [x] add Linking back to a Photo if used a photo by pexels in event details page
- [x] tell me where to store the pexels api key
- [x] show the rate limit headers statistics on a individual page under profile

- [x] private events should be blurred unless unlocked by entering 4 digit pin. Lock icon on nav bar to enable/disable private events visibility. timeout is 5 minutes
- [x] instead of categories events should be organized by tags

- [x] card menu z-index is not working correctly
- [x] field selection is not implemented