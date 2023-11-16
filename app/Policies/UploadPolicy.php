<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UploadPolicy
{
    public function create(User $user): bool
    {
        return $user->can("create post");
    }
    public function view(User $user): bool
    {
        return true;
    }
}
