<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUploadsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => Auth::user()->id,

        ]);
    }
    public function rules(): array
    {
        return [
            'file.*' => 'file|max:10240',
            'user_id' => 'exists:users,id',
        ];
    }
}
