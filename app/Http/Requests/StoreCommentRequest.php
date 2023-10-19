<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCommentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // dd($this->attributes->get("commentable_type"));
        $this->merge([
            'user_id' => Auth::user()->id,
            "commentable_type" => $this->attributes->get("commentable_type"),
            "commentable_id" => $this->attributes->get("commentable_id")
        ]);
    }


    public function rules(): array
    {
        return [
            'user_id' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'commentable_type' => 'required',
            'commentable_id' => 'required',
            'description' => 'nullable|string',
            "image" => 'nullable',
            'image.*' => 'file|mimes:jpeg,png,jpg,gif|max:2000',
        ];
    }
}
