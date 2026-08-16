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
            'event_name' => ['required', 'string', 'min:3', 'max:100'],
            'event_date' => ['required', 'date'],
            'event_time' => ['nullable', 'date_format:H:i'],
            'event_type' => ['required', 'string', 'in:Personal,Private,Work,Reminder,Past,Future'],
            'fields' => ['nullable', 'array'],
            'fields.*' => ['string', 'in:days,months,years,hours,weeks,minutes,seconds,workdays'],
            'is_background_image' => ['nullable', 'boolean'],
            'background_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
            'background_gradient' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'event_name.required' => 'An event name is required.',
            'event_name.min' => 'An event name should be at least 3 characters.',
            'event_name.max' => 'An event name must be less than 100 characters.',
            'event_date.required' => 'An event date is required.',
        ];       
    }
}
