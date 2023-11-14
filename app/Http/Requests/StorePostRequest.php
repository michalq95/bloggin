<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePostRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    protected function prepareForValidation()
    {
        $this->merge(['user_id' => Auth::user()->id]);
    }

    public function rules(): array
    {
        return [
            'user_id' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            "image" => 'nullable',
            'image.*' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2000',
            'uploads.*' => 'nullable|exists:uploads,id',

        ];
    }
}
