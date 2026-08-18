<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_name' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date'],
            'event_time' => ['nullable', 'string'],
            'event_type' => ['required', 'string', 'in:Personal,Work,Reminder,Private,Birthday'],
            'fields' => ['nullable', 'array'],
            'is_background_image' => ['nullable'],
            'background_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:4096'],
            'background_gradient' => ['nullable', 'string'],
            'pexels_id' => ['nullable', 'string'],
            'pexels_photographer' => ['nullable', 'string', 'max:255'],
            'pexels_photographer_url' => ['nullable', 'url', 'max:500'],
            'pexels_photo_url' => ['nullable', 'url', 'max:500'],
            'pexels_download_url' => ['nullable', 'url'],
            'is_recurring' => ['nullable'],
            'recurrence_type' => ['nullable', 'string', 'in:none,yearly,monthly,weekly,daily'],
            'tags' => ['nullable'],
            'milestones' => ['nullable'],
            'milestones_raw' => ['nullable', 'string'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
