<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUserImage extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    // protected function prepareForValidation()
    // {
    //     $this->merge(['user_id' => Auth::user()->id]);
    // }

    public function rules(): array
    {
        return [
            'image.*' => 'file|mimes:jpeg,png,jpg,gif|max:2000',

        ];
    }
}
