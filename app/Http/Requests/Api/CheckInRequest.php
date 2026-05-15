<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CheckInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        $branch = $this->user()->branch;
        $requireGeo = $branch?->require_geolocation ?? false;
        $requirePhoto = $branch?->require_liveness ?? false;

        return [
            'latitude' => $requireGeo ? 'required|numeric' : 'nullable|numeric',
            'longitude' => $requireGeo ? 'required|numeric' : 'nullable|numeric',
            'photo' => $requirePhoto ? 'required|image|max:2048' : 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.required' => 'Latitude is required.',
            'latitude.numeric' => 'Latitude must be a numeric value.',
            'longitude.required' => 'Longitude is required.',
            'longitude.numeric' => 'Longitude must be a numeric value.',
            'photo.image' => 'The photo must be an image file.',
            'photo.max' => 'The photo may not be greater than 2MB.',
        ];
    }
}