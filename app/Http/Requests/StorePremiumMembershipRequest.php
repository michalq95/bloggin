<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePremiumMembershipRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::user()->hasRole("admin");
    }



    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'active' => 'required|boolean',
            'expiration_date' => 'required|date|after:tomorrow'
        ];
    }
}
