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
- [x] Implement event categorization filters (Personal, Work, Reminder, Past, Future) in the dashboard and navigation bar.
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