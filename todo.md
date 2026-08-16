# Project Tasks & To-Do List

## 1. Core Event Management (CRUD & Views)
- [ ] Implement `EventController::store()` to use actual request inputs (`event_name`, `event_date`, `event_type`, `fields`, background image/gradient upload) instead of hardcoded data and remove `dd($validated)`.
- [ ] Create Single Event view (`resources/views/layout/single.blade.php` or `resources/views/events/show.blade.php`) and implement `EventController::show()`.
- [ ] Implement `EventController::index()` or standardize dashboard vs events listing routes.
- [ ] Implement `EventController::edit()`, `EventController::update()`, and edit event view template.
- [ ] Implement `EventController::delete()` (destroy) method to handle event deletion.
- [ ] Add Form Request validation rules for file uploads, backgrounds, and time fields in `EventPostRequest`.

## 2. Event Display & Time Calculation
- [ ] Fix/complete dynamic background rendering on event cards (handling custom uploaded backgrounds and various gradient options).
- [ ] Expand countdown / time-lapse calculations in `App\Models\Event` to support remaining fields (weeks, minutes, seconds, workdays, hours).
- [ ] Add Calendar View and Timeline Mode for events as outlined in the project README.
- [ ] Implement event categorization filters (Personal, Work, Reminder, Past, Future) in the dashboard and navigation bar.
- [ ] Implement the search bar functionality in the navigation bar (`layouts/navigation.blade.php`).

## 3. CSV Import & Export
- [ ] Complete Livewire `UploadCSV` component: re-enable validation, map imported rows with user ID, and handle error logging properly.
- [ ] Implement CSV Export functionality (currently `profile/partials/export-events.blade.php` points to `profile.update` without export logic).
- [ ] Implement a dedicated controller/action for exporting events data as CSV.

## 4. User Profile & Avatar
- [ ] Implement `ProfileController::updateAvatar()` to handle avatar file upload, validation, storage, and database update.
- [ ] Connect the file input in `resources/views/profile/partials/update-avatar-form.blade.php` to the form submission.
- [ ] Add Settings page and route (currently `#` in navigation user menu).

## 5. Authentication & OAuth
- [ ] Fix Google OAuth callback in `GoogleAuthController::callback()`: change `Auth::login($user)` to `Auth::login($existingUser)` (currently passing Socialite user object instead of Eloquent model).
- [ ] Fix Google OAuth avatar download & storage handling.
- [ ] Remove `dd($e)` debug dump in Google OAuth exception handler and replace with proper error feedback.

## 6. Notifications & Mail
- [ ] Update `App\Notifications\EventAdded` to use real event data for Slack and Mail notifications instead of sample invoice text.
- [ ] Configure and test Google SMTP mail delivery and Slack webhook integration.
- [ ] Add in-app notification center / dropdown UI for the bell icon in the top navigation bar.

## 7. Testing & Quality Assurance
- [ ] Write Feature tests for Event CRUD operations (Create, Read, Update, Delete).
- [ ] Write Feature tests for CSV Import and Export.
- [ ] Write Feature tests for Google OAuth login flow.
- [ ] Write Unit tests for `Event` model date/time calculations and helper methods.
