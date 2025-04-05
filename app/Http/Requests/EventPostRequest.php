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
            'event_name' => ['required', 'string', 'min:3', 'max:25'],
            'event_date' => ['required', 'date'],
            // 'time' => ['nullable'],
            'event_type' => ['string'],
            'fields' => ['string'],
            'is_background_image' => ['boolean'],
            'background' => ['string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.min' => 'A Name should be atleast 3 characters.',
            'name.max' => 'A Name should be less than 25 characters.'
        ];       
    }
}
