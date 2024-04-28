<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VoteRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => Auth::user()->id,
            // 'voteable_type' => $this->voteable_type,
            // 'voteable_id' => $this->voteable_id

        ]);
    }


    public function rules(): array
    {
        return [
            'user_id' => 'exists:users,id|required',
            'voteable_type' => 'required',
            'voteable_id' => 'required',
            'vote' => 'required|in:-1,0,1',
        ];
    }
}
