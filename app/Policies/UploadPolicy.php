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
    public function view(): bool
    {
        return true; //$user->can("create post");
    }
    public function viewAny(User $user)
    {
        return true; //$user->can("create post");
    }
}
